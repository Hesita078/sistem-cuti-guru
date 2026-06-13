<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class LeaveService
{
    public function __construct(private readonly HolidayService $holidayService) {}

    public function createRequest(User $user, array $data): LeaveRequest
    {
        $start = Carbon::parse($data['start_date'])->startOfDay();
        $end   = Carbon::parse($data['end_date'])->startOfDay();
        $tahun = $start->year;

        if ($start->lt(now()->startOfDay())) {
            throw ValidationException::withMessages([
                'start_date' => 'Tanggal mulai tidak boleh di masa lalu.',
            ]);
        }

        $totalDays = $this->holidayService->countWorkDays($start, $end);

        if ($totalDays === 0) {
            throw ValidationException::withMessages([
                'start_date' => 'Rentang tanggal tidak mengandung hari kerja efektif.',
            ]);
        }

        // Hitung sisa kuota cuti tahun ini
        $sudahDipakai = LeaveRequest::where('user_id', $user->id)
            ->where('tahun', $tahun)
            ->where('status', 'approved')
            ->sum('total_days');

        $sisa = $user->hak_cuti - $sudahDipakai;

        if ($totalDays > $sisa) {
            throw ValidationException::withMessages([
                'start_date' => "Sisa kuota cuti Anda tinggal {$sisa} hari, tidak cukup untuk {$totalDays} hari.",
            ]);
        }

        // Cek overlap
        $overlap = LeaveRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(fn($q2) => $q2->where('start_date', '<=', $start)->where('end_date', '>=', $end));
            })
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'start_date' => 'Sudah ada pengajuan cuti dalam rentang tanggal ini.',
            ]);
        }

        return LeaveRequest::create([
            'user_id'    => $user->id,
            'start_date' => $start->toDateString(),
            'end_date'   => $end->toDateString(),
            'total_days' => $totalDays,
            'tahun'      => $tahun,
            'reason'     => $data['reason'],
            'status'     => 'pending',
        ]);
    }

    public function approve(LeaveRequest $leave, User $approver): LeaveRequest
    {
        // Hanya kepala_sekolah atau admin yang boleh approve
        abort_if(!in_array($approver->role, ['kepala_sekolah', 'admin']), 403, 'Tidak punya akses.');
        abort_if(!$leave->isPending(), 422, 'Pengajuan bukan status pending.');

        $leave->update([
            'status'      => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);

        return $leave->fresh();
    }

    public function reject(LeaveRequest $leave, User $approver, string $note): LeaveRequest
    {
        abort_if(!in_array($approver->role, ['kepala_sekolah', 'admin']), 403, 'Tidak punya akses.');
        abort_if(!$leave->isPending(), 422, 'Pengajuan bukan status pending.');

        $leave->update([
            'status'         => 'rejected',
            'approved_by'    => $approver->id,
            'approved_at'    => now(),
            'rejection_note' => $note,
        ]);

        return $leave->fresh();
    }

    // Hitung sisa kuota cuti user
    public function sisaKuota(User $user, int $tahun): int
    {
        $dipakai = LeaveRequest::where('user_id', $user->id)
            ->where('tahun', $tahun)
            ->where('status', 'approved')
            ->sum('total_days');

        return max(0, $user->hak_cuti - $dipakai);
    }
}

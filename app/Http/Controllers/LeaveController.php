<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Services\HolidayService;
use App\Services\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function __construct(
        private readonly LeaveService   $leaveService,
        private readonly HolidayService $holidayService,
    ) {}

    // Daftar cuti guru yang login
    public function index()
    {
        $user   = Auth::user();
        $leaves = LeaveRequest::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        $sisaKuota = $this->leaveService->sisaKuota($user, now()->year);

        return view('leaves.index', compact('leaves', 'sisaKuota'));
    }

    // Form ajukan cuti
    public function create()
    {
        $user      = Auth::user();
        $sisaKuota = $this->leaveService->sisaKuota($user, now()->year);

        return view('leaves.create', compact('sisaKuota'));
    }

    // Simpan pengajuan cuti
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|min:10|max:1000',
        ]);

        $this->leaveService->createRequest(Auth::user(), $request->all());

        return redirect()->route('leaves.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim, menunggu persetujuan.');
    }

    // Detail pengajuan cuti
    public function show(LeaveRequest $leave)
    {
        // Guru hanya bisa lihat miliknya sendiri
        if (Auth::user()->role === 'guru') {
            abort_if($leave->user_id !== Auth::id(), 403);
        }

        return view('leaves.show', compact('leave'));
    }

    // Preview hitung hari kerja (dipanggil via fetch/ajax dari form)
    public function previewWorkDays(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $start    = Carbon::parse($request->start_date);
        $end      = Carbon::parse($request->end_date);
        $workDays = $this->holidayService->countWorkDays($start, $end);
        $holidays = Holiday::getHolidayDatesInRange($start, $end);

        return response()->json([
            'work_days'     => $workDays,
            'holiday_dates' => $holidays,
        ]);
    }

    // ── Khusus kepala_sekolah & admin ───────────────────────

    // Daftar semua pengajuan cuti (untuk kepala sekolah/admin)
    public function adminIndex(Request $request)
    {
        abort_if(!in_array(Auth::user()->role, ['kepala_sekolah', 'admin']), 403);

        $leaves = LeaveRequest::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('leaves.admin-index', compact('leaves'));
    }

    // Approve cuti
    public function approve(LeaveRequest $leave)
    {
        $this->leaveService->approve($leave, Auth::user());

        return back()->with('success', 'Pengajuan cuti disetujui.');
    }

    // Reject cuti
    public function reject(Request $request, LeaveRequest $leave)
    {
        $request->validate(['rejection_note' => 'required|string|max:500']);

        $this->leaveService->reject($leave, Auth::user(), $request->rejection_note);

        return back()->with('success', 'Pengajuan cuti ditolak.');
    }
}

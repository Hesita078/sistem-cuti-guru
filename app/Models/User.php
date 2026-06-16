<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'nip',
        'jabatan',
        'no_telp',
        'alamat',
        'hak_cuti_tahunan',
        'hak_cuti_sakit',
        'hak_cuti_melahirkan',
        'hak_cuti_haji',
        'hak_cuti_penting',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function pengajuanCuti()
    {
        return $this->hasMany(PengajuanCuti::class);
    }

    public function historiCuti()
    {
        return $this->hasMany(HistoriCuti::class);
    }

    // ============================================
    // ROLE HELPERS
    // ============================================

    public function isKepalaSekolah()
    {
        return strtolower($this->role) === 'kepala_sekolah';
    }

    public function isAdmin()
    {
        return strtolower($this->role) === 'admin';
    }

    public function isGuru()
    {
        return strtolower($this->role) === 'guru';
    }

    // ============================================
    // HAK CUTI
    // ============================================

    public function resetHakCuti()
    {
        $this->update([
            'hak_cuti_tahunan'    => 12,
            'hak_cuti_sakit'      => 14,
            'hak_cuti_melahirkan' => 90,
            'hak_cuti_haji'       => 40,
            'hak_cuti_penting'    => 5,
        ]);
    }

    // Helper: ambil hak cuti berdasarkan jenis
    public function getHakCutiByJenis(string $jenis): int
    {
        return match($jenis) {
            'Cuti Tahunan'        => $this->hak_cuti_tahunan    ?? 12,
            'Cuti Sakit'          => $this->hak_cuti_sakit       ?? 14,
            'Cuti Melahirkan'     => $this->hak_cuti_melahirkan  ?? 90,
            'Cuti Ibadah Haji'    => $this->hak_cuti_haji        ?? 40,
            'Cuti Alasan Penting' => $this->hak_cuti_penting     ?? 5,
            default               => 0,
        };
    }
}

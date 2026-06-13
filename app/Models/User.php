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
        // 'telepon',
        // 'hak_cuti_tahunan',
        'hak_cuti',
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
            'hak_cuti' => 12, // reset ke default 12 hari
        ]);
    }
}

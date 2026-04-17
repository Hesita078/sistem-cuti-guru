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
        'hak_cuti',
        'nip',
        'no_telp',
        'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Ini penting!
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship: User memiliki banyak pengajuan cuti
    public function pengajuanCuti()
    {
        return $this->hasMany(PengajuanCuti::class);
    }

    // Relationship: User memiliki banyak histori cuti
    public function historiCuti()
    {
        return $this->hasMany(HistoriCuti::class);
    }

    // Helper method: Cek apakah user adalah Kepala Sekolah
    public function isKepalaSekolah()
    {
        return $this->role === 'Kepala Sekolah';
    }

    // Helper method: Cek apakah user adalah Admin
    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    // Helper method: Cek apakah user adalah Guru
    public function isGuru()
    {
        return $this->role === 'Guru';
    }
}

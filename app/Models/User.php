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
}

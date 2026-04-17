<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua user dulu
        User::truncate();

        // Guru
        User::create([
            'name' => 'Rina Wati',
            'email' => 'rina.wati@sd-example.sch.id',
            'password' => Hash::make('password'),
            'role' => 'Guru',
            'nip' => '198501152010012001',
            'hak_cuti' => 12,
        ]);

        // Admin
        User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sd-example.sch.id',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'nip' => '198001012005011001',
        ]);

        // Kepala Sekolah
        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@sd-example.sch.id',
            'password' => Hash::make('password'),
            'role' => 'Kepala Sekolah',
            'nip' => '197501012000011001',
        ]);
    }
}

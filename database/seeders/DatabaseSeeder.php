<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Kepala Sekolah
        User::create([
            'nama' => 'Dr. Budi Santoso, M.Pd',
            'email' => 'kepala.sekolah@sd-example.sch.id',
            'password' => Hash::make('password'),
            'role' => 'Kepala Sekolah',
            'hak_cuti' => 12,
            'nip' => '196501011990031001',
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Pendidikan No. 1, Surabaya',
        ]);

        // Admin
        User::create([
            'nama' => 'Siti Nurhaliza, S.Kom',
            'email' => 'admin@sd-example.sch.id',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'hak_cuti' => 12,
            'nip' => '198505152010012001',
            'no_telp' => '081234567891',
            'alamat' => 'Jl. Administrasi No. 5, Surabaya',
        ]);

        // Guru 1
        User::create([
            'nama' => 'Rina Wati, S.Pd',
            'email' => 'rina.wati@sd-example.sch.id',
            'password' => Hash::make('password'),
            'role' => 'Guru',
            'hak_cuti' => 12,
            'nip' => '199001012015012002',
            'no_telp' => '081234567892',
            'alamat' => 'Jl. Guru No. 10, Surabaya',
        ]);

        // Guru 2
        User::create([
            'nama' => 'Ahmad Hidayat, S.Pd',
            'email' => 'ahmad.hidayat@sd-example.sch.id',
            'password' => Hash::make('password'),
            'role' => 'Guru',
            'hak_cuti' => 12,
            'nip' => '198803052012011001',
            'no_telp' => '081234567893',
            'alamat' => 'Jl. Pendidik No. 15, Surabaya',
        ]);

        // Guru 3 - Dengan hak cuti 0 untuk testing
        User::create([
            'nama' => 'Dewi Lestari, S.Pd',
            'email' => 'dewi.lestari@sd-example.sch.id',
            'password' => Hash::make('password'),
            'role' => 'Guru',
            'hak_cuti' => 0, // Untuk testing tombol disabled
            'nip' => '199205102015022001',
            'no_telp' => '081234567894',
            'alamat' => 'Jl. Pengajar No. 20, Surabaya',
        ]);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pagination Bootstrap 5
        Paginator::useBootstrapFive();

        // Pesan validasi required bahasa Indonesia
        Validator::replacer('required', function ($message, $attribute) {

            $attributes = [
                'nip' => 'NIP',
                'nama' => 'Nama Lengkap',
                'email' => 'Email',
                'role' => 'Role',
                'password' => 'Password',
                'password_confirmation' => 'Konfirmasi Password',
                'telepon' => 'Nomor Telepon',
                'hak_cuti_tahunan' => 'Hak Cuti Tahunan',
                'alamat' => 'Alamat',
            ];

            $attribute = $attributes[$attribute] ?? $attribute;

            return "{$attribute} wajib diisi.";
        });
    }
}

<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('cuti-bersama:sync')
    ->monthly()
    ->withoutOverlapping();

Schedule::command('cuti-bersama:sync ' . (date('Y') + 1))
    ->yearlyOn(12, 1, '07:00')
    ->withoutOverlapping();

Schedule::command('cuti:reset')->yearlyOn(1, 1, '00:00'); // Setiap 1 Januari jam 00:00
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

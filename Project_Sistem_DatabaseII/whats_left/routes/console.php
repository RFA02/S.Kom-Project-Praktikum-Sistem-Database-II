<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Kedaluwarsakan listing dan rapikan reservasi yang jam pickup-nya lewat.
// Jalankan scheduler: php artisan schedule:work (atau pakai run-scheduler.bat di Windows).
Schedule::command('whatsleft:sweep-stale')
    ->everyMinute()
    ->withoutOverlapping()
    ->name('sweep-stale-reservations');

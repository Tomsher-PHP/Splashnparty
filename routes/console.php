<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::command('queue:work --stop-when-empty')
        ->everyMinute()
        ->withoutOverlapping();

// Schedule::command('reports:send-daily-branch-bookings')
//         ->everyMinute()->withoutOverlapping();

// Schedule::command('reports:send-monthly-branch-bookings')
//         ->everyMinute()->withoutOverlapping();


/*
 * Daily report:
 *
 * Runs shortly after midnight and generates the report
 * for the previous completed calendar day.
 *
 * Example:
 * 23 Sep 00:05 -> Report for 22 Sep
 */
Schedule::command('reports:send-daily-branch-bookings')
        ->dailyAt('00:05')
        ->withoutOverlapping();


/*
 * Monthly report remains unchanged.
 */
Schedule::command('reports:send-monthly-branch-bookings')
        ->monthlyOn(1, '09:00');

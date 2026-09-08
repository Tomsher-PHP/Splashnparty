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

// // Daily Branchwise Booking Report at 9:00 AM
// Schedule::command('reports:send-daily-branch-bookings')
//         ->dailyAt('09:00');

// // Monthly Branchwise Booking Report on the 1st day of every month at 9:00 AM
// Schedule::command('reports:send-monthly-branch-bookings')
//         ->monthlyOn(1, '09:00');


Schedule::command('reports:send-daily-branch-bookings')
        ->everyMinute()->withoutOverlapping();

Schedule::command('reports:send-monthly-branch-bookings')
        ->everyMinute()->withoutOverlapping();
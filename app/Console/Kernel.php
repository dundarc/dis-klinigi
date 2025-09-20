<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Adım 10'da eklediğimiz komut zamanlaması.
        // Başka bir kod olmamalı.
        $schedule->command('app:send-reminders')
                 ->dailyAt('19:00')
                 ->timezone('Europe/Istanbul');
    }
}
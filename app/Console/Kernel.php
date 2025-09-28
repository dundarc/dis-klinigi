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

        $schedule->command('appointments:handle-missed')->daily();
        
        // Payment reminder and overdue status updates
        $schedule->command('payments:check-reminders --update-overdue')
                 ->dailyAt('09:00')
                 ->timezone('Europe/Istanbul');
                 
        $schedule->command('payments:check-reminders --days=3')
                 ->dailyAt('08:00')
                 ->timezone('Europe/Istanbul');
    }
}
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
        // $schedule->command('inspire')->hourly();
        $schedule->call('\App\Http\Controllers\AgingPullOutController@agingControllerEPC')->timezone('Asia/Manila')->dailyAt('08:00');
        $schedule->call('\App\Http\Controllers\AgingPullOutController@agingControllerNBFI')->timezone('Asia/Manila')->dailyAt('08:00');
        $schedule->call('\App\Http\Controllers\PromoMaintenanceController@promoMaintenance')->timezone('Asia/Manila')->dailyAt('08:00');
        $schedule->call('\App\Http\Controllers\AgingPullOutController@SLACountNBFI')->timezone('Asia/Manila')->dailyAt('08:00');
        $schedule->call('\App\Http\Controllers\AgingPullOutController@SLACountEPC')->timezone('Asia/Manila')->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

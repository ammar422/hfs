<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Ranks\Jobs\UpgradeRanks;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('commissions:binary')->everyMinute();
        // $schedule->job(new UpgradeRanks)->everyMinute();
        $schedule->command('commissions:binary')->weeklyOn(5, '7:00'); // Friday at 7 AM
        $schedule->job(new UpgradeRanks)->quarterly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

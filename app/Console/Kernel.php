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
        // Daily database backup at 2:00 AM
        // The command will automatically use the BACKUP_PASSWORD from .env in non-interactive mode
        $schedule->command('app:backup-database')
            ->dailyAt('02:00')
            ->appendOutputTo(storage_path('logs/backup.log'))
            ->emailOutputOnFailure(env('ADMIN_EMAIL'))
            ->emailOutputTo(env('ADMIN_EMAIL'));
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

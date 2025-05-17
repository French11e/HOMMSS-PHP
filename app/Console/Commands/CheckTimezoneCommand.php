<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckTimezoneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-timezone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the application timezone settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Application Timezone Information:');
        $this->info('----------------------------------');
        $this->info('Configured timezone: ' . config('app.timezone'));
        $this->info('Current time: ' . Carbon::now()->format('Y-m-d H:i:s'));
        $this->info('Current time (UTC): ' . Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i:s'));
        $this->info('PHP default timezone: ' . date_default_timezone_get());
        
        // Check if the scheduler would run at 2:00 AM
        $now = Carbon::now();
        $scheduledTime = Carbon::createFromFormat('Y-m-d H:i', $now->format('Y-m-d') . ' 02:00');
        
        if ($now->hour < 2) {
            $this->info('Next scheduled backup will run today at 2:00 AM (' . $scheduledTime->format('Y-m-d H:i:s') . ')');
        } else {
            $scheduledTime = $scheduledTime->addDay();
            $this->info('Next scheduled backup will run tomorrow at 2:00 AM (' . $scheduledTime->format('Y-m-d H:i:s') . ')');
        }
        
        return Command::SUCCESS;
    }
}

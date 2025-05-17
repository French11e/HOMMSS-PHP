<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email to the admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminEmail = env('ADMIN_EMAIL');
        
        if (empty($adminEmail)) {
            $this->error('Admin email not configured. Please set ADMIN_EMAIL in your .env file.');
            return Command::FAILURE;
        }
        
        $this->info('Sending test email to: ' . $adminEmail);
        
        try {
            Mail::raw('This is a test email from the backup system to verify that email notifications are working correctly.', function (Message $message) use ($adminEmail) {
                $message->to($adminEmail)
                    ->subject('Backup System - Email Test');
            });
            
            $this->info('Test email sent successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to send test email: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Backup\Tasks\Backup\Zip;
use App\Support\WindowsCompatibleZip;

class BackupServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Only bind our custom Zip class if Windows compatibility is enabled
        if (config('backup.backup.windows_compatibility', false)) {
            $this->app->bind(Zip::class, function ($app, $parameters) {
                return new WindowsCompatibleZip($parameters['filename'] ?? '');
            });
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

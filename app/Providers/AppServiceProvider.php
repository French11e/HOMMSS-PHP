<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // You can remove the custom validation rule since we're manually checking the password now
        if (config('app.env') === 'local') {
            DB::listen(function ($query) {
                Log::info(
                    $query->sql,
                    [
                        'bindings' => $query->bindings,
                        'time' => $query->time
                    ]
                );
            });
        }
    }
}


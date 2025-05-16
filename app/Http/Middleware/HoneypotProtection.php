<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HoneypotProtection
{
    public function handle(Request $request, Closure $next)
    {
        // Only check POST requests
        if ($request->isMethod('post')) {
            // Check honeypot field
            if ($request->filled('honeypot')) {
                // Log the attempt
                Log::warning('Honeypot triggered', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl()
                ]);

                abort(403, 'Spam detected');
            }

            // Check timestamp if present
            if ($request->has('timestamp')) {
                $now = time();
                $formTime = (int)$request->input('timestamp');

                // Form must be submitted between 2 seconds and 1 hour
                if ($now - $formTime < 2 || $now - $formTime > 3600) {
                    // Log the attempt
                    Log::warning('Honeypot time validation failed', [
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'url' => $request->fullUrl(),
                        'time_diff' => $now - $formTime
                    ]);

                    abort(403, 'Invalid form submission timing');
                }
            }
        }

        return $next($request);
    }
}

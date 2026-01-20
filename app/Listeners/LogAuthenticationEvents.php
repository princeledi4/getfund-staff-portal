<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogAuthenticationEvents
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        if ($event instanceof Login) {
            Log::info('User logged in', [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'timestamp' => now(),
            ]);
        } elseif ($event instanceof Logout) {
            Log::info('User logged out', [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'timestamp' => now(),
            ]);
        } elseif ($event instanceof Failed) {
            Log::warning('Failed login attempt', [
                'email' => $event->credentials['email'] ?? 'unknown',
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'timestamp' => now(),
            ]);
        } elseif ($event instanceof Lockout) {
            Log::warning('Account lockout', [
                'email' => $event->request->input('email'),
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'timestamp' => now(),
            ]);
        }
    }
}

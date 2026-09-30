<?php

namespace App\Listeners;

use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        ActivityLogger::log('login', $event->user, null, null, ['guard' => $event->guard, 'ip' => request()->ip()]);
    }
}

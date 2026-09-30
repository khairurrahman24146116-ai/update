<?php

namespace App\Listeners;

use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        ActivityLogger::log('logout', $event->user, null, null, ['guard' => $event->guard, 'ip' => request()->ip()]);
    }
}

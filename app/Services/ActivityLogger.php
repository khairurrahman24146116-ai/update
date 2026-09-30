<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $action, User|int|null $user = null, ?string $modelType = null, int|string|null $modelId = null, ?array $payload = null): ActivityLog
    {
        $userId = $user instanceof User ? $user->id : ($user ?? Auth::id());

        return ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId ? (int) $modelId : null,
            'payload' => $payload,
        ]);
    }
}

<?php

namespace App\Service;

use App\Models\Platform;
use App\Models\User;

class PlatformService
{
    public function list(?string $user_id)
    {
        return Platform::orderByDesc('created_at')
            ->when(!is_null($user_id), fn($q) => $q->whereHas('users', fn($q) => $q->where('user_id', $user_id)))
            ->get();
    }

    public function syncPlatforms(array $platforms, User $user): bool
    {
        return count($user->platforms()->sync($platforms));
    }
}

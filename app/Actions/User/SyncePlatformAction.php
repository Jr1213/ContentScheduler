<?php

namespace App\Actions\User;

use App\Service\PlatformService;

class SyncePlatformAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly PlatformService $platformService) {}

    public function handle(array $platfomrs): bool
    {

        return $this->platformService->syncPlatforms($platfomrs, request()->user());
    }
}

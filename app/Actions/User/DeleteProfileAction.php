<?php

namespace App\Actions\User;

use App\Service\UserService;

class DeleteProfileAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly UserService $userService)
    {
        //
    }

    public function handel(): bool
    {
        $user = request()->user();

        $this->userService->delete($user);

        //TODO delete all user posts and platform active


        return true;
    }
}

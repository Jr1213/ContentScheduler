<?php

namespace App\Actions\User;

use App\Dtos\Auth\UserDto;
use App\Service\UserService;

class UpdatePasswordAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly UserService $userService)
    {
        //
    }

    public function handle(string $password): bool
    {
        $user = request()->user();

        $hashedPassword = $this->userService->hashPassword($password);

        $userDto = UserDto::create(['password' => $hashedPassword]);

        return $this->userService->update($userDto, $user);
    }
}

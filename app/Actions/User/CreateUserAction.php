<?php

namespace App\Actions\User;

use App\Dtos\Auth\UserDto;
use App\Service\UserService;

class CreateUserAction
{
    public function __construct(private readonly UserService $userService) {}
    public function handel($data): array
    {
        //hash password,
        $data['password'] = $this->userService->hashPassword($data['password']);
        // create dto,
        $userDto = UserDto::create($data);
        // create user,

        $user = $this->userService->store($userDto);
        // create token
        $token = $this->userService->createUserToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}

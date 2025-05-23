<?php

namespace App\Actions\User;

use App\Dtos\Auth\LoginDto;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;

class LoginAction
{
    public function __construct(private readonly UserService $userService) {}
    public function handel(LoginDto $loginDto): array
    {

        $user = $this->userService->getUserByEmail($loginDto->email);

        if (!$user || ! $this->userService->checkPassword($loginDto->password, $user->password)) {
            throw new \Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        //generate token
        $token = $this->userService->createUserToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}

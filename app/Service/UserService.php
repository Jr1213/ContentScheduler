<?php

namespace App\Service;

use App\Dtos\Auth\UserDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getPrfile(int $id): User
    {
        return User::findOrFail($id);
    }

    public function store(UserDto $userDto): User
    {
        $data = $userDto->toArray();

        return User::create($data);
    }

    public function update(UserDto $userDto,User $user):bool
    {
        return $user->update($userDto->toArray());
    }


    public function delete(User $user):bool
    {
        return $user->delete();
    }



    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function hashPassword(string $password): string
    {
        return Hash::make($password);
    }

    public function checkPassword(string $plainPassword, string $hashPassword): bool
    {
        return Hash::check($plainPassword, $hashPassword);
    }
    public function createUserToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}

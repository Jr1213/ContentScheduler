<?php

namespace App\Actions\User;

use App\Dtos\Auth\UserDto;
use App\Service\UserService;

class UpdateProfileAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly UserService $userService)
    {
        //
    }

    public function handle($data):array
    {
        $user = request()->user();

        $userDto = $this->getUserDto($data);

        $this->userService->update($userDto, $user);


        return [
            'user' => $user->refresh()
        ];
    }


    private function getUserDto(array $data):UserDto 
    {
        $clearItems = [];
        isset($data['name']) && !is_null($data['name']) ? $clearItems['name'] = $data['name'] : null;
        isset($data['email']) && !is_null($data['email']) ? $clearItems['email'] = $data['email'] : null;

        return UserDto::create($clearItems);
    }
}

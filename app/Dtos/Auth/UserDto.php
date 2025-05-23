<?php

namespace App\Dtos\Auth;

use App\Traits\StaticCreateSelf;
use App\Traits\ToArray;

class UserDto
{
    use StaticCreateSelf, ToArray;

    public readonly ?string $name;

    public readonly ?string $email;

    public readonly ?string $password;
}

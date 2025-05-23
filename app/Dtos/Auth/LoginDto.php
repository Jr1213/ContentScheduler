<?php

namespace App\Dtos\Auth;

use App\Traits\StaticCreateSelf;
use App\Traits\ToArray;

class LoginDto
{
    use StaticCreateSelf, ToArray;

    public string $email;
    public string $password;
}

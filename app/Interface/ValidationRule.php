<?php

namespace App\Interface;

interface ValidationRule
{
    public function validate(): bool;
}

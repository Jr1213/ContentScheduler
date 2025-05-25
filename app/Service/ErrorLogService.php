<?php

namespace App\Service;

use Illuminate\Support\Facades\Log;

class ErrorLogService
{
    public function log($message)
    {
        Log::error($message);
    }
}

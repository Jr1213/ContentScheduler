<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class UploadHelper
{
    public static function store(UploadedFile $file, string $path): string
    {
        return $file->store($path, 'public');
    }
}

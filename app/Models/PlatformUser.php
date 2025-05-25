<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformUser extends Model
{
    /** @use HasFactory<\Database\Factories\PlatformUserFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform_id'
    ];
}

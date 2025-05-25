<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rule extends Model
{
    /** @use HasFactory<\Database\Factories\RuleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'path',
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'integer'
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function getInstance(Post $post): ?object
    {
        if (class_exists($this->path)) {
            return new $this->path($post, $this->value, $this->key);
        }

        return null;
    }
}

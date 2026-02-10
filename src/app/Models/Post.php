<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    public $timestamps = false;

    protected $fillable = ['thread_id', 'post_number', 'name', 'body', 'created_at'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}

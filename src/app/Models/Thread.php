<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    protected $fillable = ['title'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function scopeSearchByTitle(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, fn (Builder $q) => $q->where('title', 'like', "%{$keyword}%"));
    }
}

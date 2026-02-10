<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Thread;

class PostController extends Controller
{
    public function store(StorePostRequest $request, Thread $thread)
    {
        $validated = $request->validated();

        $nextNumber = $thread->posts()->max('post_number') + 1;

        $thread->posts()->create([
            'post_number' => $nextNumber,
            'name' => ($validated['name'] ?? '') ?: '名無しさん',
            'body' => $validated['body'],
            'created_at' => now(),
        ]);

        $thread->touch();

        return redirect()->route('threads.show', $thread)->withFragment("post-{$nextNumber}");
    }
}

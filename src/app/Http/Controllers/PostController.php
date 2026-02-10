<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, Thread $thread)
    {
        $validated = $request->validate([
            'name' => 'nullable|max:100',
            'body' => 'required',
        ]);

        $nextNumber = $thread->posts()->max('post_number') + 1;

        $thread->posts()->create([
            'post_number' => $nextNumber,
            'name' => $validated['name'] ?: '名無しさん',
            'body' => $validated['body'],
            'created_at' => now(),
        ]);

        $thread->touch();

        return redirect()->route('threads.show', $thread)->withFragment("post-{$nextNumber}");
    }
}

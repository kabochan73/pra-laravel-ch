<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::withCount('posts')
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('threads.index', compact('threads'));
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'name' => 'nullable|max:100',
            'body' => 'required',
        ]);

        $thread = Thread::create(['title' => $validated['title']]);

        $thread->posts()->create([
            'post_number' => 1,
            'name' => ($validated['name'] ?? '') ?: '名無しさん',
            'body' => $validated['body'],
            'created_at' => now(),
        ]);

        return redirect()->route('threads.show', $thread);
    }

    public function show(Thread $thread)
    {
        $posts = $thread->posts()->orderBy('post_number')->get();

        return view('threads.show', compact('thread', 'posts'));
    }
}

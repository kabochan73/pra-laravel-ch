<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThreadRequest;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index(Request $request)
    {
        $threads = Thread::withCount('posts')
            ->searchByTitle($request->query('keyword'))
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('threads.index', compact('threads'));
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(StoreThreadRequest $request)
    {
        $validated = $request->validated();

        $thread = Thread::create(['title' => $validated['title']]);

        $thread->posts()->create([
            'post_number' => 1,
            'name' => ($validated['name'] ?? '') ?: '名無しさん',
            'body' => $validated['body'],
            'created_at' => now(),
        ]);

        return redirect()->route('threads.index');
    }

    public function show(Thread $thread)
    {
        $posts = $thread->posts()->orderBy('post_number')->get();

        return view('threads.show', compact('thread', 'posts'));
    }
}

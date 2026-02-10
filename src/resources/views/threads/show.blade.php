@extends('layouts.app')

@section('title', $thread->title . ' - 匿名掲示板')

@section('content')
    <h1 class="text-2xl font-bold mb-4">{{ $thread->title }}</h1>

    <div class="space-y-2 mb-8">
        @foreach ($posts as $post)
            <div id="post-{{ $post->post_number }}" class="bg-white rounded shadow px-4 py-3">
                <div class="text-sm mb-1">
                    <span class="font-bold text-green-800">{{ $post->post_number }}</span>
                    <span class="font-semibold text-green-700">{{ $post->name }}</span>
                    <span class="text-gray-400 ml-2">{{ $post->created_at->format('Y/m/d(D) H:i:s') }}</span>
                </div>
                <div class="whitespace-pre-wrap">{{ $post->body }}</div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-bold mb-3">レスを書き込む</h2>

        <form action="{{ route('posts.store', $thread) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="block text-sm font-semibold mb-1">名前（空欄で「名無しさん」）</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" maxlength="100"
                       class="w-full border rounded px-3 py-2" placeholder="名無しさん">
            </div>

            <div class="mb-3">
                <label for="body" class="block text-sm font-semibold mb-1">本文 <span class="text-red-500">*</span></label>
                <textarea name="body" id="body" rows="4" required
                          class="w-full border rounded px-3 py-2 @error('body') border-red-500 @enderror">{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-red-800 text-white px-6 py-2 rounded font-semibold hover:bg-red-700">
                書き込む
            </button>
        </form>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'スレッド一覧 - 匿名掲示板')

@section('content')
    <h1 class="text-2xl font-bold mb-4">スレッド一覧</h1>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-white rounded shadow px-4 py-3 text-center">
            <div class="text-sm text-gray-500">スレッド数</div>
            <div class="text-2xl font-bold text-blue-800">{{ number_format($threadCount) }}</div>
        </div>
        <div class="bg-white rounded shadow px-4 py-3 text-center">
            <div class="text-sm text-gray-500">総投稿数</div>
            <div class="text-2xl font-bold text-blue-800">{{ number_format($postCount) }}</div>
        </div>
    </div>

    <form action="{{ route('threads.index') }}" method="GET" class="mb-4 flex gap-2">
        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="スレッドタイトルで検索"
               class="border border-gray-300 rounded px-3 py-2 flex-1">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">検索</button>
    </form>

    @forelse ($threads as $thread)
        <div class="bg-white rounded shadow mb-2 px-4 py-3 hover:bg-gray-50">
            <a href="{{ route('threads.show', $thread) }}" class="block">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-blue-800">{{ $thread->title }}</span>
                    <span class="text-sm text-gray-500">({{ $thread->posts_count }})</span>
                </div>
                <div class="text-xs text-gray-400 mt-1">
                    最終更新: {{ $thread->updated_at->format('Y/m/d H:i') }}
                </div>
            </a>
        </div>
    @empty
        <p class="text-gray-500">スレッドがまだありません。</p>
    @endforelse

    <div class="mt-4">
        {{ $threads->links() }}
    </div>
@endsection

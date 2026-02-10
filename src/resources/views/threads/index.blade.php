@extends('layouts.app')

@section('title', 'スレッド一覧 - 匿名掲示板')

@section('content')
    <h1 class="text-2xl font-bold mb-4">スレッド一覧</h1>

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

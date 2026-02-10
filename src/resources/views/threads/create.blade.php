@extends('layouts.app')

@section('title', '新規スレッド作成 - 匿名掲示板')

@section('content')
    <h1 class="text-2xl font-bold mb-4">新規スレッド作成</h1>

    <form action="{{ route('threads.store') }}" method="POST" class="bg-white rounded shadow p-6">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-sm font-semibold mb-1">スレッドタイトル <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="255"
                   class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror">
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-semibold mb-1">名前（空欄で「名無しさん」）</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" maxlength="100"
                   class="w-full border rounded px-3 py-2" placeholder="名無しさん">
        </div>

        <div class="mb-4">
            <label for="body" class="block text-sm font-semibold mb-1">本文 <span class="text-red-500">*</span></label>
            <textarea name="body" id="body" rows="5" required
                      class="w-full border rounded px-3 py-2 @error('body') border-red-500 @enderror">{{ old('body') }}</textarea>
            @error('body')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-red-800 text-white px-6 py-2 rounded font-semibold hover:bg-red-700">
            スレッドを立てる
        </button>
    </form>
@endsection

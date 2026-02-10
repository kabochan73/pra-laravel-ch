<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('threads.index'));

Route::get('/threads', [ThreadController::class, 'index'])->name('threads.index');
Route::get('/threads/create', [ThreadController::class, 'create'])->name('threads.create');
Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
Route::get('/threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');
Route::post('/threads/{thread}/posts', [PostController::class, 'store'])->name('posts.store');

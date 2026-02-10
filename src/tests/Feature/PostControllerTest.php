<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createThreadWithPost(): Thread
    {
        $thread = Thread::create(['title' => 'テストスレッド']);
        $thread->posts()->create([
            'post_number' => 1,
            'name' => '名無しさん',
            'body' => '最初の投稿',
            'created_at' => now(),
        ]);

        return $thread;
    }

    public function test_レスを投稿できる(): void
    {
        $thread = $this->createThreadWithPost();

        $response = $this->post(route('posts.store', $thread), [
            'name' => 'テスト太郎',
            'body' => 'テスト本文です',
        ]);

        $response->assertRedirect(route('threads.show', $thread) . '#post-2');

        $this->assertDatabaseHas('posts', [
            'thread_id' => $thread->id,
            'post_number' => 2,
            'name' => 'テスト太郎',
            'body' => 'テスト本文です',
        ]);
    }

    public function test_名前未入力時は名無しさんになる(): void
    {
        $thread = $this->createThreadWithPost();

        $this->post(route('posts.store', $thread), [
            'name' => '',
            'body' => '名前なし投稿',
        ]);

        $this->assertDatabaseHas('posts', [
            'thread_id' => $thread->id,
            'post_number' => 2,
            'name' => '名無しさん',
        ]);
    }

    public function test_本文が空だとバリデーションエラー(): void
    {
        $thread = $this->createThreadWithPost();

        $response = $this->post(route('posts.store', $thread), [
            'name' => '',
            'body' => '',
        ]);

        $response->assertSessionHasErrors('body');
        $this->assertDatabaseCount('posts', 1);
    }

    public function test_名前が100文字を超えるとバリデーションエラー(): void
    {
        $thread = $this->createThreadWithPost();

        $response = $this->post(route('posts.store', $thread), [
            'name' => str_repeat('あ', 101),
            'body' => '本文',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('posts', 1);
    }

    public function test_投稿後にスレッドのupdated_atが更新される(): void
    {
        $thread = $this->createThreadWithPost();
        $oldUpdatedAt = $thread->updated_at;

        $this->travel(1)->minutes();

        $this->post(route('posts.store', $thread), [
            'body' => '新しい投稿',
        ]);

        $thread->refresh();
        $this->assertTrue($thread->updated_at->gt($oldUpdatedAt));
    }

    public function test_post_numberが連番で増える(): void
    {
        $thread = $this->createThreadWithPost();

        $this->post(route('posts.store', $thread), ['body' => '2番目']);
        $this->post(route('posts.store', $thread), ['body' => '3番目']);

        $this->assertDatabaseHas('posts', ['thread_id' => $thread->id, 'post_number' => 2, 'body' => '2番目']);
        $this->assertDatabaseHas('posts', ['thread_id' => $thread->id, 'post_number' => 3, 'body' => '3番目']);
    }

    public function test_存在しないスレッドには投稿できない(): void
    {
        $response = $this->post('/threads/9999/posts', [
            'body' => '存在しないスレッド',
        ]);

        $response->assertNotFound();
    }
}

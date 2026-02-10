<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createThreadWithPost(string $title = 'テストスレッド'): Thread
    {
        $thread = Thread::create(['title' => $title]);
        $thread->posts()->create([
            'post_number' => 1,
            'name' => '名無しさん',
            'body' => '最初の投稿',
            'created_at' => now(),
        ]);

        return $thread;
    }

    // --- index ---

    public function test_スレッド一覧ページが表示される(): void
    {
        $response = $this->get(route('threads.index'));

        $response->assertOk();
    }

    public function test_スレッド一覧にスレッドが表示される(): void
    {
        $thread = $this->createThreadWithPost('表示テスト');

        $response = $this->get(route('threads.index'));

        $response->assertOk();
        $response->assertSeeText('表示テスト');
    }

    public function test_スレッド一覧はupdated_at降順で表示される(): void
    {
        $old = $this->createThreadWithPost('古いスレッド');
        $this->travel(1)->minutes();
        $new = $this->createThreadWithPost('新しいスレッド');

        $response = $this->get(route('threads.index'));

        $response->assertOk();
        $response->assertSeeTextInOrder(['新しいスレッド', '古いスレッド']);
    }

    public function test_ルートURLからスレッド一覧にリダイレクトされる(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('threads.index'));
    }

    public function test_キーワードで検索すると一致するスレッドが表示される(): void
    {
        $this->createThreadWithPost('Laravel入門');
        $this->createThreadWithPost('Vue.js入門');

        $response = $this->get(route('threads.index', ['keyword' => 'Laravel']));

        $response->assertOk();
        $response->assertSeeText('Laravel入門');
        $response->assertDontSeeText('Vue.js入門');
    }

    public function test_キーワードに一致しないスレッドは表示されない(): void
    {
        $this->createThreadWithPost('Laravel入門');

        $response = $this->get(route('threads.index', ['keyword' => 'Python']));

        $response->assertOk();
        $response->assertDontSeeText('Laravel入門');
    }

    // --- create ---

    public function test_スレッド作成フォームが表示される(): void
    {
        $response = $this->get(route('threads.create'));

        $response->assertOk();
    }

    // --- store ---

    public function test_スレッドを作成できる(): void
    {
        $response = $this->post(route('threads.store'), [
            'title' => '新しいスレッド',
            'name' => 'テスト太郎',
            'body' => '最初のレス',
        ]);

        $thread = Thread::first();
        $response->assertRedirect(route('threads.show', $thread));

        $this->assertDatabaseHas('threads', ['title' => '新しいスレッド']);
        $this->assertDatabaseHas('posts', [
            'thread_id' => $thread->id,
            'post_number' => 1,
            'name' => 'テスト太郎',
            'body' => '最初のレス',
        ]);
    }

    public function test_スレッド作成時に名前未入力で名無しさんになる(): void
    {
        $this->post(route('threads.store'), [
            'title' => 'テスト',
            'body' => '本文',
        ]);

        $this->assertDatabaseHas('posts', [
            'post_number' => 1,
            'name' => '名無しさん',
        ]);
    }

    public function test_タイトル未入力だとバリデーションエラー(): void
    {
        $response = $this->post(route('threads.store'), [
            'title' => '',
            'body' => '本文',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('threads', 0);
    }

    public function test_タイトルが255文字を超えるとバリデーションエラー(): void
    {
        $response = $this->post(route('threads.store'), [
            'title' => str_repeat('あ', 256),
            'body' => '本文',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('threads', 0);
    }

    public function test_本文未入力だとバリデーションエラー(): void
    {
        $response = $this->post(route('threads.store'), [
            'title' => 'テスト',
            'body' => '',
        ]);

        $response->assertSessionHasErrors('body');
        $this->assertDatabaseCount('threads', 0);
    }

    // --- show ---

    public function test_スレッド詳細ページが表示される(): void
    {
        $thread = $this->createThreadWithPost();

        $response = $this->get(route('threads.show', $thread));

        $response->assertOk();
        $response->assertSeeText('テストスレッド');
        $response->assertSeeText('最初の投稿');
    }

    public function test_存在しないスレッドは404(): void
    {
        $response = $this->get('/threads/9999');

        $response->assertNotFound();
    }
}

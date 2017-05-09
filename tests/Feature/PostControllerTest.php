<?php

namespace Tests\Feature;

use App\Models;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateAuthFail()
    {
        $response = $this->get(route('post.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testCreate()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('post.create'));
        $response->assertStatus(200);
        $response->assertSee('Create');
    }

    public function testStoreAuthFail()
    {
        $response = $this->post(route('post.store'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testStoreValidate()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->post(route('post.store'), [
            'tags' => '',
            'text' => '',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['tags', 'text']);
        $this->assertDatabaseMissing((new Models\Post())->getTable(), ['author_id' => $user->id]);
    }

    public function testStore()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->post(route('post.store'), [
            'tags' => ['tag1', 'tag2'],
            'text' => 'text for test post store method',
        ]);
        $response->assertStatus(302);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseHas((new Models\Post())->getTable(), [
            'author_id' => $user->id,
            'text' => 'text for test post store method',
        ]);
    }

    public function testEditAuthFail()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);

        $response = $this->get(route('post.edit', $post->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testEditAccessDenied()
    {
        $author = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $author->id]);

        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('post.edit', $post->id));
        $response->assertStatus(403);
    }

    public function testEdit()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->get(route('post.edit', $post->id));
        $response->assertStatus(200);
        $response->assertSee('Edit');
    }

    public function testUpdateAuthFail()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);

        $response = $this->post(route('post.update', $post->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testUpdateValidate()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->post(route('post.update', $post->id), [
            'tags' => '',
            'text' => '',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['tags', 'text']);
        $this->assertDatabaseHas((new Models\Post())->getTable(), [
            'author_id' => $user->id,
            'text' => $post->text,
        ]);
    }

    public function testUpdateAccessDenied()
    {
        $author = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $author->id]);

        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->post(route('post.update', $post->id), [
            'tags' => ['tag1', 'tag2'],
            'text' => 'text for test post update method',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseHas((new Models\Post())->getTable(), [
            'author_id' => $author->id,
            'text' => $post->text,
        ]);
        $this->assertDatabaseMissing((new Models\Post())->getTable(), [
            'author_id' => $user->id,
            'text' => 'text for test post update method',
        ]);
    }

    public function testUpdate()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->post(route('post.update', $post->id), [
            'tags' => ['tag1', 'tag2'],
            'text' => 'text for test post update method',
        ]);
        $response->assertStatus(302);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseHas((new Models\Post())->getTable(), [
            'author_id' => $user->id,
            'text' => 'text for test post update method',
        ]);
        $this->assertDatabaseMissing((new Models\Post())->getTable(), [
            'author_id' => $user->id,
            'text' => $post->text,
        ]);
    }

    public function testShow()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);

        $response = $this->get(route('post.show', $post->id));
        $response->assertStatus(200);
    }
}

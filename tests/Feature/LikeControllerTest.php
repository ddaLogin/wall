<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models;

class LikeControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testToggleAuthFail()
    {
        $response = $this->post(route('like.toggle'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testToggleValidate()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->post(route('like.toggle'));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['post_id', 'like']);
    }

    public function testToggleFromNullToLike()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->post(route('like.toggle'), [
            'post_id' => $post->id,
            'like' => true
        ]);

        $response->assertStatus(200);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseHas((new Models\Like())->getTable(), [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'like' => true,
        ]);
        $this->assertDatabaseMissing((new Models\Like())->getTable(), [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'like' => false,
        ]);
    }

    public function testToggleFromNullToDislike()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->post(route('like.toggle'), [
            'post_id' => $post->id,
            'like' => false
        ]);

        $response->assertStatus(200);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseHas((new Models\Like())->getTable(), [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'like' => false,
        ]);
        $this->assertDatabaseMissing((new Models\Like())->getTable(), [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'like' => true,
        ]);
    }

    public function testToggleFromNullToNull()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->post(route('like.toggle'), [
            'post_id' => $post->id,
            'like' => null
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['like']);
    }

    public function testToggleFromLikeToLike()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);
        $like = factory(Models\Like::class)->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'like' => true,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('like.toggle'), [
            'post_id' => $post->id,
            'like' => true
        ]);

        $response->assertStatus(200);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseMissing((new Models\Like())->getTable(), [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    public function testToggleFromLikeToDislike()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);
        $like = factory(Models\Like::class)->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'like' => true,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('like.toggle'), [
            'post_id' => $post->id,
            'like' => false
        ]);

        $response->assertStatus(200);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseHas((new Models\Like())->getTable(), [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'like' => false
        ]);
        $this->assertDatabaseMissing((new Models\Like())->getTable(), [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'like' => true
        ]);
    }

    public function testToggleFromDislikeToDislike()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);
        $like = factory(Models\Like::class)->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'like' => false,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('like.toggle'), [
            'post_id' => $post->id,
            'like' => false
        ]);

        $response->assertStatus(200);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseMissing((new Models\Like())->getTable(), [
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
    }

    public function testToggleFromDislikeToLike()
    {
        $user = factory(Models\User::class)->create();
        $post = factory(Models\Post::class)->create(['author_id' => $user->id]);
        $like = factory(Models\Like::class)->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'like' => false,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('like.toggle'), [
            'post_id' => $post->id,
            'like' => true
        ]);

        $response->assertStatus(200);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseHas((new Models\Like())->getTable(), [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'like' => true
        ]);
        $this->assertDatabaseMissing((new Models\Like())->getTable(), [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'like' => false
        ]);
    }
}

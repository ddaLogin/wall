<?php

namespace Tests\Feature;

use App\Models;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
    }

    public function testLogin()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    public function testSignup()
    {
        $response = $this->get(route('signup'));
        $response->assertStatus(200);
    }

    public function testRegistrationValidate()
    {
        $response = $this->post(route('registration'), [
            'nickname' => '',
            'email' => 'test',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['nickname', 'email', 'password']);
        $this->assertDatabaseMissing((new Models\User())->getTable(), ['email' => 'test']);
    }

    public function testRegistration()
    {
        $user = factory(Models\User::class)->make();

        $response = $this->post(route('registration'), [
            'nickname' => $user->nickname,
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $response->assertSessionMissing(['error']);
        $this->assertDatabaseHas((new Models\User())->getTable(), [
            'nickname' => $user->nickname,
            'email' => $user->email
        ]);
    }

    public function testAuthenticateValidate()
    {
        $response = $this->post(route('authenticate'), [
            'email' => 'email',
            'password' => 'password'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['authenticate']);
    }

    public function testAuthenticate()
    {
        $user = factory(Models\User::class)->create(['password' => bcrypt('secret')]);

        $response = $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionMissing(['errors']);
    }

    public function testSearchFail()
    {
        $response = $this->get(route('search', ['q' => 'some text for test search']));

        $response->assertStatus(200);
        $response->assertSee('Posts not found');
        $response->assertSee('Users not found');
    }

    public function testSearchPost()
    {
        $q = 'text for search from test';
        $user = factory(Models\User::class)->create();
        factory(Models\Post::class)->create([
            'author_id' => $user->id,
            'text' => $q
        ]);

        $response = $this->get(route('search', ['q' => $q]));

        $response->assertStatus(200);
        $response->assertSee($q);
        $response->assertDontSee('Posts not found');
    }

    public function testSearchUser()
    {
        $user = factory(Models\User::class)->create();
        $q = $user->email;
        $response = $this->get(route('search', ['q' => $q]));

        $response->assertStatus(200);
        $response->assertSee($q);
        $response->assertDontSee('Users not found');
    }
}

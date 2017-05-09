<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testWallNotFound()
    {
        $user = factory(Models\User::class)->make();

        $response = $this->get(route('user.wall', $user->nickname));

        $response->assertStatus(404);
    }

    public function testWall()
    {
        $user = factory(Models\User::class)->create();

        $response = $this->get(route('user.wall', $user->nickname));

        $response->assertStatus(200);
        $response->assertSee($user->nickname);
        $response->assertSee($user->email);
    }

    public function testSubscriptionsAuthFail()
    {
        $user = factory(Models\User::class)->create();

        $response = $this->get(route('user.subscriptions', $user->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testSubscriptionsNotFound()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('user.subscriptions', 0));
        $response->assertStatus(404);
    }

    public function testSubscriptions()
    {
        $first = factory(Models\User::class)->create();
        $second = factory(Models\User::class)->create();
        factory(Models\Subscription::class)->create(['user_id' => $first->id, 'target_id' => $second->id,]);
        factory(Models\Subscription::class)->create(['user_id' => $second->id, 'target_id' => $first->id,]);

        $this->actingAs($first);
        $response = $this->get(route('user.subscriptions', $first->id));
        $response->assertStatus(200);
        $response->assertSee($second->nickname);
    }

    public function testSettingsAuthFail()
    {
        factory(Models\User::class)->create();

        $response = $this->get(route('user.settings'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testSettings()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('user.settings'));
        $response->assertStatus(200);
    }

    public function testFeedAuthFail()
    {
        $response = $this->get(route('user.feed'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testFeed()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('user.feed'));
        $response->assertStatus(200);
    }

    public function testNotificationsAuthFail()
    {
        $response = $this->get(route('user.notifications'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testNotifications()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('user.notifications'));
        $response->assertStatus(200);
    }

    public function testChangeMailAuthFail()
    {
        $response = $this->post(route('user.settings.change.mail'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testChangeMailCommonValidate()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->post(route('user.settings.change.mail'));
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function testChangeMailPasswordValidate()
    {
        $user = factory(Models\User::class)->create(['password' => bcrypt('secret')]);

        $this->actingAs($user);
        $response = $this->post(route('user.settings.change.mail'),[
            'email' => 'example@gmail.com',
            'password' => 'wrongPassword'
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }

    public function testChangeMail()
    {
        $user = factory(Models\User::class)->create(['password' => bcrypt('secret')]);

        $this->actingAs($user);
        $response = $this->post(route('user.settings.change.mail'),[
            'email' => 'example@gmail.com',
            'password' => 'secret'
        ]);

        $response->assertStatus(302);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseHas((new Models\User())->getTable(), [
            'email' => 'example@gmail.com',
            'nickname' => $user->nickname
        ]);
        $this->assertDatabaseMissing((new Models\User())->getTable(), [
            'email' => $user->email,
            'nickname' => $user->nickname
        ]);
    }

    public function testChangePasswordAuthFail()
    {
        $response = $this->post(route('user.settings.change.password'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testChangePasswordCommonValidate()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->post(route('user.settings.change.password'));
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['currentPassword', 'newPassword']);
    }

    public function testChangePasswordPasswordValidate()
    {
        $user = factory(Models\User::class)->create(['password' => bcrypt('secret')]);

        $this->actingAs($user);
        $response = $this->post(route('user.settings.change.password'),[
            'currentPassword' => 'wrongPassword',
            'newPassword' => 'newPassword',
            'newPassword_confirmation' => 'newPassword'
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['currentPassword']);
    }

    public function testChangePassword()
    {
        $user = factory(Models\User::class)->create(['password' => bcrypt('secret')]);

        $this->actingAs($user);
        $response = $this->post(route('user.settings.change.password'),[
            'currentPassword' => 'secret',
            'newPassword' => 'newPassword',
            'newPassword_confirmation' => 'newPassword'
        ]);

        $response->assertStatus(302);
        $response->assertSessionMissing(['errors']);
    }
}

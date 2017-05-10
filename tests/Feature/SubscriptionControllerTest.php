<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models;

class SubscriptionControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testToggleAuthFail()
    {
        $response = $this->post(route('subscription.toggle'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testToggleValidate()
    {
        $first = factory(Models\User::class)->create();

        $this->actingAs($first);
        $response = $this->post(route('subscription.toggle'));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['target_id']);
    }

    public function testToggleAccessDenied()
    {
        $first = factory(Models\User::class)->create();

        $this->actingAs($first);
        $response = $this->post(route('subscription.toggle'), [
            'target_id' => $first->id
        ]);

        $response->assertStatus(403);
    }

    public function testToggleOn()
    {
        $first = factory(Models\User::class)->create();
        $second = factory(Models\User::class)->create();

        $this->actingAs($first);
        $response = $this->post(route('subscription.toggle'), [
            'target_id' => $second->id
        ]);

        $response->assertStatus(200);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseHas((new Models\Subscription())->getTable(), [
            'user_id' => $first->id,
            'target_id' => $second->id
        ]);
    }

    public function testToggleOff()
    {
        $first = factory(Models\User::class)->create();
        $second = factory(Models\User::class)->create();
        factory(Models\Subscription::class)->create([
            'user_id' => $first->id,
            'target_id' => $second->id
        ]);

        $this->actingAs($first);
        $response = $this->post(route('subscription.toggle'), [
            'target_id' => $second->id
        ]);

        $response->assertStatus(200);
        $response->assertSessionMissing(['errors']);
        $this->assertDatabaseMissing((new Models\Subscription())->getTable(), [
            'user_id' => $first->id,
            'target_id' => $second->id
        ]);
    }
}

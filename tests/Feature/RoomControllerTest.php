<?php

namespace Tests\Feature;

use App\Models;
use App\Repositories\RoomRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateAuthFail()
    {
        $response = $this->get(route('room.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testCreate()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('room.create'));
        $response->assertStatus(302);
    }

    public function testJoinAuthFail()
    {
        $room = factory(Models\Room::class)->create();
        $response = $this->get(route('room.join', $room));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testJoinNotFound()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('room.join', 'fake_room_link'));
        $response->assertStatus(404);
        $response->assertSee(__('content.errors.404.text', [
            'url' => route('room.join', 'fake_room_link')
        ]));
    }

    public function testJoin()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $room = factory(Models\Room::class)->create();
        $response = $this->get(route('room.join', $room));
        $response->assertStatus(200);
    }

    public function testInviteAuthFail()
    {
        $user = factory(Models\User::class)->create();
        $room = factory(Models\Room::class)->create();
        $response = $this->get(route('room.invite', [$room, $user]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testInviteNotFoundRoom()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('room.invite', ['fake_room_link', $user]));
        $response->assertStatus(404);
        $response->assertSee(__('content.errors.404.text', [
            'url' => route('room.invite', ['fake_room_link', $user])
        ]));
    }

    public function testInviteNotFoundUser()
    {
        $user = factory(Models\User::class)->create();
        $room = factory(Models\Room::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('room.invite', [$room, '-1']));
        $response->assertStatus(404);
        $response->assertSee(__('content.errors.404.text', [
            'url' => route('room.invite', [$room, '-1'])
        ]));
    }

    public function testInvite()
    {
        $user = factory(Models\User::class)->create();
        $room = factory(Models\Room::class)->create();

        $this->actingAs($user);
        $response = $this->get(route('room.invite', [$room, $user]));
        $response->assertStatus(200);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'type' => 'App\Notifications\InviteToRoom',
        ]);
    }
}

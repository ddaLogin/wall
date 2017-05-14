<?php

namespace Tests\Feature;

use App\Models;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testAvatarAuthFail()
    {
        $response = $this->post(route('file.avatar'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testAvatarValidate()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->post(route('file.avatar'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['photo', 'w', 'h', 'x' , 'y']);
    }

    public function testAvatar()
    {
        $user = factory(Models\User::class)->create();

        $this->actingAs($user);
        $response = $this->post(route('file.avatar'), [
            'photo' => UploadedFile::fake()->image('avatar.jpg'),
            'w' => '100',
            'h' => '100',
            'x' => '0',
            'y' => '0',
        ]);
        $response->assertStatus(200);
        $response->assertSessionMissing('errors');
        $this->assertDatabaseMissing((new Models\User())->getTable(), [
            'id' => $user->id,
            'photo' => null,
            'photo_mini' => null
        ]);

        $data = $response->getData();
        $fileName = explode('/', $data->url);
        $fileName = $fileName[count($fileName) - 1];
        $fileNameParts = explode('.', $fileName);
        $fileNameMini = $fileNameParts[0]."_mini.".$fileNameParts[1];

        Storage::disk('public')->assertExists('/photos/'.$fileName);
        Storage::disk('public')->assertExists('/photos/'.$fileNameMini);

        Storage::disk('public')->delete('/photos/'.$fileName);
        Storage::disk('public')->delete('/photos/'.$fileNameMini);
    }
}

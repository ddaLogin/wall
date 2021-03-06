<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Models\User::all() as $user){
            factory(\App\Models\Post::class, rand(15,100))->create(['author_id' => $user->id]);
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param \Faker\Generator $faker
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
        $users = factory(\App\Models\User::class, 30)->make();
        $users[] = factory(\App\Models\User::class)->make([
            'nickname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin'
        ]);

        $users->each(function ($item) use($faker){
            $photo = $faker->image(storage_path('app/public/photos'), 500, 500, null, false);
            $item->photo = ($photo)?'photos/'.$photo:null;
            $item->photo_mini = ($photo)?'photos/'.$photo:null;
            $item->save();
        });
    }
}

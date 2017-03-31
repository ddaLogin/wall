<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 30)->create();
        factory(\App\Models\User::class)->create([
            'nickname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin')
        ]);
    }
}

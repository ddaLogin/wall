<?php

use Illuminate\Database\Seeder;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $max = \App\Models\User::all()->count();
        foreach (\App\Models\User::all() as $user){
            for ($i=0; $i<rand(0,6); $i++){
                $target_id = rand(1, $max);
                if ($target_id != $user->id){
                    factory(\App\Models\Subscription::class)->create(['user_id' => $user->id, 'target_id' => $target_id]);
                }
            }
        }
    }
}

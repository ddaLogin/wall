<?php

namespace App\Providers;

use App\Interfaces;
use App\Models\Post;
use App\Models\User;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Post::observe(PostObserver::class);
        User::observe(UserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Interfaces\LikeRepository::class, config('repositories.LikeRepository'));
        $this->app->bind(Interfaces\PostRepository::class, config('repositories.PostRepository'));
        $this->app->bind(Interfaces\RoomRepository::class, config('repositories.RoomRepository'));
        $this->app->bind(Interfaces\SubscriptionRepository::class, config('repositories.SubscriptionRepository'));
        $this->app->bind(Interfaces\UserRepository::class, config('repositories.UserRepository'));
    }
}

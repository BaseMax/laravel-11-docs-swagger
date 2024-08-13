<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\Post;
use App\Models\Comment;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use App\Events\UserRegistered;
use App\Listeners\SendWelcomeEmailListener;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::define("isAdmin", function ($user): bool {
            return $user->role?->name === 'admin';
        });

        // policies
        Gate::policy(Post::class, PostPolicy::class);

        Gate::policy(Category::class, CategoryPolicy::class);

        Gate::policy(Comment::class, CommentPolicy::class);

        // events
        Event::listen(UserRegistered::class, SendWelcomeEmailListener::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}

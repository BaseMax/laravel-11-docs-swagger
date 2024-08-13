<?php

namespace Tests\Feature\Models\Relationships;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use App\Models\Post;
use App\Models\User;

class PostLikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_post_like(): void
    {
        $user = User::factory()->create();

        $post = Post::factory()->for(User::factory()->for(Role::factory()), "author")->create();


        $post->likes()->attach($user);

        $this->assertDatabaseHas("post_likes", [
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }
}

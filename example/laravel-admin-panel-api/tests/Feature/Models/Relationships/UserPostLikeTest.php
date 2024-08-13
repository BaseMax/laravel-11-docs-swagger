<?php

namespace Tests\Feature\Models\Relationships;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserPostLikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_be_created_for_user(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $user = User::factory()->create();
        $post = Post::factory()->for(User::factory()->create(), "author")->create();

        $user->like()->attach($post->id);

        $this->assertDatabaseHas("post_likes", [
            "user_id"=> $user->id,
            "post_id"=> $post->id
        ]);
    }

    public function test_can_be_deleted_for_user(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $user = User::factory()->create();
        $post = Post::factory()->for(User::factory()->create(), "author")->create();

        $user->like()->attach($post->id);

        $this->assertDatabaseHas("post_likes", [
            "user_id"=> $user->id,
            "post_id"=> $post->id
        ]);

        $user->like()->detach($post->id);

        $this->assertDatabaseMissing("post_likes", [
            "user_id"=> $user->id,
            "post_id"=> $post->id
        ]);
    }
}

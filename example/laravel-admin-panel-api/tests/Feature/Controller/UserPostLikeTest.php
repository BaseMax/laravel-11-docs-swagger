<?php

namespace Tests\Feature\Controller;

use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Post;

class UserPostLikeTest extends TestCase
{
    public function test_user_like_store_method(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->for(User::factory()->for(Role::factory()), "author")->create();

        $reponse = $this->postJson(route("api.users.likes.like", $post->id));

        $reponse->assertCreated()->assertJson(
            fn(AssertableJson $json) =>
            $json->whereAllType([
                "success" => "boolean",
                "message" => "string"
            ])
        );
    }

    public function test_user_dislike_post(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $post = Post::factory()->for(User::factory()->for(Role::factory()), "author")->create();

        $reponse = $this->postJson(route("api.users.likes.dislike", $post->id));

        $reponse->assertNoContent();
    }

    public function test_user_liked_post_count(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $post = Post::factory()->for(User::factory()->for(Role::factory()), "author")->create();

        $reponse = $this->getJson(route("api.users.likes.count"));

        $reponse->assertOk()->assertJson(
            fn(AssertableJson $json) =>
            $json->whereAllType([
                "success" => "boolean",
                "message" => "string",
                "data" => "integer"
            ])
        );
    }
}

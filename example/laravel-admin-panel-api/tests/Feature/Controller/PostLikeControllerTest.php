<?php

namespace Tests\Feature\Controller;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\Post;
use App\Models\User;

class PostLikeControllerTest extends TestCase
{
    public function test_post_like_method(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->for(User::factory(), "author")->create();

        $response = $this->postJson(route("api.posts.likes.store", $post->id));

        $response->assertStatus(201)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->whereAllType([
                    "success" => "boolean",
                    "message" => "string"
                ])
            );
    }
    public function test_post_dislike_method(): void
    {

        Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->for(User::factory(), "author")->create();

        $response = $this->postJson(route("api.posts.dislike.store", $post->id));

        $response->assertStatus(204);
    }

    public function test_post_liked_count_method(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->for(User::factory(), "author")->create();

        $response = $this->getJson(route("api.posts.likes.count",$post->id));

        $response->assertStatus(200)
        ->assertJson(
            fn(AssertableJson $json) =>
            $json->whereAllType([
                "success" => "boolean",
                "message" => "string",
                "data" => "integer",
            ])
        );
    }
}

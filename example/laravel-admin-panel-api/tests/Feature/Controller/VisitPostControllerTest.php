<?php

namespace Tests\Feature\Controller;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Post;

class VisitPostControllerTest extends TestCase
{

    public function test_store_visit_post_method(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->create();

        $response = $this->postJson(route("api.posts.visits.store", $post->id));

        $response->assertStatus(201);
    }

    public function test_post_visits_count(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->create();

        $reeesponse = $this->getJson(route("api.posts.visits.count", $post->id));

        $reeesponse->assertOk()->assertJson(fn (AssertableJson $json) =>
        $json->whereAllType([
            "success" => "boolean",
            "message" => "string",
            "data" => "integer"
        ])
    );
    }
}

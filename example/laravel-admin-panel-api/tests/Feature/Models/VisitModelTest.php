<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;

class VisitModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_create_a_visit(): void
    {
        $post = Post::factory()->create();

        $data = [
            "ip_address" => \Hash::make($this->faker->ipv4()),
            "user_agent" => $this->faker->userAgent(),
            "visited_at" => $this->faker->date(),
        ];

        $post->visits()->create($data);

        $this->assertDatabaseHas("visits", $data);
    }
}

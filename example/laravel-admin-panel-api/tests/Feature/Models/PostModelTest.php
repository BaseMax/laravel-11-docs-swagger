<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Post;

class PostModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void{
        parent::setUp();
        Role::factory()->create();
        Role::factory()->create();
    }

    public function test_can_create_a_post(): void
    {
        $post = Post::factory()->for(User::factory(), "author")->create();

        $this->assertDatabaseHas("posts", $post->first()->toArray());
    }
    public function test_can_update_a_post(): void
    {
        Role::factory()->create();
        $post = Post::factory()->for(User::factory()->for(Role::factory()), "author")->create();

        $post_data = [
            "title" => $this->faker->title,
            "summary" => $this->faker->paragraph,
            "content" => $this->faker->text,
            "cover" => $this->faker->imageUrl,
        ];

        $this->assertDatabaseMissing("posts", $post_data);

        $post->update($post_data);

        $this->assertDatabaseHas("posts", $post_data);
    }

    public function test_can_delete_a_post(): void
    {
        Role::factory()->create();
        $post = Post::factory()->for(User::factory()->for(Role::factory()),"author")->create();

        $this->assertDatabaseHas("posts", $post->toArray());

        $post->delete();

        $this->assertDatabaseMissing("posts", $post->toArray());
    }
}

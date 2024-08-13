<?php

namespace Tests\Feature\Controller;

use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\Role;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public $user;
    public function setUp(): void
    {
        parent::setUp();
        $role = Role::factory()->create(['name' => 'admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);
    }

    public function test_index_category_method(): void
    {
        $this->actingAs($this->user);

        $response = $this->getJson(route('api.comments.index'));

        $response->assertStatus(200)->assertJson(fn(AssertableJson $json) =>
            $json->whereType("data", "array")->whereAllType([
                "links" => "array",
                "meta" => "array"
            ])
        );
    }
    public function test_show_category_method(): void
    {
        $this->actingAs($this->user);

        $comment = Comment::factory()->for(Post::factory())->create();

        $response = $this->getJson(route('api.comments.show', $comment->id));

        $response->assertStatus(200)->assertJson(fn(AssertableJson $json) =>
            $json->whereAllType([
                "id" => "integer",
                "created_at" => "string",
                "updated_at" => "string",
                "title" => "string",
                "description" => "string",
                "user_id" => "integer",
                "post_id" => "integer",
            ])
        );
    }

    public function test_can_create_category(): void
    {
        Sanctum::actingAs($this->user);
        $post = Post::factory()->create();

        $data = [
            'title' => 'Test Comment',
            'description' => 'New Comment for test api',
            'post_id'=> $post->id,
            'user_id'=> $this->user->id,
        ];

        $response = $this->postJson(route('api.comments.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('comments', $data);
    }
    public function test_can_update_category(): void
    {
        Sanctum::actingAs($this->user);

        $comment = Comment::factory()->for(User::factory())->create(['title' => 'Test Comment']);

        $data = [
            'title' => 'New Test Comment Title',
            'description' => $comment->description,
            'user_id' => $comment->user_id,
            'post_id' => $comment->post_id,
        ];

        $response = $this->actingAs($this->user)->putJson(route('api.comments.update', $comment->id), $data);

        $response->assertStatus(204);

        $this->assertDatabaseHas('comments', $data);
    }
    public function test_can_delete_category(): void
    {
        Sanctum::actingAs($this->user);

        $comment = Comment::factory()->for(User::factory())->create(['title' => 'Test Category']);

        $response = $this->actingAs($this->user)->deleteJson(route('api.comments.destroy', $comment->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('comments', $comment->toArray());
    }
}

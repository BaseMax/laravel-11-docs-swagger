<?php

namespace Tests\Feature\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Role;
use App\Models\Post;
use App\Models\Category;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public User $user;
    public function setUp(): void
    {
        parent::setUp();
        $role = Role::factory()->create(['name' => 'admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);
    }

    public function test_index_post_method(): void
    {
        $this->actingAs($this->user);

        $response = $this->getJson(route('api.posts.index'));

        $response->assertStatus(200)->assertJson(
            fn(AssertableJson $json) =>
            $json->whereType("data", "array")->whereAllType([
                "links" => "array",
                "meta" => "array"
            ])
        );
    }
    public function test_show_post_method(): void
    {
        $this->actingAs($this->user);

        $post = Post::factory()->create();

        $response = $this->getJson(route('api.posts.show', $post->id));

        $response->assertStatus(200)->assertJson(
            fn(AssertableJson $json) =>
            $json->whereAllType([
                "id" => "integer",
                "created_at" => "string",
                "updated_at" => "string",
                "title" => "string",
                "summary" => "string",
                "cover" => "string",
                "content" => "string",
                "author_id" => "integer"
            ])
        );
    }

    public function test_can_create_post(): void
    {
        Sanctum::actingAs($this->user);
        $cover = UploadedFile::fake()->image('test_image.png');
        $data = [
            'title' => $this->faker->text(25),
            'summary' => "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim ven",
            'content' => $this->faker->paragraph(150),
            'cover' => $cover,
            'author_id' => $this->user->id,
            'category_id' => Category::factory()->create()->id,
        ];

        $response = $this->postJson(route('api.posts.store'), $data);

        Storage::assertExists("covers/" . $cover->hashName());

        $response->assertStatus(201);

        $this->assertDatabaseHas('posts', collect($data)->except(['cover'])->toArray());
    }
    public function test_can_update_post(): void
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs($this->user);

        $post = Post::factory()->for(User::factory(), 'author')->create(['title' => 'Test Category']);

        $data = [
            'title' => 'New Test Category',
            'summary' => "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim ven",
            'content' => $post->content,
            'cover' => $post->cover,
            'author_id' => $post->author_id,
            'category_id' => $post->category_id,
        ];

        $response = $this->actingAs($this->user)->putJson(route('api.posts.update', $post->id), $data);

        $response->assertStatus(204);

        $this->assertDatabaseHas('posts', $data);
    }
    public function test_can_delete_category(): void
    {
        Sanctum::actingAs($this->user);

        $category = Category::factory()->for(User::factory())->create(['name' => 'Test Category']);

        $response = $this->actingAs($this->user)->deleteJson(route('api.categories.destroy', $category->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', $category->toArray());
    }
    public function test_store_method_requires_authentication_for_unauthenticated_user(): void
    {
        $data = [
            'name' => 'Test Category',
        ];

        $response = $this->actingAs(User::factory()->for(Role::factory())->create())->postJson(route("api.categories.store"), $data);

        $response->assertStatus(403);
    }
}

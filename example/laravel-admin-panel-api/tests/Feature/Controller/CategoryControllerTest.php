<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;

class CategoryControllerTest extends TestCase
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

        $response = $this->getJson(route('api.categories.index'));

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

        $category = Category::factory()->create();

        $response = $this->getJson(route('api.categories.show', $category->id));

        $response->assertStatus(200)->assertJson(fn(AssertableJson $json) =>
            $json->whereAllType([
                "id" => "integer",
                "created_at" => "string",
                "updated_at" => "string",
                "name" => "string",
                "user_id" => "integer"
            ])
        );
    }

    public function test_can_create_category(): void
    {
        Sanctum::actingAs($this->user);
        $data = [
            'name' => 'Test Category',
        ];

        $response = $this->postJson(route('api.categories.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('categories', $data);
    }
    public function test_can_update_category(): void
    {
        Sanctum::actingAs($this->user);

        $category = Category::factory()->for(User::factory())->create(['name' => 'Test Category']);

        $data = [
            'name' => 'New Test Category',
        ];

        $response = $this->actingAs($this->user)->putJson(route('api.categories.update', $category->id), $data);

        $response->assertStatus(204);

        $this->assertDatabaseHas('categories', $data);
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

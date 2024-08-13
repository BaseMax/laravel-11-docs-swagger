<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_category(): void
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas("categories", $category->toArray());

        $this->assertInstanceOf(Category::class, $category->first());
    }
    public function test_can_update_a_category(): void
    {
        $category = Category::factory()->for(User::factory())->create();

        $new_category = [
            "name" => 'new Category name',
        ];

        $this->assertDatabaseHas("categories", $category->toArray());

        $category->first()->update($new_category);

        $this->assertDatabaseHas("categories", $new_category);
    }

    public function test_can_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $deleted_category = $category->toArray();

        $category->delete();
        $this->assertDatabaseMissing("categories", $deleted_category);
    }
}

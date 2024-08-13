<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    public $user;
    public function setUp(): void
    {
        parent::setUp();

        Role::factory()->create(['name' => 'admin']);
        Role::factory()->create(['name' => 'user']);

        $this->user = User::factory()->create(['role_id' => 1]);
    }

    public function test_create_user_store_method(): void
    {
        $this->actingAs($this->user);

        $fake_password = "user12345ABCD";

        $data = [
            "name" => "testUser123",
            "email" => $this->faker->email,
            "password" => $fake_password,
            "password_confirmation" => $fake_password
        ];

        $this->postJson(route("api.users.store"), $data)->assertCreated()->assertJson([
            "success" => true,
            "message" => "User created successfully",
            "data" => []
        ]);
    }

    public function test_create_user_store_method_with_validation(): void
    {
        $this->actingAs($this->user);

        $this->postJson(route("api.users.store"), [])->assertStatus(422)->assertJson([
            "message" => "The name field is required. (and 2 more errors)",
            "errors" => [
                "name" => ["The name field is required."],
                "email" => ["The email field is required."],
                "password" => ["The password field is required."],
            ]
        ]);
    }

    public function test_update_user_method(): void
    {
        $user = User::factory()->create();

        $new_user = $user->toArray();

        $new_user['name'] = "testNewUser123";

        $this->actingAs($user)->putJson(route("api.users.update", $user->id), $new_user)->assertStatus(204);
    }

    public function test_delete_user_method(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->deleteJson(route("api.users.destroy", $user->id))->assertStatus(204);
    }
}

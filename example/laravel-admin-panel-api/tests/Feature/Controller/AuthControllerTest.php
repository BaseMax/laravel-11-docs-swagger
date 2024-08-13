<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_register_user_success(): void
    {
        Role::factory(2)->create();

        $user = [
            "name" => "user001",
            "email" => "user001@email.com",
            "password" => "user001password",
            "password_confirmation" => "user001password",
        ];

        $response = $this->postJson(route("api.register"), $user);

        $response->assertStatus(200)->assertJson([
            "success" => true,
            "message" => "User registered successfully!",
            "data" => []
        ]);
    }

    public function test_register_user_validation_failure(): void
    {
        $data = [
            "name" => "u",
            "email" => "al",
            "password" => ""
        ];

        $response = $this->postJson(route("api.register"), $data);

        $response->assertStatus(422)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->whereType("message", "string")
                    ->whereType("errors", "array")->whereAllType([
                            "errors.name" => "array",
                            "errors.email" => "array",
                            "errors.password" => "array"
                        ])
            );
    }

    public function test_login_success(): void
    {
        $user = User::factory()->create([
            "password" => \Hash::make("user001password"),
        ]);

        $user->createToken($user->name)->plainTextToken;

        $response = $this->actingAs($user)
            ->postJson(route("api.login"), $user->toArray() + ["password" => "user001password"])
            ->assertStatus(200);
    }

    public function test_login_failed_with_validation(): void
    {
        $data = [
            "email" => "al",
            "password" => ""
        ];

        $response = $this->postJson(route("api.login"), $data);

        $response->assertStatus(422)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->whereType("message", "string")
                    ->whereType("errors", "array")->whereAllType([
                            "errors.email" => "array",
                            "errors.password" => "array"
                        ])
            );
    }

    public function test_logout_success(): void
    {
        $user = User::factory()->create();
        $user->createToken($user->name)->plainTextToken;

        $response = $this->actingAs($user)
        ->postJson(route("api.logout"), $user->toArray())
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) =>
        $json->whereType("success", "boolean")->whereType("message", "string")
    );
    }
}

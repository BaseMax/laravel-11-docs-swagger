<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_user(): void
    {
        $user = User::factory()->for(Role::factory())->create();

        $this->assertDatabaseHas("users", $user->toArray());
    }
    public function test_can_put_update_a_user(): void
    {
        $user = User::factory()->for(Role::factory())->create();
        $user_data = [
            "name" => "user_test",
            "email" => "test@email.com",
            "password" => \Hash::make("12345678")
        ];

        $this->assertDatabaseMissing("users", $user_data);

        $user->update($user_data);

        $this->assertDatabaseHas("users", $user_data);
    }
    public function test_can_delete_a_user(): void
    {
        $user = User::factory()->for(Role::factory())->create();

        $this->assertDatabaseHas("users", $user->toArray());

        $user->delete();

        $this->assertDatabaseMissing("users", $user->toArray());
    }
}

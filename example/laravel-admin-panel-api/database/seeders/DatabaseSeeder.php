<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::query()->create(['name' => 'admin']);
        Role::query()->create(['name' => 'manual']);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'role_id' => Role::query()->where('name', 'admin')->first()->id,
            'password' => bcrypt('admin12345678'),
        ]);

        User::factory()->create([
            'name' => 'user',
            'email' => 'user@user.com',
            'role_id' => Role::query()->where('name', 'manual')->first()->id,
            'password' => bcrypt('user12345678'),
        ]);
    }
}

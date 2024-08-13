<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->text(15),
            'summary' => $this->faker->text(170),
            'content' => $this->faker->paragraph(350),
            'cover' => UploadedFile::fake()->image("test_image.png"),

            'author_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }
}

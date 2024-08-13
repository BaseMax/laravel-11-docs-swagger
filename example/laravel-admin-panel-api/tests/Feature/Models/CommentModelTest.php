<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;

class CommentModelTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_create_a_comment(): void
    {
        $comment = Comment::factory()->for(User::factory())->create();

        $this->assertDatabaseHas("comments", $comment->toArray());

        $this->assertInstanceOf(Comment::class, $comment);
    }

    public function test_can_update_a_comment(): void
    {
        $comment = Comment::factory()->for(User::factory())->create();

        $new_comment = [
            'title' => 'new comment title',
            'description' => 'new comment description for test update comment',
        ];

        $this->assertDatabaseHas("comments", $comment->toArray());

        $comment->update($new_comment);

        $this->assertDatabaseHas("comments", $new_comment);
    }

    public function test_can_delete_a_comment(): void
    {
        $comment = Comment::factory()->for(User::factory())->create();

        $this->assertDatabaseHas("comments", $comment->toArray());

        $comment->delete();

        $this->assertDatabaseMissing("comments", $comment->toArray());
    }
}

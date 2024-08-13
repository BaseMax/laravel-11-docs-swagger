<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Comment;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(User $user, Comment $comment): bool
    {
        return $this->isAdminOrUserPost($user, $comment);
    }

    public function create(User $user): bool
    {
        return $user->cannot("isAdmin");
    }

    public function update(User $user, Comment $comment): bool
    {
        return $this->isAdminOrUserPost($user, $comment);
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $this->isAdminOrUserPost($user, $comment);
    }

    public function restore(User $user, Comment $comment): bool
    {
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
    }
    public function isAdminOrUserPost(User $user, Comment $comment): bool
    {
        return $user->can("isAdmin") || $user->id === $comment->user_id;
    }
}

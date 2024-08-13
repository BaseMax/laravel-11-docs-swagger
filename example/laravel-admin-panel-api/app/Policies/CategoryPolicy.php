<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(User $user, Category $category): bool
    {
        return $this->isAdmin($user, $category);
    }

    public function create(User $user): bool
    {
        return $user->can("isAdmin");
    }

    public function update(User $user, Category $category): bool
    {
        // dd($user->role->name);
        return $this->isAdmin($user, $category);
    }

    public function delete(User $user, Category $category): bool
    {
        return $this->isAdmin($user, $category);
    }

    public function restore(User $user, Category $category): bool
    {
    }

    public function forceDelete(User $user, Category $category): bool
    {
    }

    public function isAdmin(User $user, Category $category): bool
    {
        return $user->can("isAdmin") || $user->is === $category->user_id;
    }
}

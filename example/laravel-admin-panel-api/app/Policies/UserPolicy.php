<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('isAdmin');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('isAdmin') || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('isAdmin') || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('isAdmin') || $user->id === $model->id;
    }

    public function restore(User $user, User $model): bool
    {
    }

    public function forceDelete(User $user, User $model): bool
    {
    }
}

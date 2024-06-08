<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_USER);
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_USER);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_USER);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo(Permission::EDIT_USER);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_USER);
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}

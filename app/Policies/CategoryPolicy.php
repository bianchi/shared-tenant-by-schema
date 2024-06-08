<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\Permission;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_CATEGORY);
    }

    public function view(User $user, Category $model): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_CATEGORY);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_CATEGORY);
    }

    public function update(User $user, Category $model): bool
    {
        return $user->hasPermissionTo(Permission::EDIT_CATEGORY);
    }

    public function delete(User $user, Category $model): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_CATEGORY);
    }

    public function restore(User $user, Category $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, Category $model): bool
    {
        return false;
    }
}

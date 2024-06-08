<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_ROLE);
    }

    public function view(User $user, Role $model): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_ROLE);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_ROLE);
    }

    public function update(User $user, Role $model): bool
    {
        return $user->hasPermissionTo(Permission::EDIT_ROLE);
    }

    public function delete(User $user, Role $model): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_ROLE);
    }

    public function restore(User $user, Role $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, Role $model): bool
    {
        return false;
    }
}

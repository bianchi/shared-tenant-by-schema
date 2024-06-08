<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Permission $model): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Permission $model): bool
    {
        return false;
    }

    public function delete(User $user, Permission $model): bool
    {
        return false;
    }

    public function restore(User $user, Permission $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, Permission $model): bool
    {
        return false;
    }
}

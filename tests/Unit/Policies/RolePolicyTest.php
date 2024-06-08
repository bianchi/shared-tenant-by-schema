<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Policies\RolePolicy;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('allows view any', function () {
    $this->assertFalse((new RolePolicy())->viewAny($this->user));

    $this->user->givePermissionTo(Permission::VIEW_ROLE);

    $this->assertTrue((new RolePolicy())->viewAny($this->user));
});

it('allows view', function () {
    $roleToView = Role::factory()->create();

    $this->assertFalse((new RolePolicy())->view($this->user, $roleToView));

    $this->user->givePermissionTo(Permission::VIEW_ROLE);

    $this->assertTrue((new RolePolicy())->view($this->user, $roleToView));
});

it('allows create', function () {
    $this->assertFalse((new RolePolicy())->create($this->user));

    $this->user->givePermissionTo(Permission::CREATE_ROLE);

    $this->assertTrue((new RolePolicy())->create($this->user));
});

it('allows update', function () {
    $roleToUpdate = Role::factory()->create();

    $this->assertFalse((new RolePolicy())->update($this->user, $roleToUpdate));

    $this->user->givePermissionTo(Permission::EDIT_ROLE);

    $this->assertTrue((new RolePolicy())->update($this->user, $roleToUpdate));
});

it('allows delete', function () {
    $roleToDelete = Role::factory()->create();

    $this->assertFalse((new RolePolicy())->delete($this->user, $roleToDelete));

    $this->user->givePermissionTo(Permission::DELETE_ROLE);

    $this->assertTrue((new RolePolicy())->delete($this->user, $roleToDelete));
});

it('disallows force delete', function () {
    $roleToDelete = Role::factory()->create();
    $this->assertFalse((new RolePolicy())->forceDelete($this->user, $roleToDelete));
});

it('disallows restore', function () {
    $roleToRestore = Role::factory()->create();
    $this->assertFalse((new RolePolicy())->restore($this->user, $roleToRestore));
});

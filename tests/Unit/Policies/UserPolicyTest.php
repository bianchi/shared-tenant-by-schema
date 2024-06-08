<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\User;
use App\Policies\UserPolicy;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('allows view any', function () {
    $this->assertFalse((new UserPolicy())->viewAny($this->user));

    $this->user->givePermissionTo(Permission::VIEW_USER);

    $this->assertTrue((new UserPolicy())->viewAny($this->user));
});

it('allows view', function () {
    $userToView = User::factory()->create();

    $this->assertFalse((new UserPolicy())->view($this->user, $userToView));

    $this->user->givePermissionTo(Permission::VIEW_USER);

    $this->assertTrue((new UserPolicy())->view($this->user, $userToView));
});

it('allows create', function () {
    $this->assertFalse((new UserPolicy())->create($this->user));

    $this->user->givePermissionTo(Permission::CREATE_USER);

    $this->assertTrue((new UserPolicy())->create($this->user));
});

it('allows update', function () {
    $userToUpdate = User::factory()->create();

    $this->assertFalse((new UserPolicy())->update($this->user, $userToUpdate));

    $this->user->givePermissionTo(Permission::EDIT_USER);

    $this->assertTrue((new UserPolicy())->update($this->user, $userToUpdate));
});

it('allows delete', function () {
    $userToDelete = User::factory()->create();

    $this->assertFalse((new UserPolicy())->delete($this->user, $userToDelete));

    $this->user->givePermissionTo(Permission::DELETE_USER);

    $this->assertTrue((new UserPolicy())->delete($this->user, $userToDelete));
});

it('disallows force delete', function () {
    $userToDelete = User::factory()->create();
    $this->assertFalse((new UserPolicy())->forceDelete($this->user, $userToDelete));
});

it('disallows restore', function () {
    $userToRestore = User::factory()->create();
    $this->assertFalse((new UserPolicy())->restore($this->user, $userToRestore));
});

<?php

declare(strict_types=1);

namespace Tests\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource\Pages\ViewRole;
use App\Models\Permission;
use App\Models\Role;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->role = Role::factory()->create();
    $this->role->givePermissionTo(Permission::factory()->create());
});

it('has correct fields and buttons', function () {
    $permissionsIds = $this->role->permissions->pluck('id');

    livewire(ViewRole::class, ['record' => $this->role->id])
        ->assertFormExists()
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('permissions')
        ->assertFormSet([
            'name' => $this->role->name,
            //            'permissions' => $permissionsIds,
        ])
        ->assertActionExists('edit')
        ->assertActionExists('delete')
        ->assertActionDoesNotExist('save')
        ->assertActionDoesNotExist('createAnother')
        ->assertActionDoesNotExist('create');
});

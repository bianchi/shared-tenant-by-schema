<?php

declare(strict_types=1);

namespace Tests\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource\Pages\EditRole;
use App\Models\Permission;
use App\Models\Role;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->role = Role::factory()->create();
});

it('has correct fields and buttons', function () {
    livewire(EditRole::class, ['record' => $this->role->id])
        ->assertFormExists()
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('permissions')
        ->assertActionExists('save')
        ->assertActionExists('delete')
        ->assertActionExists('cancel')
        ->assertActionDoesNotExist('createAnother')
        ->assertActionDoesNotExist('create');
});

it('can save without change data', function () {
    livewire(EditRole::class, ['record' => $this->role->id])
        ->call('save')
        ->assertHasNoFormErrors();
});

it('can validate input', function () {
    livewire(EditRole::class, ['record' => $this->role->id])
        ->fillForm([
            'name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'required',
        ])->fillForm([
            'name' => 'Any valid name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();
});

it('can add permissions', function () {
    livewire(EditRole::class, ['record' => $this->role->id])
        ->fillForm([
            'permissions' => [Permission::factory()->create()->id],
        ])
        ->call('save');
});

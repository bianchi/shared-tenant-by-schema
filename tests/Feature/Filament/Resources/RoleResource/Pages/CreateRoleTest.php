<?php

declare(strict_types=1);

namespace Tests\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\RoleResource\Pages\CreateRole;
use App\Models\Permission;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

it('has correct fields and buttons', function () {
    livewire(CreateRole::class)
        ->assertFormExists()
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('permissions')
        ->assertActionExists('create')
        ->assertActionExists('createAnother')
        ->assertActionExists('cancel')
        ->assertActionDoesNotExist('save');
});

it('can validate input', function () {
    livewire(CreateRole::class)
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
        ])
        ->fillForm([
            'name' => 'Any valid name',
        ])
        ->call('create')
        ->assertHasNoFormErrors();
});

it('can add permissions', function () {
    livewire(CreateRole::class)
        ->fillForm([
            'name' => Str::random(60),
            'permissions' => [Permission::factory()->create()->id],
        ])
        ->call('create');
});

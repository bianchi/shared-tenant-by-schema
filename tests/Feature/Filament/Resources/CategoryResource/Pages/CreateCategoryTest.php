<?php

declare(strict_types=1);

namespace Tests\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

it('has correct fields and buttons', function () {
    livewire(CreateCategory::class)
        ->assertFormExists()
        ->assertFormFieldExists('name')
        ->assertActionExists('create')
        ->assertActionExists('createAnother')
        ->assertActionExists('cancel')
        ->assertActionDoesNotExist('save');
});

it('can validate input', function () {
    livewire(CreateCategory::class)
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
        ])
        ->fillForm([
            'name' => Str::random(61),
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'max',
        ])->fillForm([
            'name' => 'Any valid name',
        ])
        ->call('create')
        ->assertHasNoFormErrors();
});

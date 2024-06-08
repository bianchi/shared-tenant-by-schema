<?php

declare(strict_types=1);

namespace Tests\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Models\Category;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->category = Category::factory()->create();
});

it('has correct fields and buttons', function () {
    livewire(EditCategory::class, ['record' => $this->category->id])
        ->assertFormExists()
        ->assertFormFieldExists('name')
        ->assertActionExists('save')
        ->assertActionExists('delete')
        ->assertActionExists('cancel')
        ->assertActionDoesNotExist('createAnother')
        ->assertActionDoesNotExist('create');
});

it('can save without change data', function () {
    livewire(EditCategory::class, ['record' => $this->category->id])
        ->call('save')
        ->assertHasNoFormErrors();
});

it('can validate input', function () {
    livewire(EditCategory::class, ['record' => $this->category->id])
        ->fillForm([
            'name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'required',
        ])
        ->fillForm([
            'name' => Str::random(61),
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'max',
        ])->fillForm([
            'name' => 'Any valid name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();
});

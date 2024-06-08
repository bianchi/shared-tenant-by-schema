<?php

declare(strict_types=1);

namespace Tests\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

use function Pest\Livewire\livewire;

beforeEach(function () {
    Category::unguard();

    $this->categories = new Collection();

    $fakeDate = Carbon::now()->subYear()->startOfDay();
    for ($i = 0; $i < 8; $i++) {
        $fakeDate->addMinute();
        $this->categories->add(Category::factory()->create(['created_at' => $fakeDate, 'updated_at' => $fakeDate]));
    }
    $fakeDate->addMinute();
    $this->categories->add(Category::factory()->create(['name' => 'Pizza', 'created_at' => $fakeDate, 'updated_at' => $fakeDate]));
    $fakeDate->addMinute();
    $this->categories->add(Category::factory()->create(['name' => 'Burguer', 'created_at' => $fakeDate, 'updated_at' => $fakeDate]));

    Category::reguard();
});

it('can render page', function () {
    $this->get(CategoryResource::getUrl('index'));
    $this->get(CategoryResource::getUrl('index'))->assertSuccessful();
});

it('can list categories', function () {
    livewire(CategoryResource\Pages\ListCategories::class)
        ->assertCanSeeTableRecords($this->categories)
        ->assertCanRenderTableColumn('name')
        ->assertCanNotRenderTableColumn('created_at')
        ->assertCanNotRenderTableColumn('updated_at');
});

it('can sort categories by id', function () {
    livewire(CategoryResource\Pages\ListCategories::class)
        ->sortTable('id')
        ->assertCanSeeTableRecords($this->categories->sortBy('id'), inOrder: true)
        ->sortTable('id', 'desc')
        ->assertCanSeeTableRecords($this->categories->sortByDesc('id'), inOrder: true);
});

it('can sort categories by name', function () {
    livewire(CategoryResource\Pages\ListCategories::class)
        ->sortTable('name')
        ->assertCanSeeTableRecords($this->categories->sortBy('name'), inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords($this->categories->sortByDesc('name'), inOrder: true);
});

it('can sort categories by created_at', function () {
    livewire(CategoryResource\Pages\ListCategories::class)
        ->sortTable('created_at')
        ->assertCanSeeTableRecords($this->categories->sortBy('created_at'), inOrder: true)
        ->sortTable('created_at', 'desc')
        ->assertCanSeeTableRecords($this->categories->sortByDesc('created_at'), inOrder: true);
});

it('can sort categories by updated_at', function () {
    livewire(CategoryResource\Pages\ListCategories::class)
        ->sortTable('updated_at')
        ->assertCanSeeTableRecords($this->categories->sortBy('updated_at'), inOrder: true)
        ->sortTable('updated_at', 'desc')
        ->assertCanSeeTableRecords($this->categories->sortByDesc('updated_at'), inOrder: true);
});

it('can search categories by exact name', function () {
    livewire(CategoryResource\Pages\ListCategories::class)
        ->searchTable('Pizza')
        ->assertCanSeeTableRecords($this->categories->where('name', 'Pizza'))
        ->assertCanNotSeeTableRecords($this->categories->where('name', '!=', 'Pizza'));
});

it('can search categories by partial name', function () {
    livewire(CategoryResource\Pages\ListCategories::class)
        ->searchTable('Pizz')
        ->assertCanSeeTableRecords($this->categories->filter(fn ($user) => str_contains($user->name, 'Pizz')))
        ->assertCanNotSeeTableRecords($this->categories->filter(fn ($user) => ! str_contains($user->name, 'Pizz')));
});

it('can delete category', function () {
    $firstCategory = $this->categories->first();

    livewire(CategoryResource\Pages\ListCategories::class)
        ->callTableAction(DeleteAction::class, $firstCategory);

    $this->assertModelMissing($firstCategory);
});

it('can bulk delete categories', function () {
    $firstCategory = $this->categories->first();
    $secondCategory = $this->categories->get(1);

    livewire(CategoryResource\Pages\ListCategories::class)
        ->callTableBulkAction(DeleteBulkAction::class, collect([$firstCategory, $secondCategory]));

    $this->assertModelMissing($firstCategory);
    $this->assertModelMissing($secondCategory);
});

it('can see edit link', function () {
    livewire(CategoryResource\Pages\ListCategories::class)
        ->assertSee(CategoryResource::getUrl('edit', [
            'record' => $this->categories->first(),
        ]));
});

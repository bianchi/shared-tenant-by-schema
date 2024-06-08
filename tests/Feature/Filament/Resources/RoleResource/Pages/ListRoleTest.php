<?php

declare(strict_types=1);

namespace Tests\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\Role;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

use function Pest\Livewire\livewire;

beforeEach(function () {
    Role::unguard();

    $this->roles = new Collection();

    $fakeDate = Carbon::now()->subYear()->startOfDay();
    for ($i = 0; $i < 5; $i++) {
        $fakeDate->addMinute();
        $this->roles->add(Role::factory()->create(['name' => 'Function '.$i, 'created_at' => $fakeDate, 'updated_at' => $fakeDate]));
    }

    Role::reguard();
});

it('can render page', function () {
    $this->get(RoleResource::getUrl('index'));
    $this->get(RoleResource::getUrl('index'))->assertSuccessful();
});

it('can list roles', function () {
    livewire(RoleResource\Pages\ListRoles::class)
        ->assertCanSeeTableRecords($this->roles)
        ->assertCanRenderTableColumn('id')
        ->assertCanRenderTableColumn('name')
        ->assertCanNotRenderTableColumn('created_at')
        ->assertCanNotRenderTableColumn('updated_at');
});

it('can sort roles by id', function () {
    livewire(RoleResource\Pages\ListRoles::class)
        ->sortTable('id')
        ->assertCanSeeTableRecords($this->roles->sortBy('id'), inOrder: true)
        ->sortTable('id', 'desc')
        ->assertCanSeeTableRecords($this->roles->sortByDesc('id'), inOrder: true);
});

it('can sort roles by name', function () {
    livewire(RoleResource\Pages\ListRoles::class)
        ->sortTable('name')
        ->assertCanSeeTableRecords($this->roles->sortBy('name'), inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords($this->roles->sortByDesc('name'), inOrder: true);
});

it('can sort roles by created_at', function () {
    livewire(RoleResource\Pages\ListRoles::class)
        ->sortTable('created_at')
        ->assertCanSeeTableRecords($this->roles->sortBy('created_at'), inOrder: true)
        ->sortTable('created_at', 'desc')
        ->assertCanSeeTableRecords($this->roles->sortByDesc('created_at'), inOrder: true);
});

it('can sort roles by updated_at', function () {
    livewire(RoleResource\Pages\ListRoles::class)
        ->sortTable('updated_at')
        ->assertCanSeeTableRecords($this->roles->sortBy('updated_at'), inOrder: true)
        ->sortTable('updated_at', 'desc')
        ->assertCanSeeTableRecords($this->roles->sortByDesc('updated_at'), inOrder: true);
});

it('can search roles by exact name', function () {
    livewire(RoleResource\Pages\ListRoles::class)
        ->searchTable('Pizza')
        ->assertCanSeeTableRecords($this->roles->where('name', 'Pizza'))
        ->assertCanNotSeeTableRecords($this->roles->where('name', '!=', 'Pizza'));
});

it('can search roles by partial name', function () {
    livewire(RoleResource\Pages\ListRoles::class)
        ->searchTable('Pizz')
        ->assertCanSeeTableRecords($this->roles->filter(fn ($user) => str_contains($user->name, 'Pizz')))
        ->assertCanNotSeeTableRecords($this->roles->filter(fn ($user) => ! str_contains($user->name, 'Pizz')));
});

it('can delete category', function () {
    $firstRole = $this->roles->first();

    livewire(RoleResource\Pages\ListRoles::class)
        ->callTableAction(DeleteAction::class, $firstRole);

    $this->assertModelMissing($firstRole);
});

it('can bulk delete roles', function () {
    $firstRole = $this->roles->first();
    $secondRole = $this->roles->get(1);

    livewire(RoleResource\Pages\ListRoles::class)
        ->callTableBulkAction(DeleteBulkAction::class, collect([$firstRole, $secondRole]));

    $this->assertModelMissing($firstRole);
    $this->assertModelMissing($secondRole);
});

it('can see edit link', function () {
    livewire(RoleResource\Pages\ListRoles::class)
        ->assertSee(RoleResource::getUrl('edit', [
            'record' => $this->roles->first(),
        ]));
});

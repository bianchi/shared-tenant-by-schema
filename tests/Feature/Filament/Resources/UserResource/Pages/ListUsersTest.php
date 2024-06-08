<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

use function Pest\Livewire\livewire;

beforeEach(function () {
    User::unguard();

    $this->users = new Collection();

    $fakeDate = Carbon::now()->subYear()->startOfDay();
    for ($i = 0; $i < 7; $i++) {
        $fakeDate->addMinute();
        $this->users->add(User::factory()->create(['created_at' => $fakeDate, 'updated_at' => $fakeDate]));
    }
    $fakeDate->addMinute();
    $this->users->add(User::factory()->create(['name' => 'John White', 'email' => 'john.white@example.com', 'created_at' => $fakeDate, 'updated_at' => $fakeDate]));
    $fakeDate->addMinute();
    $this->users->add(User::factory()->create(['name' => 'John Doe', 'email' => 'john.doe@example.com', 'created_at' => $fakeDate, 'updated_at' => $fakeDate]));
    $this->users->add($this->user);

    User::reguard();
});

it('can render page', function () {
    $this->get(UserResource::getUrl('index'));
    $this->get(UserResource::getUrl('index'))->assertSuccessful();
});

it('can list users', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords($this->users)
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('email')
        ->assertCanNotRenderTableColumn('created_at')
        ->assertCanNotRenderTableColumn('updated_at');
});

it('can sort roles by id', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->sortTable('id')
        ->assertCanSeeTableRecords($this->users->sortBy('id'), inOrder: true)
        ->sortTable('id', 'desc')
        ->assertCanSeeTableRecords($this->users->sortByDesc('id'), inOrder: true);
});

it('can sort users by name', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->sortTable('name')
        ->assertCanSeeTableRecords($this->users->sortBy('name'), inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords($this->users->sortByDesc('name'), inOrder: true);
});

it('can sort users by email', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->sortTable('email')
        ->assertCanSeeTableRecords($this->users->sortBy('email'), inOrder: true)
        ->sortTable('email', 'desc')
        ->assertCanSeeTableRecords($this->users->sortByDesc('email'), inOrder: true);
});

it('can sort users by created_at', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->sortTable('created_at')
        ->assertCanSeeTableRecords($this->users->sortBy('created_at'), inOrder: true)
        ->sortTable('created_at', 'desc')
        ->assertCanSeeTableRecords($this->users->sortByDesc('created_at'), inOrder: true);
});

it('can sort users by updated_at', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->sortTable('updated_at')
        ->assertCanSeeTableRecords($this->users->sortBy('updated_at'), inOrder: true)
        ->sortTable('updated_at', 'desc')
        ->assertCanSeeTableRecords($this->users->sortByDesc('updated_at'), inOrder: true);
});

it('can search users by exact name', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->searchTable('John Doe')
        ->assertCanSeeTableRecords($this->users->where('name', 'John Doe'))
        ->assertCanNotSeeTableRecords($this->users->where('name', '!=', 'John Doe'));
});

it('can search users by partial name', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->searchTable('John')
        ->assertCanSeeTableRecords($this->users->filter(fn ($user) => str_contains($user->name, 'John')))
        ->assertCanNotSeeTableRecords($this->users->filter(fn ($user) => ! str_contains($user->name, 'John')));
});

it('can search users by exact email', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->searchTable('john.doe@example.com')
        ->assertCanSeeTableRecords($this->users->where('email', 'john.doe@example.com'))
        ->assertCanNotSeeTableRecords($this->users->where('email', '!=', 'john.doe@example.com'));
});

it('can search users by partial email', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->searchTable('john@')
        ->assertCanSeeTableRecords($this->users->filter(fn ($user) => str_contains($user->email, 'john@')))
        ->assertCanNotSeeTableRecords($this->users->filter(fn ($user) => ! str_contains($user->email, 'john@')));
});

it('can delete user', function () {
    $firstUser = $this->users->first();

    livewire(UserResource\Pages\ListUsers::class)
        ->callTableAction(DeleteAction::class, $firstUser);

    $this->assertModelMissing($firstUser);
});

it('can bulk delete users', function () {
    $firstUser = $this->users->first();
    $secondUser = $this->users->get(1);

    livewire(UserResource\Pages\ListUsers::class)
        ->callTableBulkAction(DeleteBulkAction::class, collect([$firstUser, $secondUser]));

    $this->assertModelMissing($firstUser);
    $this->assertModelMissing($secondUser);
});

it('can see edit link', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->assertSee(UserResource::getUrl('edit', [
            'record' => $this->users->first(),
        ]));
});

it('delete will halt if user is the account owner', function () {
    $accountOwner = User::first();

    livewire(UserResource\Pages\ListUsers::class)
        ->callTableAction(DeleteAction::class, $accountOwner)
        ->assertTableActionHalted(DeleteAction::class);

    $this->assertModelExists($accountOwner);
});

it('bulk delete will halt if user is the account owner', function () {
    $accountOwner = User::first();

    livewire(UserResource\Pages\ListUsers::class)
        ->callTableBulkAction(DeleteBulkAction::class, collect([$accountOwner]))
        ->assertTableBulkActionHalted(DeleteBulkAction::class);

    $this->assertModelExists($accountOwner);
});

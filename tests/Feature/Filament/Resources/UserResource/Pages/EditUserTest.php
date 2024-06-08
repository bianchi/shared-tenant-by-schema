<?php

declare(strict_types=1);

namespace Tests\Filament\Resources\UserResource\Pages;

use App\Enums\Role;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->user->assignRole(Role::SuperAdmin);
});

it('has correct fields and buttons', function () {
    livewire(EditUser::class, ['record' => $this->user->id])
        ->assertFormExists()
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('email')
        ->assertFormFieldIsHidden('password')
        ->assertFormFieldIsHidden('password_confirmation')
        ->assertFormFieldExists('roles')
        ->assertActionExists('save')
        ->assertActionExists('delete')
        ->assertActionExists('cancel')
        ->assertActionDoesNotExist('createAnother')
        ->assertActionDoesNotExist('create');
});

it('can save without change data', function () {
    livewire(EditUser::class, ['record' => $this->user->id])
        ->call('save')
        ->assertHasNoFormErrors();
});

it('can validate input', function () {
    livewire(EditUser::class, ['record' => $this->user->id])
        ->fillForm([
            'name' => null,
            'email' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
        ])
        ->fillForm([
            'name' => 'Any name',
            'email' => 'invalidemail.com',
        ])
        ->call('save')
        ->assertHasFormErrors([
            'email' => 'email',
        ])->fillForm([
            'email' => 'valid@email.com',
        ])
        ->call('save')
        ->assertHasNoFormErrors();
});

it('delete will halt if user is the account owner', function () {
    $accountOwner = User::first();

    livewire(EditUser::class, ['record' => $accountOwner->id])
        ->callAction('delete')
        ->assertActionHalted(DeleteAction::class)
        ->assertNotified(
            Notification::make()
                ->title(__('Error'))
                ->body(__('The account owner cannot be deleted.'))
                ->status('danger')
        );

    $this->assertModelExists($accountOwner);
});

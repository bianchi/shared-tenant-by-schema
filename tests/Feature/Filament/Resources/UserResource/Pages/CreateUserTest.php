<?php

declare(strict_types=1);

namespace Tests\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource\Pages\CreateUser;

use function Pest\Livewire\livewire;

it('has correct fields and buttons', function () {
    livewire(CreateUser::class)
        ->assertFormExists()
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('email')
        ->assertFormFieldExists('password')
        ->assertFormFieldExists('password_confirmation')
        ->assertFormFieldExists('roles')
        ->assertActionExists('create')
        ->assertActionExists('createAnother')
        ->assertActionExists('cancel')
        ->assertActionDoesNotExist('save');
});

it('can validate input', function () {
    livewire(CreateUser::class)
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required',
        ])
        ->fillForm([
            'name' => 'Any name',
            'email' => 'invalidemail.com',
            'password' => '123',
            'password_confirmation' => '1234',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'email' => 'email',
            'password' => 'confirmed',
        ])->fillForm([
            'email' => 'valid@email.com',
            'password_confirmation' => '123',
        ])
        ->call('create')
        ->assertHasNoFormErrors();
});

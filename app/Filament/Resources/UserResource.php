<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'usuário';
    protected static ?string $pluralModelLabel = 'usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->confirmed()
                    ->required()
                    ->maxLength(255)
                    ->hiddenOn(Pages\EditUser::class),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->hiddenOn(Pages\EditUser::class),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.roles')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(
                        function ($record, Tables\Actions\DeleteAction $action) {
                            $accountOwnerId = User::first()->id;
                            if ($record->id === $accountOwnerId) {
                                Notification::make()
                                    ->title(__('Error'))
                                    ->body(__('The account owner cannot be deleted.'))
                                    ->status('danger')
                                    ->send();

                                // todo change to cancel if discover how to test cancel
                                $action->halt();
                            }
                        }
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(
                            function ($records, Tables\Actions\DeleteBulkAction $action) {
                                $idsToDelete = $records->pluck('id')->toArray();
                                $accountOwnerId = User::first()->id;
                                if (in_array($accountOwnerId, $idsToDelete, true)) {
                                    Notification::make()
                                        ->title(__('messages.error'))
                                        ->body(__('messages.cannot_delete_account_owner'))
                                        ->status('danger')
                                        ->send();

                                    // todo change to cancel if discover how to test cancel
                                    $action->halt();
                                }
                            }
                        ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

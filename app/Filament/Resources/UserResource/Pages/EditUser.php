<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\EditRecord;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(
                    function ($record, DeleteAction $action) {
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
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament;

class EditRecord extends \Filament\Resources\Pages\EditRecord
{
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

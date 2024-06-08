<?php

declare(strict_types=1);

namespace App\Filament;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case SuperAdmin = 'Super admin';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => __('roles.super_admin')
        };
    }
}

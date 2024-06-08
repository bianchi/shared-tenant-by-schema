<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\Role;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Column::configureUsing(static function (Column $column): void {
            $column->toggleable()
                ->translateLabel();
        });

        Gate::after(static function ($user, $ability) {
            return $user->hasRole(Role::SuperAdmin);
        });

        Gate::policy(\Spatie\Permission\Models\Permission::class, PermissionPolicy::class);
        Gate::policy(\Spatie\Permission\Models\Role::class, RolePolicy::class);

        DateTimePicker::configureUsing(static fn () => DateTimePicker::$defaultDateDisplayFormat = __('format.date'));
        DateTimePicker::configureUsing(static fn () => DateTimePicker::$defaultDateTimeDisplayFormat = __('format.dateTime'));
        Table::configureUsing(static fn () => Table::$defaultDateDisplayFormat = __('format.date'));
        Table::configureUsing(static fn () => Table::$defaultDateTimeDisplayFormat = __('format.dateTime'));
    }
}

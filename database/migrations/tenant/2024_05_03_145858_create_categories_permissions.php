<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $superAdmin = Role::query()->where('name', \App\Enums\Role::SuperAdmin)->firstOrFail();

        $superAdmin->givePermissionTo(Permission::create(['name' => 'Create categories']));
        $superAdmin->givePermissionTo(Permission::create(['name' => 'View categories']));
        $superAdmin->givePermissionTo(Permission::create(['name' => 'Edit categories']));
        $superAdmin->givePermissionTo(Permission::create(['name' => 'Delete categories']));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::query()->where('name', 'Create categories')->delete();
        Permission::query()->where('name', 'View categories')->delete();
        Permission::query()->where('name', 'Edit categories')->delete();
        Permission::query()->where('name', 'Delete categories')->delete();
    }
};

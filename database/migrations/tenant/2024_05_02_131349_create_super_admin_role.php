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
        $superAdmin = Role::create(['name' => 'Super admin']);

        $superAdmin->givePermissionTo(Permission::create(['name' => 'Create users']));
        $superAdmin->givePermissionTo(Permission::create(['name' => 'View users']));
        $superAdmin->givePermissionTo(Permission::create(['name' => 'Edit users']));
        $superAdmin->givePermissionTo(Permission::create(['name' => 'Delete users']));

        $superAdmin->givePermissionTo(Permission::create(['name' => 'Create roles']));
        $superAdmin->givePermissionTo(Permission::create(['name' => 'View roles']));
        $superAdmin->givePermissionTo(Permission::create(['name' => 'Edit roles']));
        $superAdmin->givePermissionTo(Permission::create(['name' => 'Delete roles']));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

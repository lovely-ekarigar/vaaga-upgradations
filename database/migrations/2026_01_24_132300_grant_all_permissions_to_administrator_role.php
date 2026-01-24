<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class GrantAllPermissionsToAdministratorRole extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        $permissionsTable = config('permission.table_names.permissions', 'permissions');
        $rolesTable = config('permission.table_names.roles', 'roles');
        $roleHasPermissionsTable = config('permission.table_names.role_has_permissions', 'role_has_permissions');

        if (!Schema::hasTable($permissionsTable) || !Schema::hasTable($rolesTable) || !Schema::hasTable($roleHasPermissionsTable)) {
            return;
        }

        $guard = config('auth.defaults.guard', 'web');
        $adminRoleName = config('access.users.admin_role', 'administrator');

        $adminRoleId = DB::table($rolesTable)
            ->where('name', $adminRoleName)
            ->where('guard_name', $guard)
            ->value('id');

        if (!$adminRoleId) {
            return;
        }

        $permissionIds = DB::table($permissionsTable)
            ->where('guard_name', $guard)
            ->pluck('id')
            ->all();

        foreach ($permissionIds as $permissionId) {
            DB::table($roleHasPermissionsTable)->updateOrInsert(
                ['permission_id' => $permissionId, 'role_id' => $adminRoleId],
                []
            );
        }

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        // no-op
    }
}


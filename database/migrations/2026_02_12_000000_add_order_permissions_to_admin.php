<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class AddOrderPermissionsToAdmin extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        $permissionsTable = config('permission.table_names.permissions', 'permissions');
        $rolesTable = config('permission.table_names.roles', 'roles');
        $roleHasPermissionsTable = config('permission.table_names.role_has_permissions', 'role_has_permissions');

        if (!Schema::hasTable($permissionsTable) || !Schema::hasTable($rolesTable)) {
            return;
        }

        $guard = config('auth.defaults.guard', 'web');
        $adminRoleName = config('access.users.admin_role', 'administrator');

        $adminRoleId = DB::table($rolesTable)
            ->where('name', $adminRoleName)
            ->where('guard_name', $guard)
            ->value('id');

        // Define order permissions
        $orderPermissions = [
            'order_access',
            'order_create',
            'order_edit',
            'order_show',
            'order_delete',
        ];

        foreach ($orderPermissions as $permissionName) {
            // Check if permission exists, if not create it
            $permissionId = DB::table($permissionsTable)
                ->where('name', $permissionName)
                ->where('guard_name', $guard)
                ->value('id');

            if (!$permissionId) {
                $permissionId = DB::table($permissionsTable)->insertGetId([
                    'name' => $permissionName,
                    'guard_name' => $guard,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Assign permission to admin role if role exists
            if ($adminRoleId && $permissionId) {
                DB::table($roleHasPermissionsTable)->updateOrInsert(
                    ['permission_id' => $permissionId, 'role_id' => $adminRoleId],
                    []
                );
            }
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

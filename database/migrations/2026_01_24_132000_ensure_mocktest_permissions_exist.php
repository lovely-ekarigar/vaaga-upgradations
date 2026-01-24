<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class EnsureMocktestPermissionsExist extends Migration
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
        $now = now();

        $permissionNames = [
            'mocktest_access',
            'mocktest_create',
            'mocktest_edit',
            'mocktest_view',
            'mocktest_delete',
        ];

        // Ensure permissions exist
        $permissionIds = [];
        foreach ($permissionNames as $name) {
            DB::table($permissionsTable)->updateOrInsert(
                ['name' => $name, 'guard_name' => $guard],
                ['updated_at' => $now, 'created_at' => $now]
            );

            $permissionIds[$name] = DB::table($permissionsTable)
                ->where('name', $name)
                ->where('guard_name', $guard)
                ->value('id');
        }

        // Ensure roles exist
        $adminRoleName = config('access.users.admin_role', 'administrator');
        $adminRoleId = DB::table($rolesTable)->where('name', $adminRoleName)->where('guard_name', $guard)->value('id');
        $teacherRoleId = DB::table($rolesTable)->where('name', 'teacher')->where('guard_name', $guard)->value('id');
        $studentRoleId = DB::table($rolesTable)->where('name', 'student')->where('guard_name', $guard)->value('id');

        // Attach permissions to admin + teacher (so both can use backend mock tests)
        foreach (array_filter([$adminRoleId, $teacherRoleId]) as $roleId) {
            foreach ($permissionIds as $permId) {
                if (!$permId) {
                    continue;
                }
                DB::table($roleHasPermissionsTable)->updateOrInsert(
                    ['role_id' => $roleId, 'permission_id' => $permId],
                    []
                );
            }
        }

        // Students only need view/access in most cases; grant minimal set for safety.
        if ($studentRoleId) {
            foreach (['mocktest_access', 'mocktest_view'] as $p) {
                $permId = $permissionIds[$p] ?? null;
                if ($permId) {
                    DB::table($roleHasPermissionsTable)->updateOrInsert(
                        ['role_id' => $studentRoleId, 'permission_id' => $permId],
                        []
                    );
                }
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
        // no-op (safety migration)
    }
}


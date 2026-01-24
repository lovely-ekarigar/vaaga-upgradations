<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class FixPermissionsTableAndAssignMocktestPermissions extends Migration
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

        // 1) Fix "id = 0" duplicates (our mocktest permissions were inserted without explicit id)
        $mockPermNames = [
            'mocktest_access',
            'mocktest_create',
            'mocktest_edit',
            'mocktest_view',
            'mocktest_delete',
        ];

        $maxId = (int) DB::table($permissionsTable)->max('id');
        foreach ($mockPermNames as $name) {
            $row = DB::table($permissionsTable)
                ->where('name', $name)
                ->where('guard_name', $guard)
                ->first();

            if ($row && (int) $row->id === 0) {
                $maxId++;
                DB::table($permissionsTable)
                    ->where('name', $name)
                    ->where('guard_name', $guard)
                    ->where('id', 0)
                    ->update(['id' => $maxId]);
            }
        }

        // 2) Best-effort: make permissions.id a proper AUTO_INCREMENT PK (prevents future id=0 rows)
        try {
            DB::statement("ALTER TABLE `{$permissionsTable}` ADD PRIMARY KEY (`id`)");
        } catch (\Throwable $e) {
            // ignore if already has PK or cannot add
        }
        try {
            DB::statement("ALTER TABLE `{$permissionsTable}` MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT");
        } catch (\Throwable $e) {
            // ignore if already auto-increment or cannot modify
        }
        try {
            $maxId = (int) DB::table($permissionsTable)->max('id');
            DB::statement("ALTER TABLE `{$permissionsTable}` AUTO_INCREMENT = " . ($maxId + 1));
        } catch (\Throwable $e) {
            // ignore
        }

        // 3) Attach mocktest permissions to roles
        $adminRoleName = config('access.users.admin_role', 'administrator');
        $adminRoleId = DB::table($rolesTable)->where('name', $adminRoleName)->where('guard_name', $guard)->value('id');
        $teacherRoleId = DB::table($rolesTable)->where('name', 'teacher')->where('guard_name', $guard)->value('id');
        $studentRoleId = DB::table($rolesTable)->where('name', 'student')->where('guard_name', $guard)->value('id');

        $permIds = DB::table($permissionsTable)
            ->where('guard_name', $guard)
            ->whereIn('name', $mockPermNames)
            ->pluck('id', 'name')
            ->all();

        foreach (array_filter([$adminRoleId, $teacherRoleId]) as $roleId) {
            foreach ($mockPermNames as $p) {
                $permId = $permIds[$p] ?? null;
                if ($permId) {
                    DB::table($roleHasPermissionsTable)->updateOrInsert(
                        ['role_id' => $roleId, 'permission_id' => $permId],
                        []
                    );
                }
            }
        }

        // Students: minimal view/access
        if ($studentRoleId) {
            foreach (['mocktest_access', 'mocktest_view'] as $p) {
                $permId = $permIds[$p] ?? null;
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
        // no-op
    }
}


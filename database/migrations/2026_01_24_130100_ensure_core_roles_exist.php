<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class EnsureCoreRolesExist extends Migration
{
    /**
     * Ensure core roles exist for the default guard.
     *
     * This prevents runtime RoleDoesNotExist exceptions when code/routes
     * check for roles like "teacher" before the database is seeded.
     *
     * @return void
     */
    public function up()
    {
        $rolesTable = config('permission.table_names.roles', 'roles');

        if (!Schema::hasTable($rolesTable)) {
            return;
        }

        $guard = config('auth.defaults.guard', 'web');

        $roleNames = array_values(array_filter([
            config('access.users.admin_role', 'administrator'),
            'author',
            'teacher',
            'student',
            'user',
        ]));

        $now = now();

        foreach ($roleNames as $name) {
            DB::table($rolesTable)->updateOrInsert(
                ['name' => $name, 'guard_name' => $guard],
                ['updated_at' => $now, 'created_at' => $now]
            );
        }

        // Clear Spatie permission cache so the newly created roles are visible immediately.
        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $rolesTable = config('permission.table_names.roles', 'roles');

        if (!Schema::hasTable($rolesTable)) {
            return;
        }

        $guard = config('auth.defaults.guard', 'web');

        DB::table($rolesTable)
            ->where('guard_name', $guard)
            ->whereIn('name', [
                config('access.users.admin_role', 'administrator'),
                'author',
                'teacher',
                'student',
                'user',
            ])
            ->delete();

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class EnsureAuthorRoleExists extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        $rolesTable = config('permission.table_names.roles', 'roles');

        if (!Schema::hasTable($rolesTable)) {
            return;
        }

        $guard = config('auth.defaults.guard', 'web');
        $now = now();

        DB::table($rolesTable)->updateOrInsert(
            ['name' => 'author', 'guard_name' => $guard],
            ['updated_at' => $now, 'created_at' => $now]
        );

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    /**
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
            ->where('name', 'author')
            ->where('guard_name', $guard)
            ->delete();

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsStarToUsersTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        $usersTable = config('access.table_names.users', 'users');

        if (!Schema::hasTable($usersTable)) {
            return;
        }

        if (!Schema::hasColumn($usersTable, 'is_star')) {
            Schema::table($usersTable, function (Blueprint $table) {
                $table->tinyInteger('is_star')->default(0)->after('on_home');
            });
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        // Intentionally no-op: safety net migration.
    }
}


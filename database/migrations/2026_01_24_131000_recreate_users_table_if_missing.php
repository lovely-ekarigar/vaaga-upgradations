<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateUsersTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        $usersTable = config('access.table_names.users', 'users');

        if (!Schema::hasTable($usersTable)) {
            Schema::create($usersTable, function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->nullable();

                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();

                $table->string('email')->unique();

                // Profile fields (used across the app)
                $table->string('dob')->nullable();
                $table->string('phone')->nullable();
                $table->string('gender')->nullable();
                $table->longText('address')->nullable();
                $table->string('city')->nullable();
                $table->string('pincode')->nullable();
                $table->string('state')->nullable();
                $table->string('country')->nullable();

                // Legacy/app-specific flags
                $table->string('type')->nullable();
                $table->tinyInteger('on_home')->default(0);
                $table->string('ip')->nullable();

                $table->string('avatar_type')->default('gravatar');
                $table->string('avatar_location')->nullable();

                $table->string('password')->nullable();
                $table->timestamp('password_changed_at')->nullable();

                $table->tinyInteger('active')->default(1)->unsigned();
                $table->string('confirmation_code')->nullable();
                $table->boolean('confirmed')->default(config('access.users.confirm_email') ? false : true);

                $table->string('timezone')->nullable();
                $table->timestamp('last_login_at')->nullable();
                $table->string('last_login_ip')->nullable();

                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });

            return;
        }

        // If the table exists but is missing columns (common when DB was partially imported),
        // add the ones that are required by current code paths.
        Schema::table($usersTable, function (Blueprint $table) use ($usersTable) {
            $addString = function (string $column) use ($table, $usersTable) {
                if (!Schema::hasColumn($usersTable, $column)) {
                    $table->string($column)->nullable();
                }
            };

            $addText = function (string $column) use ($table, $usersTable) {
                if (!Schema::hasColumn($usersTable, $column)) {
                    $table->longText($column)->nullable();
                }
            };

            if (!Schema::hasColumn($usersTable, 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }

            foreach (['dob', 'phone', 'gender', 'city', 'pincode', 'state', 'country', 'type', 'ip'] as $col) {
                $addString($col);
            }

            $addText('address');

            if (!Schema::hasColumn($usersTable, 'on_home')) {
                $table->tinyInteger('on_home')->default(0);
            }
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        // Intentionally no-op.
        // This migration is a safety net to restore a missing users table in local/dev databases.
    }
}


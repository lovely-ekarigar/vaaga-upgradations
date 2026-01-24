<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTeacherBatchesTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('student_teacher_batches')) {
            return;
        }

        Schema::create('student_teacher_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uid'); // student user id
            $table->unsignedBigInteger('bid'); // batch id
            $table->unsignedBigInteger('tid')->nullable(); // teacher id (optional)
            $table->timestamps();

            $table->index(['uid']);
            $table->index(['bid']);
            $table->index(['tid']);
            $table->unique(['uid', 'bid']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        // Intentionally no-op: safety migration for broken local DBs.
    }
}


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherAttendancesTableIfMissing extends Migration
{
    /**
     * Run the migrations.
     * Creates teacher_attendances table used by Earning and TeachersController.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('teacher_attendances')) {
            return;
        }

        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->decimal('hours', 10, 2)->default(0);
            $table->decimal('fees', 10, 2)->nullable()->comment('Per-hour or per-minute rate for earning calculation');
            $table->date('date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['teacher_id']);
            $table->index(['batch_id']);
            $table->index(['date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_attendances');
    }
}

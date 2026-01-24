<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherBatchesTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('teacher_batches')) {
            return;
        }

        Schema::create('teacher_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tid'); // teacher user id
            $table->unsignedBigInteger('bid'); // batch id
            $table->timestamps();

            $table->index(['tid']);
            $table->index(['bid']);
            $table->unique(['tid', 'bid']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        // no-op
    }
}


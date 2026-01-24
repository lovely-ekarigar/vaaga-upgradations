<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateRecordingsTableIfMissing extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('recordings')) {
            return;
        }

        Schema::create('recordings', function (Blueprint $table) {
            $table->id();

            // batch / meeting linkage
            $table->string('parent')->nullable(); // usually Batch->parent_api_class_id
            $table->string('api_class_id')->nullable(); // meeting id used to join
            $table->string('internal_id')->nullable(); // recording id used by provider
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->unsignedBigInteger('lesson_id')->nullable();

            // recording details
            $table->string('name')->nullable();
            $table->text('view_url')->nullable();
            $table->bigInteger('start_time')->nullable(); // provider ms epoch
            $table->bigInteger('end_time')->nullable();   // provider ms epoch
            $table->tinyInteger('status')->nullable();

            $table->timestamps();

            $table->index(['parent']);
            $table->index(['batch_id']);
            $table->index(['lesson_id']);
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


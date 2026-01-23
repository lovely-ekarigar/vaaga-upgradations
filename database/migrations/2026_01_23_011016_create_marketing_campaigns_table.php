<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // email, sms, whatsapp
            $table->string('status')->default('active'); // active, inactive
            $table->string('audience')->default('all'); // all, active, inactive, converted
            $table->string('subject')->default('all'); // all or specific subject
            $table->string('grade')->default('all'); // all or specific grade
            $table->string('skip')->default('all'); // all or specific skip
            $table->unsignedBigInteger('list_id')->nullable();
            $table->string('aisensy_campaign_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('type');
        });
        
        Schema::create('marketing_campaign_lead', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('lead_id');
            $table->timestamps();
            
            $table->foreign('campaign_id')->references('id')->on('marketing_campaigns')->onDelete('cascade');
            $table->foreign('lead_id')->references('id')->on('marketing_leads')->onDelete('cascade');
            $table->unique(['campaign_id', 'lead_id']);
        });
        
        Schema::create('marketing_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('marketing_list_lead', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('list_id');
            $table->unsignedBigInteger('lead_id');
            $table->timestamps();
            
            $table->foreign('list_id')->references('id')->on('marketing_lists')->onDelete('cascade');
            $table->foreign('lead_id')->references('id')->on('marketing_leads')->onDelete('cascade');
            $table->unique(['list_id', 'lead_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_list_lead');
        Schema::dropIfExists('marketing_lists');
        Schema::dropIfExists('marketing_campaign_lead');
        Schema::dropIfExists('marketing_campaigns');
    }
};

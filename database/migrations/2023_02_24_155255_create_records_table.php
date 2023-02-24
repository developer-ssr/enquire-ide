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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('participant_id');
            $table->boolean('test_id');
            $table->string('survey_code')->index();
            $table->string('status')->default('og');
            $table->json('part')->nullable();
            $table->json('links_list')->nullable();
            $table->json('links_status')->nullable();
            $table->json('url_data')->nullable();
            $table->json('participant_data')->nullable();
            $table->json('recruiter_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};

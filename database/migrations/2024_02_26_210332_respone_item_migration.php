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
        Schema::create('afears_response_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')
                ->constrained('afears_response')
                ->onDelete('cascade');
            $table->integer('questionnaire_id');
            $table->integer('response_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afears_response_item');
    }
};

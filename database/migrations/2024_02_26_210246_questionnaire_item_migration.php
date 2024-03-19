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
        Schema::create('afears_questionnaire_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')
                ->constrained('afears_questionnaire')
                ->onDelete('cascade');
            $table->foreignId('criteria_id')
                ->constrained('afears_criteria')
                ->onDelete('cascade');
            $table->longText('item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afears_questionnaire_item');
    }
};

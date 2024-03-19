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
        Schema::create('afears_response', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('afears_student');
            $table->integer('evaluation_id');
            $table->integer('template_id');
            $table->integer('faculty_id');
            $table->integer('semester');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afears_response');
    }
};

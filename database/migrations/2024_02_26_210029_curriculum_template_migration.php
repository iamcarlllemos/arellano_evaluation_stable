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
        Schema::create('afears_curriculum_template', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')
                ->constrained('afears_department')
                ->onDelete('cascade');
            $table->foreignId('course_id')
                ->constrained('afears_course')
                ->onDelete('cascade');
            $table->foreignId('subject_id')
                ->constrained('afears_subject')
                ->onDelete('cascade');
            $table->integer('subject_sem');
            $table->integer('year_level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afears_curriculum_template');
    }
};

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
        Schema::create('afears_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->constrained('afears_course')
                ->onDelete('cascade');
            $table->string('student_number');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename');
            $table->string('gender');
            $table->string('birthday');
            $table->integer('year_level');
            $table->string('image');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afears_student');
    }
};

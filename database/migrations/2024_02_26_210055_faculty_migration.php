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
        Schema::create('afears_faculty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')
                ->constrained('afears_department')
                ->onDelete('cascade');
            $table->string('employee_number');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename');
            $table->string('email');
            $table->string('gender');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afears_faculty');
    }
};

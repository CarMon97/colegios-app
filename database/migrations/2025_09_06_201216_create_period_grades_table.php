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
        Schema::create('period_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_period_id');
            $table->unsignedBigInteger('student_id');
            $table->float('final_grade');
            $table->enum('status', ['approved', 'reproved']);


            $table->foreign('academic_period_id')->references('id')->on('academic_periods');
            $table->foreign('student_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_grades');
    }
};

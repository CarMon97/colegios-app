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
        Schema::create('school_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('school_year_id');
            $table->boolean('status');
            $table->unsignedBigInteger('school_grade_id');
            $table->timestamps();

            $table->foreign('school_year_id')->references('id')->on('school_years');
            $table->foreign('school_grade_id')->references('id')->on('school_grades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_groups');
    }
};

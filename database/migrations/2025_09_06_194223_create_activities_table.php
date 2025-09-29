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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->unsignedBigInteger('subject_id');
            $table->float('percentage_equivalence');
            $table->unsignedBigInteger('school_group_id');
            $table->unsignedBigInteger('teacher_id');
            

            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('school_group_id')->references('id')->on('school_groups');
            $table->foreign('teacher_id')->references('id')->on('users');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

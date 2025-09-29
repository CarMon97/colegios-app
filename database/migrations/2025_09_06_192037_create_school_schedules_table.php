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
        Schema::create('school_schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('day', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('status')->default(true); // Activo/Inactivo
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('school_group_id'); // Grupo al que pertenece

            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('teacher_id')->references('id')->on('users');
            $table->foreign('school_group_id')->references('id')->on('school_groups');
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['day', 'start_time']);
            $table->index(['teacher_id', 'day']);
            $table->index(['school_group_id', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_schedules');
    }
};

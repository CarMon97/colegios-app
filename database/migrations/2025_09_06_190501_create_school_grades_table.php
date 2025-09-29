<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('school_grades', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('school_grades')->insert([
            ['name' => 'Primero'],
            ['name' => 'Segundo'],
            ['name' => 'Tercero'],
            ['name' => 'Cuarto'],
            ['name' => 'Quinto'],
            ['name' => 'Sexto'],
            ['name' => 'Séptimo'],
            ['name' => 'Octavo'],
            ['name' => 'Noveno'],
            ['name' => 'Décimo'],
            ['name' => 'Once'],
            
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_grades');
    }
};

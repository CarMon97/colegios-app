<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('type_documents', function (Blueprint $table) {
            $table->id();
            $table->string('short_name');
            $table->string('name');
            $table->timestamps();
        });

        DB::table('type_documents')->insert([
            ['short_name' => 'CC', 'name' => 'Cédula de ciudadanía'],
            ['short_name' => 'CE', 'name' => 'Cédula de extranjería'],
            ['short_name' => 'PA', 'name' => 'Pasaporte'],
            ['short_name' => 'TI', 'name' => 'Tarjeta de identidad'],
            ['short_name' => 'RC', 'name' => 'Registro civil'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_documents');
    }
};

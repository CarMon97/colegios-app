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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('type_document_id');
            $table->unsignedBigInteger('municipality_id');
            

            $table->foreign('type_document_id')->references('id')->on('type_documents');
            $table->foreign('municipality_id')->references('id')->on('municipalities');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['type_document_id']);
            $table->dropForeign(['municipality_id']);
            $table->dropColumn(['type_document_id', 'municipality_id']);
        });
    }
};

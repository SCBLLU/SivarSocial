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
        Schema::table('universidades', function (Blueprint $table) {
            // Agregar columna dominio para el email institucional
            $table->string('dominio')->nullable()->after('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('universidades', function (Blueprint $table) {
            $table->dropColumn('dominio');
        });
    }
};

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
            // Agregar columna universidad_id con relación a la tabla universidades
            $table->foreignId('universidad_id')->nullable()->constrained('universidades')->onDelete('set null');

            // Agregar columna carrera_id con relación a la tabla carreras
            $table->foreignId('carrera_id')->nullable()->constrained('carreras')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar las foreign keys primero
            $table->dropForeign(['universidad_id']);
            $table->dropForeign(['carrera_id']);

            // Luego eliminar las columnas
            $table->dropColumn(['universidad_id', 'carrera_id']);
        });
    }
};

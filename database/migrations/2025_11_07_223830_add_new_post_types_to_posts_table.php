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
        Schema::table('posts', function (Blueprint $table) {
            // Hacer campos opcionales (nullable)
            $table->string('titulo')->nullable()->change();
            $table->text('descripcion')->nullable()->change();
            $table->string('imagen')->nullable()->change();
            
            // Agregar nuevos campos para tipos de posts
            $table->text('texto')->nullable()->after('descripcion');
            $table->string('archivo')->nullable()->after('texto');
            $table->string('tipo')->default('imagen')->after('archivo');
            $table->string('visibility')->default('public')->after('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['texto', 'archivo', 'tipo', 'visibility']);
            
            // Revertir a NOT NULL
            $table->string('titulo')->nullable(false)->change();
            $table->text('descripcion')->nullable(false)->change();
            $table->string('imagen')->nullable(false)->change();
        });
    }
};

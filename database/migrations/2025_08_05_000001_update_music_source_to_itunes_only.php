<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Actualiza el campo music_source para que solo use 'itunes' 
     * ya que eliminamos el soporte de búsqueda de Spotify
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Cambiar el enum para que solo tenga 'itunes'
            $table->enum('music_source', ['itunes'])->default('itunes')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Revertir a los valores originales
            $table->enum('music_source', ['itunes', 'spotify'])->default('itunes')->change();
        });
    }
};

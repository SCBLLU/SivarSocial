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
            // Campos para enlaces cruzados entre plataformas
            $table->string('apple_music_url')->nullable()->after('music_source')->comment('URL para abrir en Apple Music');
            $table->string('spotify_web_url')->nullable()->after('apple_music_url')->comment('URL para abrir en Spotify web/app');
            
            // Campos adicionales para mejorar la búsqueda cruzada
            $table->string('artist_search_term')->nullable()->after('spotify_web_url')->comment('Término de búsqueda del artista');
            $table->string('track_search_term')->nullable()->after('artist_search_term')->comment('Término de búsqueda de la canción');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'apple_music_url',
                'spotify_web_url', 
                'artist_search_term',
                'track_search_term'
            ]);
        });
    }
};

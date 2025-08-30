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
     * Migración consolidada que añade todos los campos de música:
     * - Campos de iTunes (para búsquedas principales)
     * - Enlaces cruzados entre plataformas
     * - Genera automáticamente enlaces para posts existentes
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Campo base para tipo de post
            $table->enum('tipo', ['imagen', 'musica'])->default('imagen')->after('descripcion');
            
            // Campos de iTunes (sistema principal de búsqueda)
            $table->string('itunes_track_id')->nullable()->after('tipo');
            $table->string('itunes_track_name')->nullable()->after('itunes_track_id');
            $table->string('itunes_artist_name')->nullable()->after('itunes_track_name');
            $table->string('itunes_collection_name')->nullable()->after('itunes_artist_name'); // Álbum
            $table->string('itunes_artwork_url')->nullable()->after('itunes_collection_name');
            $table->string('itunes_preview_url')->nullable()->after('itunes_artwork_url');
            $table->string('itunes_track_view_url')->nullable()->after('itunes_preview_url'); // Link a Apple Music
            $table->integer('itunes_track_time_millis')->nullable()->after('itunes_track_view_url'); // Duración
            $table->string('itunes_country')->nullable()->after('itunes_track_time_millis');
            $table->string('itunes_primary_genre_name')->nullable()->after('itunes_country');
            
            // Campo para indicar la fuente de búsqueda (solo iTunes ahora)
            $table->enum('music_source', ['itunes'])->default('itunes')->after('itunes_primary_genre_name');
            
            // Campos para enlaces cruzados entre plataformas
            $table->string('apple_music_url')->nullable()->after('music_source')->comment('URL para abrir en Apple Music');
            $table->string('spotify_web_url')->nullable()->after('apple_music_url')->comment('URL para abrir en Spotify web/app');
            
            // Campos adicionales para mejorar la búsqueda cruzada
            $table->string('artist_search_term')->nullable()->after('spotify_web_url')->comment('Término de búsqueda del artista');
            $table->string('track_search_term')->nullable()->after('artist_search_term')->comment('Término de búsqueda de la canción');
        });

        // Generar enlaces cruzados para posts existentes (si los hay)
        $this->generateCrossPlatformLinks();
    }

    /**
     * Generar enlaces cruzados para posts existentes con datos de música
     */
    private function generateCrossPlatformLinks()
    {
        // Esta función se ejecuta por si hay posts de música existentes
        // En una instalación nueva no hará nada
        $musicPosts = DB::table('posts')
            ->where('tipo', 'musica')
            ->get();

        foreach ($musicPosts as $post) {
            // Si tiene datos de iTunes, generar enlaces cruzados
            if (!empty($post->itunes_artist_name) && !empty($post->itunes_track_name)) {
                $this->updatePostWithCrossLinks($post->id, $post->itunes_artist_name, $post->itunes_track_name);
            }
        }
    }

    /**
     * Actualizar post con enlaces cruzados
     */
    private function updatePostWithCrossLinks($postId, $artist, $track)
    {
        // Limpiar términos de búsqueda
        $cleanArtist = $this->cleanSearchTerm($artist);
        $cleanTrack = $this->cleanSearchTerm($track);
        
        // Generar URLs de búsqueda
        $spotifySearchUrl = 'https://open.spotify.com/search/' . urlencode(trim($cleanArtist . ' ' . $cleanTrack));
        
        // Actualizar el post
        DB::table('posts')
            ->where('id', $postId)
            ->update([
                'artist_search_term' => $cleanArtist,
                'track_search_term' => $cleanTrack,
                'spotify_web_url' => $spotifySearchUrl,
                'music_source' => 'itunes'
            ]);
    }

    /**
     * Limpiar términos de búsqueda
     */
    private function cleanSearchTerm($term)
    {
        // Remover caracteres especiales y normalizar
        $cleaned = preg_replace('/[^\w\s\-]/', '', $term);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        return trim($cleaned);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'tipo',
                'itunes_track_id',
                'itunes_track_name',
                'itunes_artist_name',
                'itunes_collection_name',
                'itunes_artwork_url',
                'itunes_preview_url',
                'itunes_track_view_url',
                'itunes_track_time_millis',
                'itunes_country',
                'itunes_primary_genre_name',
                'music_source',
                'apple_music_url',
                'spotify_web_url',
                'artist_search_term',
                'track_search_term'
            ]);
        });
    }
};

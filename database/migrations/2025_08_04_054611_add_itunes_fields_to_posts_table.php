<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Post;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Campos de iTunes
            $table->string('itunes_track_id')->nullable()->after('spotify_external_url');
            $table->string('itunes_track_name')->nullable()->after('itunes_track_id');
            $table->string('itunes_artist_name')->nullable()->after('itunes_track_name');
            $table->string('itunes_collection_name')->nullable()->after('itunes_artist_name'); // Álbum
            $table->string('itunes_artwork_url')->nullable()->after('itunes_collection_name');
            $table->string('itunes_preview_url')->nullable()->after('itunes_artwork_url');
            $table->string('itunes_track_view_url')->nullable()->after('itunes_preview_url'); // Link a Apple Music
            $table->integer('itunes_track_time_millis')->nullable()->after('itunes_track_view_url'); // Duración
            $table->string('itunes_country')->nullable()->after('itunes_track_time_millis');
            $table->string('itunes_primary_genre_name')->nullable()->after('itunes_country');
            
            // Campo para indicar la fuente principal (itunes o spotify)
            $table->enum('music_source', ['itunes', 'spotify'])->default('itunes')->after('itunes_primary_genre_name');
        });

        // Generar enlaces cruzados para posts existentes con datos de Spotify
        $this->generateCrossPlatformLinks();
    }

    /**
     * Generar enlaces cruzados para posts existentes
     */
    private function generateCrossPlatformLinks()
    {
        // Buscar posts de música que tengan datos de Spotify
        $musicPosts = DB::table('posts')
            ->where('tipo', 'musica')
            ->whereNotNull('spotify_artist_name')
            ->whereNotNull('spotify_track_name')
            ->get();

        foreach ($musicPosts as $post) {
            // Limpiar términos de búsqueda
            $artist = $this->cleanSearchTerm($post->spotify_artist_name);
            $track = $this->cleanSearchTerm($post->spotify_track_name);
            
            // Generar URL de búsqueda para Spotify
            $spotifySearchUrl = 'https://open.spotify.com/search/' . urlencode(trim($artist . ' ' . $track));
            
            // Actualizar el post con los términos de búsqueda
            DB::table('posts')
                ->where('id', $post->id)
                ->update([
                    'artist_search_term' => $artist,
                    'track_search_term' => $track,
                    'spotify_web_url' => $spotifySearchUrl,
                    'music_source' => 'itunes' // Establecer como iTunes por defecto
                ]);
        }
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
                'music_source'
            ]);
        });
    }
};

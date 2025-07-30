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
            $table->enum('tipo', ['imagen', 'musica'])->default('imagen')->after('descripcion');
            $table->string('spotify_track_id')->nullable()->after('tipo');
            $table->string('spotify_track_name')->nullable()->after('spotify_track_id');
            $table->string('spotify_artist_name')->nullable()->after('spotify_track_name');
            $table->string('spotify_album_name')->nullable()->after('spotify_artist_name');
            $table->string('spotify_album_image')->nullable()->after('spotify_album_name');
            $table->string('spotify_preview_url')->nullable()->after('spotify_album_image');
            $table->string('spotify_external_url')->nullable()->after('spotify_preview_url');
            $table->string('dominant_color')->nullable()->after('spotify_external_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'tipo',
                'spotify_track_id',
                'spotify_track_name',
                'spotify_artist_name',
                'spotify_album_name',
                'spotify_album_image',
                'spotify_preview_url',
                'spotify_external_url',
                'dominant_color'
            ]);
        });
    }
};

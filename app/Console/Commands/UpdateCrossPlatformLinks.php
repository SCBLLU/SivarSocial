<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Services\CrossPlatformMusicService;

class UpdateCrossPlatformLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'music:update-cross-platform-links {--force : Force update all posts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing music posts with cross-platform links (Spotify/Apple Music)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating cross-platform music links...');

        $query = Post::where('tipo', 'musica');
        
        if (!$this->option('force')) {
            // Solo actualizar posts que no tienen enlaces cruzados
            $query->where(function($q) {
                $q->whereNull('apple_music_url')
                  ->orWhereNull('spotify_web_url');
            });
        }

        $posts = $query->get();
        
        if ($posts->isEmpty()) {
            $this->info('No music posts found to update.');
            return;
        }

        $this->info("Found {$posts->count()} posts to update.");
        
        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        $updated = 0;

        foreach ($posts as $post) {
            $wasUpdated = false;

            // Para posts de iTunes, generar enlace a Spotify
            if ($post->music_source === 'itunes' && $post->itunes_artist_name && $post->itunes_track_name) {
                $searchTerms = CrossPlatformMusicService::cleanSearchTerms(
                    $post->itunes_artist_name,
                    $post->itunes_track_name
                );
                
                $post->artist_search_term = $searchTerms['artist'];
                $post->track_search_term = $searchTerms['track'];
                $post->spotify_web_url = CrossPlatformMusicService::generateSpotifySearchUrl(
                    $searchTerms['artist'],
                    $searchTerms['track']
                );
                $wasUpdated = true;
            }
            
            // Para posts de Spotify, generar enlace a Apple Music
            if ($post->music_source === 'spotify' && $post->spotify_artist_name && $post->spotify_track_name) {
                $searchTerms = CrossPlatformMusicService::cleanSearchTerms(
                    $post->spotify_artist_name,
                    $post->spotify_track_name
                );
                
                $post->artist_search_term = $searchTerms['artist'];
                $post->track_search_term = $searchTerms['track'];
                $post->apple_music_url = CrossPlatformMusicService::generateAppleMusicSearchUrl(
                    $searchTerms['artist'],
                    $searchTerms['track']
                );
                $wasUpdated = true;
            }

            if ($wasUpdated) {
                $post->save();
                $updated++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Updated {$updated} posts with cross-platform links.");
    }
}

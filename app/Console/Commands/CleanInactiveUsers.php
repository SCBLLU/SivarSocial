<?php

namespace App\Console\Commands;

use App\Events\UserStatusChanged;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:clean-inactive {--minutes=5 : Minutes of inactivity before marking as offline}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark users as offline if they have been inactive for specified minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = (int) $this->option('minutes');
        $threshold = Carbon::now()->subMinutes($minutes);

        $this->info("Cleaning users inactive for more than {$minutes} minutes...");

        // Encontrar usuarios que están marcados como online pero llevan tiempo inactivos
        $inactiveUsers = User::where('is_online', true)
            ->where(function ($query) use ($threshold) {
                $query->whereNull('last_activity')
                    ->orWhere('last_activity', '<', $threshold);
            })
            ->get();

        if ($inactiveUsers->isEmpty()) {
            $this->info('No inactive users found.');
            return;
        }

        $count = 0;
        foreach ($inactiveUsers as $user) {
            try {
                $user->timestamps = false;
                $user->is_online = false;
                $user->last_seen = now();
                $user->save();
                $user->timestamps = true;

                // Disparar evento para notificar cambio de estado
                event(new UserStatusChanged($user, 'offline'));

                $count++;
            } catch (\Exception $e) {
                $this->error("Error updating user {$user->id}: " . $e->getMessage());
            }
        }

        $this->info("Marked {$count} users as offline.");
    }
}

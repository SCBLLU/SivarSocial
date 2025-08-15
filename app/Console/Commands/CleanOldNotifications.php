<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class CleanOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:clean {--days=30 : Number of days to keep notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old notifications older than specified days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');

        $this->info("Cleaning notifications older than {$days} days...");

        $notificationService = new NotificationService();
        $deletedCount = $notificationService->cleanOldNotifications($days);

        $this->info("Cleaned {$deletedCount} old notifications.");

        return Command::SUCCESS;
    }
}

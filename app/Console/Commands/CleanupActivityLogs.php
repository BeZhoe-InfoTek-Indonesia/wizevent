<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;

class CleanupActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:cleanup {--days=30 : Number of days to retain logs} {--force : Run without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old activity logs based on retention policy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $force = $this->option('force');

        if ($days < 1 || $days > 365) {
            $this->error('Days must be between 1 and 365.');

            return 1;
        }

        $cutoffDate = now()->subDays($days);
        $countToDelete = Activity::where('created_at', '<', $cutoffDate)->count();

        if ($countToDelete === 0) {
            $this->info("No activity logs older than {$days} days to delete.");

            return 0;
        }

        if (! $force) {
            $this->warn("This will delete {$countToDelete} activity logs older than {$days} days.");
            if (! $this->confirm('Do you wish to continue?')) {
                $this->info('Operation cancelled.');

                return 0;
            }
        }

        $this->info("Deleting activity logs older than {$days} days...");

        $deletedCount = Activity::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Successfully deleted {$deletedCount} activity logs.");

        // Log the cleanup operation
        activity()
            ->withProperties([
                'deleted_count' => $deletedCount,
                'retention_days' => $days,
                'cutoff_date' => $cutoffDate->toDateTimeString(),
                'event' => 'activity_cleanup',
            ])
            ->log('Automated activity log cleanup completed');

        return 0;
    }
}

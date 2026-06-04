<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class PurgeActivityLogs extends Command
{
    protected $signature   = 'logs:purge-activity {--hours=12 : Jam retensi log}';
    protected $description = 'Hapus permanen activity log yang lebih lama dari N jam (default 12 jam)';

    public function handle(): int
    {
        $hours   = (int) $this->option('hours');
        $cutoff  = now()->subHours($hours);

        $deleted = ActivityLog::where('created_at', '<', $cutoff)->delete();

        $this->info("✓ {$deleted} log dihapus (lebih lama dari {$hours} jam).");

        return self::SUCCESS;
    }
}
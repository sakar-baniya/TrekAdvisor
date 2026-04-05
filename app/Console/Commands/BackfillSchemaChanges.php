<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BackfillSchemaChanges extends Command
{
    protected $signature = 'app:backfill-schema-changes';

    protected $description = 'Backfill data for schema refactoring (approval_status)';

    public function handle(): void
    {
        $this->info('Starting schema backfill...');

        $pendingCount = User::query()->whereNull('approval_status')->update([
            'approval_status' => 'pending',
        ]);

        $this->line("  - {$pendingCount} users normalized");
        $this->info('Schema backfill completed.');
    }
}

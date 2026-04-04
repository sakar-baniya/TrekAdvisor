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

        $this->backfillApprovalStatus();

        $this->info('Schema backfill completed.');
    }

    protected function backfillApprovalStatus(): void
    {
        $this->info('Backfilling user approval_status...');

        $approvedCount = User::where('is_approved', true)
            ->update(['approval_status' => 'approved']);

        $rejectedCount = User::where('is_approved', false)
            ->update(['approval_status' => 'rejected']);

        $this->line("  - {$approvedCount} users marked as approved");
        $this->line("  - {$rejectedCount} users marked as rejected");
    }
}

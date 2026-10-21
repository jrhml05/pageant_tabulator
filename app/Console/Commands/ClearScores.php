<?php

namespace App\Console\Commands;

use App\Scoring\ScoreReset;
use Illuminate\Console\Command;

class ClearScores extends Command
{
    protected $signature = 'scores:clear {--force : Skip the confirmation}';

    protected $description = 'Delete every score, lock and finalist mark after a rehearsal (keeps candidates and accounts)';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Delete every score, lock and finalist mark? This cannot be undone.')) {
            return self::FAILURE;
        }

        ScoreReset::everything();

        $this->info('Scores cleared. Every segment is closed.');

        return self::SUCCESS;
    }
}

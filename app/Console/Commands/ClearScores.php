<?php

namespace App\Console\Commands;

use App\Models\Candidate;
use App\Models\OpenSegment;
use App\Models\Score;
use App\Models\ScoreLock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearScores extends Command
{
    protected $signature = 'scores:clear {--force : Skip the confirmation}';

    protected $description = 'Delete every score, lock and finalist mark after a rehearsal (keeps candidates and accounts)';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Delete every score, lock and finalist mark? This cannot be undone.')) {
            return self::FAILURE;
        }

        DB::transaction(function () {
            Score::query()->delete();
            ScoreLock::query()->delete();
            OpenSegment::query()->delete();
            Candidate::query()->update(['is_finalist' => false]);
        });

        $this->info('Scores cleared. Every segment is closed.');

        return self::SUCCESS;
    }
}

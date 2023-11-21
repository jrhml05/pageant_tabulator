<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Barangay;
use App\Models\FinalScore;
use Illuminate\Console\Command;

class FinalScoreGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missbinalonan:finalscoregenerator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate 5 candidates final null scores';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $barangays = Barangay::limit(5)->get();

        $judges = User::where('role','judge')->get();

        foreach ($judges as $judge) {
            foreach ($barangays as $barangay) {
                //initialize zero score to scores table based on stage, judges and barangays
                FinalScore::create([
                    'stage_id' => 4,
                    'judge_id' => $judge->id,
                    'barangay_id' => $barangay->id,
                    'candidate_id' => $barangay->id,
                ]);
            }
        }

        echo 'Done...';

    }
}

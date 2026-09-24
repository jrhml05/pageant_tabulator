<?php

namespace App\Http\Livewire\Judge\Preliminaries\Ms;

use App\Models\Ms_natlcost_score;
use App\Models\Ms_prelim_score;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NationalcostumeScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['confirmedLockInScores'];

    protected $rules = [
        'records.*.design' => 'required',
        'records.*.stage_presence' => 'required',
        'records.*.poise_bearing' => 'required',
        'records.*.overall_impact' => 'required',
    ];
    public function render()
    {
        return view('livewire.judge.preliminaries.ms.nationalcostume-score-board-component');
    }

    public function mount()
    {
        $this->records = Ms_natlcost_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }

    public function updatedRecords($value, $key)
    {
        // $key is "index.field": save only the candidate that was edited, not every row on each keystroke.
        $changed = $key === null ? $this->records : array_filter([$this->records[(int) strtok($key, '.')] ?? null]);

        foreach ($changed as $record) {

            Ms_natlcost_score::updateOrCreate(
                [
                    // 'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [   'design' => $record->design == '' ? null : $record->design,
                    'stage_presence' => $record->stage_presence == '' ? null : $record->stage_presence,
                    'poise_bearing' => $record->poise_bearing == '' ? null : $record->poise_bearing,
                    'overall_impact' => $record->overall_impact == '' ? null : $record->overall_impact,
                ]
            );

            $total = ((float) $record->design) + ((float) $record->stage_presence) + ((float) $record->poise_bearing) + ((float) $record->overall_impact);
            
            $national_costume = ($this->cal_percentage($total, 100) / 100) * 20;

            Ms_prelim_score::updateOrCreate(
                [
                    
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'national_costume' => $national_costume == '' ? null : $national_costume,
                ]
            );
        }
    }

    public function cal_percentage($num_amount, $num_total)
    {
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 2);
        return $count;
    }
    public function lockInscore()
    {
        $this->dispatch('swal:confirm',
            type: 'warning',
            message: 'Lock in your scores?',
            text: 'You will not be able to change them after this.'
        );
    }

    public function confirmedLockInScores()
    {
        $locked = 0;
        foreach ($this->records as $record) {
            if ($record->design === null || $record->stage_presence === null || $record->poise_bearing === null || $record->overall_impact === null ) {
                $this->dispatch('swal:modal',
                    type: 'warning',
                    message: 'Some scores are missing.',
                    text: 'Enter every score for every candidate, then lock in again.'
                );
                $locked = 0;
                break;
            } else {
                if (($record->design > 40 || $record->design < 0) || ($record->stage_presence > 30 || $record->stage_presence < 0) || ($record->poise_bearing > 20 || $record->poise_bearing < 0) || ($record->overall_impact > 10 || $record->overall_impact < 0)) {

                    $this->dispatch('swal:modal',
                        type: 'warning',
                        message: 'A score is out of range.',
                        text: 'Fix the fields marked in red, then lock in again.'
                    );
                    $locked = 0;
                    break;
                } else {
                    Ms_natlcost_score::updateOrCreate(
                        [
                            // 'id' => $record->id,
                            'candidate_id' => $record->candidate_id,
                            'judge_id' => Auth::user()->id,
                        ],
                        [
                            'is_lock' => 1
                        ]
                    );
                    $locked = 1;
                }
            }

            
        }
        if($locked == 1){
            return redirect()->route('judge.app.ms.prelim.score', $this->stage);
        }
        
    }
}

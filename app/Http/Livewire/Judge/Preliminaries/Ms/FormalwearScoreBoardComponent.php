<?php

namespace App\Http\Livewire\Judge\Preliminaries\Ms;

use App\Models\Ms_formalwear_score;
use App\Models\Ms_prelim_score;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormalwearScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['confirmedLockInScores'];

    protected $rules = [
        'records.*.beauty' => 'required',
        'records.*.stage_presence' => 'required',
        'records.*.design' => 'required',
        'records.*.overall_impact' => 'required',
    ];
    public function render()
    {
        return view('livewire.judge.preliminaries.ms.formalwear-score-board-component');
    }

    public function mount()
    {
        $this->records = Ms_formalwear_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }

    public function updatedRecords()
    {
        foreach ($this->records as $record) {

            Ms_formalwear_score::updateOrCreate(
                [
                    // 'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [   
                    'beauty' => $record->beauty == '' ? null : $record->beauty,
                    'stage_presence' => $record->stage_presence == '' ? null : $record->stage_presence,
                    'design' => $record->design == '' ? null : $record->design,
                    'overall_impact' => $record->overall_impact == '' ? null : $record->overall_impact,
                ]
            );

            $total = ((float) $record->beauty) + ((float) $record->stage_presence) + ((float) $record->design) + ((float) $record->overall_impact);
            
            $formal_wear = ($this->cal_percentage($total, 100) / 100) * 20;

            Ms_prelim_score::updateOrCreate(
                [
                    
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'formal_wear' => $formal_wear == '' ? null : $formal_wear,
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
            message: 'Are you sure you want to lock in the scores?',
            text: 'If yes, score fields will be disabled!'
        );
    }

    public function confirmedLockInScores()
    {
        $locked = 0;
        foreach ($this->records as $record) {
            if ($record->beauty === null || $record->stage_presence === null || $record->design === null || $record->overall_impact === null ) {
                $this->dispatch('swal:modal',
                    type: 'warning',
                    message: 'Fill out all Scores.',
                    text: '.'
                );
                $locked = 0;
                break;
            } else {
                if (($record->beauty > 40 || $record->beauty < 0) || ($record->stage_presence > 30 || $record->stage_presence < 0) || ($record->design > 20 || $record->design < 0) || ($record->overall_impact > 10 || $record->overall_impact < 0)) {

                    $this->dispatch('swal:modal',
                        type: 'warning',
                        message: 'Double Check your scores.',
                        text: '.'
                    );
                    $locked = 0;
                    break;
                } else {
                    Ms_formalwear_score::updateOrCreate(
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

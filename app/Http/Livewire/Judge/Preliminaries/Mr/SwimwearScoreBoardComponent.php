<?php

namespace App\Http\Livewire\Judge\Preliminaries\Mr;

use App\Models\Mr_prelim_score;
use Livewire\Component;
use App\Models\Mr_swimwear_score;
use Illuminate\Support\Facades\Auth;

class SwimwearScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['confirmedLockInScores'];

    protected $rules = [
        'records.*.body' => 'required',
        'records.*.poise' => 'required',
        'records.*.stage_presence' => 'required',
        'records.*.audience_impact' => 'required',
    ];

    public function mount()
    {
        $this->records = Mr_swimwear_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }
    public function render()
    {
        return view('livewire.judge.preliminaries.mr.swimwear-score-board-component');
    }

    public function alertConfirm()
    {
        $this->dispatch('swal:confirm',
            type: 'warning',
            message: 'Are you sure you want to save the scores?',
            text: 'If saved, the fields with scores will be disabled!'
        );
    }

    public function cal_percentage($num_amount, $num_total)
    {
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 2);
        return $count;
    }

    

    public function updatedRecords()
    {
        foreach ($this->records as $record) {

            Mr_swimwear_score::updateOrCreate(
                [
                    // 'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'poise' => $record->poise == '' ? null : $record->poise,
                    'body' => $record->body == '' ? null : $record->body,
                    'stage_presence' => $record->stage_presence == '' ? null : $record->stage_presence,
                    'audience_impact' => $record->audience_impact == '' ? null : $record->audience_impact,
                ]
            );

            $total = ((float) $record->poise) + ((float) $record->body) + ((float) $record->stage_presence) + ((float) $record->audience_impact);
            $swim_wear = ($this->cal_percentage($total, 100) / 100) * 20;

            Mr_prelim_score::updateOrCreate(
                [
                    // 'id' => $record->score_id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'swim_wear' => $swim_wear == '' ? null : $swim_wear,
                ]
            );
        }
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
            if ($record->body === null || $record->poise === null || $record->stage_presence === null || $record->audience_impact === null ) {
                $this->dispatch('swal:modal',
                    type: 'warning',
                    message: 'Fill out all Scores.',
                    text: '.'
                );
                $locked = 0;
                break;
            } else {
                if (($record->body > 40 || $record->body < 0) || ($record->poise > 30 || $record->poise < 0) || ($record->stage_presence > 20 || $record->stage_presence < 0) || ($record->audience_impact > 10 || $record->audience_impact < 0)) {

                    $this->dispatch('swal:modal',
                        type: 'warning',
                        message: 'Double Check your scores.',
                        text: '.'
                    );
                    $locked = 0;
                    break;
                } else {
                    Mr_swimwear_score::updateOrCreate(
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
            return redirect()->route('judge.app.mr.prelim.score', $this->stage);
        }
        
    }
}

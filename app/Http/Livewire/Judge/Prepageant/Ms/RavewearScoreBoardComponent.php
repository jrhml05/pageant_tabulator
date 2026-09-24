<?php

namespace App\Http\Livewire\Judge\Prepageant\Ms;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Ms_ravewear_score;
use App\Models\Ms_prepageant_score;

class RavewearScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['confirmedLockInScores'];

    protected $rules = [
        'records.*.style' => 'required',
        'records.*.creativity' => 'required',
        'records.*.functionality' => 'required',
        'records.*.audience_impact' => 'required',
    ];
    public function render()
    {
        return view('livewire.judge.prepageant.ms.ravewear-score-board-component');
    }

    public function mount()
    {
        $this->records = Ms_ravewear_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }

    public function updatedRecords()
    {
        foreach ($this->records as $record) {

            Ms_ravewear_score::updateOrCreate(
                [
                    // 'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [   'style' => $record->style == '' ? null : $record->style,
                    'creativity' => $record->creativity == '' ? null : $record->creativity,
                    'functionality' => $record->functionality == '' ? null : $record->functionality,
                    'audience_impact' => $record->audience_impact == '' ? null : $record->audience_impact,
                ]
            );

            $total = ((float) $record->style) + ((float) $record->creativity) + ((float) $record->functionality) + ((float) $record->audience_impact);
            
            $rave_wear = ($this->cal_percentage($total, 100) / 100) * 50;

            Ms_prepageant_score::updateOrCreate(
                [
                    
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'rave_wear' => $rave_wear == '' ? null : $rave_wear,
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
            if ($record->style === null || $record->creativity === null || $record->functionality === null || $record->audience_impact === null ) {
                $this->dispatch('swal:modal',
                    type: 'warning',
                    message: 'Fill out all Scores.',
                    text: '.'
                );
                $locked = 0;
                break;
            } else {
                if (($record->style > 40 || $record->style < 0) || ($record->creativity > 30 || $record->creativity < 0) || ($record->functionality > 20 || $record->functionality < 0) || ($record->audience_impact > 10 || $record->audience_impact < 0)) {

                    $this->dispatch('swal:modal',
                        type: 'warning',
                        message: 'Double Check your scores.',
                        text: '.'
                    );
                    $locked = 0;
                    break;
                } else {
                    Ms_ravewear_score::updateOrCreate(
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
            return redirect()->route('judge.app.ms.score', $this->stage);
        }
        
    }
}

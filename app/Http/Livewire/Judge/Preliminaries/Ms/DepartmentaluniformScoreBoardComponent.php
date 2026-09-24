<?php

namespace App\Http\Livewire\Judge\Preliminaries\Ms;

use App\Models\Ms_deptuni_score;
use App\Models\Ms_prelim_score;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DepartmentaluniformScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['confirmedLockInScores'];

    protected $rules = [
        'records.*.presentation' => 'required',
        'records.*.figure' => 'required',
        'records.*.beauty_poise' => 'required',
        'records.*.overall_impact' => 'required',
    ];
    public function render()
    {
        return view('livewire.judge.preliminaries.ms.departmentaluniform-score-board-component');
    }

    public function mount()
    {
        $this->records = Ms_deptuni_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }

    public function updatedRecords()
    {
        foreach ($this->records as $record) {

            Ms_deptuni_score::updateOrCreate(
                [
                    // 'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [   
                    'presentation' => $record->presentation == '' ? null : $record->presentation,
                    'figure' => $record->figure == '' ? null : $record->figure,
                    'beauty_poise' => $record->beauty_poise == '' ? null : $record->beauty_poise,
                    'overall_impact' => $record->overall_impact == '' ? null : $record->overall_impact,
                ]
            );

            $total = ((float) $record->presentation) + ((float) $record->figure) + ((float) $record->beauty_poise) + ((float) $record->overall_impact);
            
            $dept_uniform = ($this->cal_percentage($total, 100) / 100) * 20;

            Ms_prelim_score::updateOrCreate(
                [
                    
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'dept_uniform' => $dept_uniform == '' ? null : $dept_uniform,
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
            if ($record->presentation === null || $record->figure === null || $record->beauty_poise === null || $record->overall_impact === null ) {
                $this->dispatch('swal:modal',
                    type: 'warning',
                    message: 'Fill out all Scores.',
                    text: '.'
                );
                $locked = 0;
                break;
            } else {
                if (($record->presentation > 40 || $record->presentation < 0) || ($record->figure > 30 || $record->figure < 0) || ($record->beauty_poise > 20 || $record->beauty_poise < 0) || ($record->overall_impact > 10 || $record->overall_impact < 0)) {

                    $this->dispatch('swal:modal',
                        type: 'warning',
                        message: 'Double Check your scores.',
                        text: '.'
                    );
                    $locked = 0;
                    break;
                } else {
                    Ms_deptuni_score::updateOrCreate(
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

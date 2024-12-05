<?php

namespace App\Http\Livewire\Judge\Final\Ms;

use Livewire\Component;
use App\Models\Ms_final_score;

use Illuminate\Support\Facades\Auth;

class ScoreBoardComponent extends Component
{

    public $stage;
    public $records;
    protected $listeners = ['confirmedLockInScores'];

    protected $rules = [
        'records.*.wit' => 'required',
        'records.*.projection' => 'required',
        'records.*.stage_presence' => 'required',
        'records.*.overall_impact' => 'required',
    ];

    public function render()
    {
        return view('livewire.judge.final.ms.score-board-component');
    }

    public function mount()
    {
        $this->records = Ms_final_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
        // dd($this->records);
    }

    public function updatedRecords()
    {
        foreach ($this->records as $record) {

            if (!$record->is_lock) {
                Ms_final_score::updateOrCreate(
                    [
                        // 'id' => $record->id,
                        'candidate_id' => $record->candidate_id,
                        'judge_id' => Auth::user()->id,
                    ],
                    [
                        'wit' => $record->wit == '' ? null : $record->wit,
                        'projection' => $record->projection == '' ? null : $record->projection,
                        'stage_presence' => $record->stage_presence == '' ? null : $record->stage_presence,
                        'overall_impact' => $record->overall_impact == '' ? null : $record->overall_impact,
                    ]
                );
            }
        }
    }

    public function lockInscore()
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'message' => 'Are you sure you want to lock in the scores?',
            'text' => 'If yes, score fields will be disabled!'
        ]);
    }

    public function confirmedLockInScores()
    {
        $locked = 0;
        foreach ($this->records as $record) {
            if ($record->wit === null || $record->projection === null || $record->stage_presence === null || $record->overall_impact === null ) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'warning',
                    'message' => 'Fill out all Scores.',
                    'text' => '.'
                ]);
                $locked = 0;
                break;
            } else {
                if (($record->wit > 40 || $record->wit < 0) || ($record->projection > 30 || $record->projection < 0) || ($record->stage_presence > 20 || $record->stage_presence < 0) || ($record->overall_impact > 10 || $record->overall_impact < 0)) {

                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'warning',
                        'message' => 'Double Check your scores.',
                        'text' => '.'
                    ]);
                    $locked = 0;
                    break;
                } else {
                    Ms_final_score::updateOrCreate(
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
            return redirect()->route('judge.app.ms.final.score', $this->stage);
        }
        
    }

}

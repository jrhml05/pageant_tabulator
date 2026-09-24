<?php

namespace App\Http\Livewire\Judge\Preliminaries\Mr;

use App\Models\Mr_prelim_score;
use App\Models\Mr_qna_score;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QnaScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['confirmedLockInScores'];

    protected $rules = [
        'records.*.relevance' => 'required',
        'records.*.delivery' => 'required',
        'records.*.content' => 'required',
        'records.*.audience_impact' => 'required',
    ];
    public function render()
    {
        return view('livewire.judge.preliminaries.mr.qna-score-board-component');
    }

    public function mount()
    {
        $this->records = Mr_qna_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }

    public function updatedRecords()
    {
        foreach ($this->records as $record) {

            Mr_qna_score::updateOrCreate(
                [
                    // 'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [   
                    'relevance' => $record->relevance == '' ? null : $record->relevance,
                    'delivery' => $record->delivery == '' ? null : $record->delivery,
                    'content' => $record->content == '' ? null : $record->content,
                    'audience_impact' => $record->audience_impact == '' ? null : $record->audience_impact,
                ]
            );

            $total = ((float) $record->relevance) + ((float) $record->delivery) + ((float) $record->content) + ((float) $record->audience_impact);
            
            $qna = ($this->cal_percentage($total, 100) / 100) * 20;

            Mr_prelim_score::updateOrCreate(
                [
                    
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'qna' => $qna == '' ? null : $qna,
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
            if ($record->relevance === null || $record->delivery === null || $record->content === null || $record->audience_impact === null ) {
                $this->dispatch('swal:modal',
                    type: 'warning',
                    message: 'Fill out all Scores.',
                    text: '.'
                );
                $locked = 0;
                break;
            } else {
                if (($record->relevance > 40 || $record->relevance < 0) || ($record->delivery > 20 || $record->delivery < 0) || ($record->content > 30 || $record->content < 0) || ($record->audience_impact > 10 || $record->audience_impact < 0)) {

                    $this->dispatch('swal:modal',
                        type: 'warning',
                        message: 'Double Check your scores.',
                        text: '.'
                    );
                    $locked = 0;
                    break;
                } else {
                    Mr_qna_score::updateOrCreate(
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

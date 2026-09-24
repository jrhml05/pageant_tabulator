<?php

namespace App\Http\Livewire\Judge\Prepageant\Mr;

use App\Models\Mr_prepageant_score;
use Livewire\Component;
use App\Models\Mr_talent_score;
use Illuminate\Support\Facades\Auth;

class TalentScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['confirmedLockInScores'];

    protected $rules = [
        'records.*.mastery' => 'required',
        'records.*.uniqueness' => 'required',
        'records.*.stage_presence' => 'required',
        'records.*.audience_impact' => 'required',
    ];

    public function mount()
    {
        $this->records = Mr_talent_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }
    public function render()
    {
        return view('livewire.judge.prepageant.mr.talent-score-board-component');
    }


    public function cal_percentage($num_amount, $num_total)
    {
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 2);
        return $count;
    }

    public function updatedRecords($value, $key)
    {
        // $key is "index.field": save only the candidate that was edited, not every row on each keystroke.
        $changed = $key === null ? $this->records : array_filter([$this->records[(int) strtok($key, '.')] ?? null]);

        foreach ($changed as $record) {

            Mr_talent_score::updateOrCreate(
                [
                    // 'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'mastery' => $record->mastery == '' ? null : $record->mastery,
                    'uniqueness' => $record->uniqueness == '' ? null : $record->uniqueness,
                    'stage_presence' => $record->stage_presence == '' ? null : $record->stage_presence,
                    'audience_impact' => $record->audience_impact == '' ? null : $record->audience_impact,
                ]
            );

            $total = ((float) $record->mastery) + ((float) $record->uniqueness) + ((float) $record->stage_presence) + ((float) $record->audience_impact);
            $talent = ($this->cal_percentage($total, 100) / 100) * 50;

            Mr_prepageant_score::updateOrCreate(
                [
                    // 'id' => $record->score_id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'talent' => $talent == '' ? null : $talent,
                ]
            );
        }
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
            if ($record->mastery === null || $record->uniqueness === null || $record->stage_presence === null || $record->audience_impact === null ) {
                $this->dispatch('swal:modal',
                    type: 'warning',
                    message: 'Some scores are missing.',
                    text: 'Enter every score for every candidate, then lock in again.'
                );
                $locked = 0;
                break;
            } else {
                if (($record->mastery > 40 || $record->mastery < 0) || ($record->uniqueness > 30 || $record->uniqueness < 0) || ($record->stage_presence > 20 || $record->stage_presence < 0) || ($record->audience_impact > 10 || $record->audience_impact < 0)) {

                    $this->dispatch('swal:modal',
                        type: 'warning',
                        message: 'A score is out of range.',
                        text: 'Fix the fields marked in red, then lock in again.'
                    );
                    $locked = 0;
                    break;
                } else {
                    Mr_talent_score::updateOrCreate(
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
            return redirect()->route('judge.app.mr.score', $this->stage);
        }
        
    }
}

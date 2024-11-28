<?php

namespace App\Http\Livewire\Judge\Prepageant\Ms;

use App\Models\Ms_prepageant_score;
use Livewire\Component;
use App\Models\Ms_talent_score;
use Illuminate\Support\Facades\Auth;

class TalentScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['save'];

    protected $rules = [
        'records.*.mastery' => 'required',
        'records.*.uniqueness' => 'required',
        'records.*.stage_presence' => 'required',
        'records.*.audience_impact' => 'required',
    ];

    public function mount()
    {
        $this->records = Ms_talent_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }
    public function render()
    {
        return view('livewire.judge.prepageant.ms.talent-score-board-component');
    }

    public function alertConfirm()
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'message' => 'Are you sure you want to save the scores?',
            'text' => 'If saved, the fields with scores will be disabled!'
        ]);
    }

    public function cal_percentage($num_amount, $num_total)
    {
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 2);
        return $count;
    }

    public function save()
    {
        foreach ($this->records as $record) {

            Ms_talent_score::updateOrCreate(
                [
                    'id' => $record->id,
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

            Ms_prepageant_score::updateOrCreate(
                [
                    'id' => $record->score_id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'talent' => $talent == '' ? null : $talent,
                ]
            );
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Scores has been saved successfully!',
            'text' => '.'
        ]);

        return redirect()->route('judge.app.ms.score', $this->stage);
    }

    public function updatedRecords()
    {
        foreach ($this->records as $record) {

            Ms_talent_score::updateOrCreate(
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

            Ms_prepageant_score::updateOrCreate(
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
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'message' => 'Are you sure you want to lock in the scores?',
            'text' => 'If yes, score fields will be disabled!'
        ]);
    }

    public function confirmedLockInScores()
    {
        foreach ($this->records as $record) {
            if ($record->mastery === null || $record->uniqueness === null || $record->stage_presence === null || $record->audience_impact === null) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'warning',
                    'message' => 'Fill out all Scores.',
                    'text' => '.'
                ]);
                break;
            } else {
                if ($record->mastery > 40 || $record->uniqueness > 30 || $record->stage_presence > 20 || $record->audience_impact > 10) {

                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'warning',
                        'message' => 'Fill out all Scores.',
                        'text' => '.'
                    ]);
                    break;
                } else {
                    Ms_talent_score::updateOrCreate(
                        [
                            'id' => $record->id,
                            'candidate_id' => $record->candidate_id,
                            'judge_id' => Auth::user()->id,
                        ],
                        [
                            'is_lock' => 1
                        ]
                    );
                }
            }
        }
        return redirect()->route('judge.prepageant.ms.score', $this->stage);
    }
}

<?php

namespace App\Http\Livewire\Judge;

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
        'records.*.execution' => 'required',
        'records.*.originality' => 'required',
        'records.*.stage_presence' => 'required',
    ];

    public function mount()
    {
        $this->records = Ms_talent_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }
    public function render()
    {
        return view('livewire.judge.talent-score-board-component');
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
                    'execution' => $record->execution == '' ? null : $record->execution,
                    'originality' => $record->originality == '' ? null : $record->originality,
                    'stage_presence' => $record->stage_presence == '' ? null : $record->stage_presence,
                ]
            );

            $total = ((float) $record->execution) + ((float) $record->originality) + ((float) $record->stage_presence);
            $talent = ($this->cal_percentage($total, 100) / 100) * 40;

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

        return redirect()->route('judge.app.score', $this->stage);
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
                    'execution' => $record->execution == '' ? null : $record->execution,
                    'originality' => $record->originality == '' ? null : $record->originality,
                    'stage_presence' => $record->stage_presence == '' ? null : $record->stage_presence,
                ]
            );

            $total = ((float) $record->execution) + ((float) $record->originality) + ((float) $record->stage_presence);
            $talent = ($this->cal_percentage($total, 100) / 100) * 40;

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
}

<?php

namespace App\Http\Livewire\Judge;

use App\Models\Score;
use Livewire\Component;
use App\Models\SubScore;
use Illuminate\Support\Facades\Auth;

class TalentScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['save'];

    protected $rules = [
        'records.*.mastery_and_execution' => 'required',
        'records.*.originality' => 'required',
        'records.*.audience_impact' => 'required',
    ];

    public function mount()
    {
        $this->records = SubScore::where('stage_id', $this->stage)
                    ->where('judge_id', Auth::user()->id)
                    ->orderBy('barangay_id', 'ASC')
                    ->get();
            // dd($this->records);
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

    public function cal_percentage($num_amount, $num_total) {
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 2);
        return $count;
    }

    public function save()
    {
        foreach ($this->records as $record) {

            SubScore::updateOrCreate(
                [
                    'id' => $record->id,
                    'score_id' => $record->score_id,
                    'stage_id' => $this->stage,
                    'barangay_id' => $record->barangay_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'mastery_and_execution' => $record->mastery_and_execution == '' ? null : $record->mastery_and_execution,
                    'originality' => $record->originality == '' ? null : $record->originality,
                    'audience_impact' => $record->audience_impact == '' ? null : $record->audience_impact,
                ]
            );

            $total = ((float) $record->mastery_and_execution) + ((float) $record->originality) + ((float) $record->audience_impact);
            $talent = ($this->cal_percentage($total, 100) / 100) * 20;

            Score::updateOrCreate(
                [
                    'id' => $record->score_id,
                    'stage_id' => $this->stage,
                    'barangay_id' => $record->barangay_id,
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

            SubScore::updateOrCreate(
                [
                    'id' => $record->id,
                    'score_id' => $record->score_id,
                    'stage_id' => $this->stage,
                    'barangay_id' => $record->barangay_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'mastery_and_execution' => $record->mastery_and_execution == '' ? null : $record->mastery_and_execution,
                    'originality' => $record->originality == '' ? null : $record->originality,
                    'audience_impact' => $record->audience_impact == '' ? null : $record->audience_impact,
                ]
            );

            $total = ((float) $record->mastery_and_execution) + ((float) $record->originality) + ((float) $record->audience_impact);
            $talent = ($this->cal_percentage($total, 100) / 100) * 20;

            Score::updateOrCreate(
                [
                    'id' => $record->score_id,
                    'stage_id' => $this->stage,
                    'barangay_id' => $record->barangay_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'talent' => $talent == '' ? null : $talent,
                ]
            );

        }
    }
}

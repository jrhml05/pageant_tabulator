<?php

namespace App\Http\Livewire\Judge\Preliminaries\Mr;

use App\Models\Mr_prelim_score;
use Livewire\Component;
use App\Models\Mr_casualwear_score;
use Illuminate\Support\Facades\Auth;

class CasualwearScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['save'];

    protected $rules = [
        'records.*.poise' => 'required',
        'records.*.execution' => 'required',
        'records.*.appearance' => 'required',
    ];

    public function mount()
    {
        $this->records = Mr_casualwear_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }
    public function render()
    {
        return view('livewire.judge.preliminaries.mr.casualwear-score-board-component');
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

            Mr_casualwear_score::updateOrCreate(
                [
                    'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'execution' => $record->execution == '' ? null : $record->execution,
                    'poise' => $record->poise == '' ? null : $record->poise,
                    'appearance' => $record->appearance == '' ? null : $record->appearance,
                ]
            );

            $total = ((float) $record->execution) + ((float) $record->poise) + ((float) $record->appearance);
            $casual_wear = ($this->cal_percentage($total, 100) / 100) * 50;

            Mr_prelim_score::updateOrCreate(
                [
                    'id' => $record->score_id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'casual_wear' => $casual_wear == '' ? null : $casual_wear,
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

            Mr_casualwear_score::updateOrCreate(
                [
                    // 'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'execution' => $record->execution == '' ? null : $record->execution,
                    'poise' => $record->poise == '' ? null : $record->poise,
                    'appearance' => $record->appearance == '' ? null : $record->appearance,
                ]
            );

            $total = ((float) $record->execution) + ((float) $record->poise) + ((float) $record->appearance);
            $casual_wear = ($this->cal_percentage($total, 100) / 100) * 50;

            Mr_prelim_score::updateOrCreate(
                [
                    // 'id' => $record->score_id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'casual_wear' => $casual_wear == '' ? null : $casual_wear,
                ]
            );
        }
    }
}

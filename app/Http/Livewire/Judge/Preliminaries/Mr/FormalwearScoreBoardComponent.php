<?php

namespace App\Http\Livewire\Judge\Preliminaries\Mr;

use App\Models\Mr_prelim_score;
use Livewire\Component;
use App\Models\Mr_formalwear_score;
use Illuminate\Support\Facades\Auth;

class FormalwearScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['save'];

    protected $rules = [
        'records.*.elegance' => 'required',
        'records.*.presence' => 'required',
        'records.*.projection' => 'required',
        'records.*.poise' => 'required',
    ];

    public function mount()
    {
        $this->records = Mr_formalwear_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }
    public function render()
    {
        return view('livewire.judge.preliminaries.mr.formalwear-score-board-component');
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

            Mr_formalwear_score::updateOrCreate(
                [
                    'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'elegance' => $record->elegance == '' ? null : $record->elegance,
                    'presence' => $record->presence == '' ? null : $record->presence,
                    'projection' => $record->projection == '' ? null : $record->projection,
                    'poise' => $record->poise == '' ? null : $record->poise,
                ]
            );

            $total = ((float) $record->elegance) + ((float) $record->presence) + ((float) $record->projection) + ((float) $record->poise);
            $formal_wear = ($this->cal_percentage($total, 100) / 100) * 50;

            Mr_prelim_score::updateOrCreate(
                [
                    'id' => $record->score_id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'formal_wear' => $formal_wear == '' ? null : $formal_wear,
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

            Mr_formalwear_score::updateOrCreate(
                [
                    // 'id' => $record->id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'elegance' => $record->elegance == '' ? null : $record->elegance,
                    'presence' => $record->presence == '' ? null : $record->presence,
                    'projection' => $record->projection == '' ? null : $record->projection,
                    'poise' => $record->poise == '' ? null : $record->poise,
                ]
            );

            $total = ((float) $record->elegance) + ((float) $record->presence) + ((float) $record->projection) + ((float) $record->poise);
            $formal_wear = ($this->cal_percentage($total, 100) / 100) * 50;

            Mr_prelim_score::updateOrCreate(
                [
                    // 'id' => $record->score_id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'formal_wear' => $formal_wear == '' ? null : $formal_wear,
                ]
            );
        }
    }
}

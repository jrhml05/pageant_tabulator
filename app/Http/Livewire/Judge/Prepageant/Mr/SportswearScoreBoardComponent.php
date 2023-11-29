<?php

namespace App\Http\Livewire\Judge\Prepageant\Mr;

use App\Models\Mr_prepageant_score;
use Livewire\Component;
use App\Models\Mr_sportswear_score;
use Illuminate\Support\Facades\Auth;

class SportswearScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['save'];

    protected $rules = [
        'records.*.execution' => 'required',
        'records.*.poise' => 'required',
        'records.*.appearance' => 'required',
    ];

    public function mount()
    {
        $this->records = Mr_sportswear_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }
    public function render()
    {
        return view('livewire.judge.prepageant.mr.sportswear-score-board-component');
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

            Mr_sportswear_score::updateOrCreate(
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
            $sports_wear = ($this->cal_percentage($total, 100) / 100) * 30;

            Mr_prepageant_score::updateOrCreate(
                [
                    'id' => $record->score_id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'sports_wear' => $sports_wear == '' ? null : $sports_wear,
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

            Mr_sportswear_score::updateOrCreate(
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
            $sports_wear = ($this->cal_percentage($total, 100) / 100) * 30;

            Mr_prepageant_score::updateOrCreate(
                [
                    // 'id' => $record->score_id,
                    'candidate_id' => $record->candidate_id,
                    'judge_id' => Auth::user()->id,
                ],
                [
                    'sports_wear' => $sports_wear == '' ? null : $sports_wear,
                ]
            );
        }
    }
}

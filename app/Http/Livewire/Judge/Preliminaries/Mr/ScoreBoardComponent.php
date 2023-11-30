<?php

namespace App\Http\Livewire\Judge\Preliminaries\Mr;

use Livewire\Component;
use App\Models\Mr_prelim_score;

use Illuminate\Support\Facades\Auth;

class ScoreBoardComponent extends Component
{

    public $stage;
    public $records;
    protected $listeners = ['save'];

    protected $rules = [
        'records.*.casual_wear' => 'required',
        'records.*.formal_wear' => 'required',
    ];

    public function render()
    {
        return view('livewire.judge.preliminaries.mr.score-board-component');
    }

    public function mount()
    {
        $this->records = Mr_prelim_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
        // dd($this->records);
    }

    public function alertConfirm()
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'message' => 'Are you sure you want to save the scores?',
            'text' => 'If saved, the fields with scores will be disabled!'
        ]);
    }

    public function save()
    {
        foreach ($this->records as $record) {

            if (!$record->is_lock) {
                $saveScore = Mr_prelim_score::updateOrCreate(
                    [
                        'id' => $record->id,
                        'stage_id' => $this->stage,
                        'barangay_id' => $record->barangay_id,
                        'judge_id' => Auth::user()->id,
                    ],
                    [
                        'poise' => $record->poise == '' ? null : $record->poise,
                        'beauty' => $record->beauty == '' ? null : $record->beauty,
                        'swimsuit' => $record->swimsuit == '' ? null : $record->swimsuit,
                        'evening_gown' => $record->evening_gown == '' ? null : $record->evening_gown,
                    ]
                );

                if ($saveScore) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'message' => 'Scores has been saved successfully!',
                        'text' => '.',
                        'button' => false,
                    ]);
                }
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'message' => 'Sorry, Score Board has already been locked!',
                    'text' => 'Try to communicate with the tabulator team.',
                    'button' => true,

                ]);
            }
        }
    }

    public function updatedRecords()
    {
        foreach ($this->records as $record) {

            if (!$record->is_lock) {
                Mr_prelim_score::updateOrCreate(
                    [
                        'id' => $record->id,
                        'stage_id' => $this->stage,
                        'barangay_id' => $record->barangay_id,
                        'judge_id' => Auth::user()->id,
                    ],
                    [
                        'poise' => $record->poise == '' ? null : $record->poise,
                        'beauty' => $record->beauty == '' ? null : $record->beauty,
                        'swimsuit' => $record->swimsuit == '' ? null : $record->swimsuit,
                        'evening_gown' => $record->evening_gown == '' ? null : $record->evening_gown,
                    ]
                );
            }
        }
    }
}

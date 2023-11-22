<?php

namespace App\Http\Livewire\Judge\Prepageant\Ms;

use App\Models\Ms_prepageant_score;
use Livewire\Component;

use Illuminate\Support\Facades\Auth;

class ScoreBoardComponent extends Component
{
    public $stage;
    public $records;
    protected $listeners = ['save'];

    protected $rules = [
        'records.*.beauty' => 'required',
        'records.*.poise' => 'required',
        'records.*.intelligence' => 'required',
        'records.*.talent' => 'required',
    ];

    public function mount()
    {
        $this->records = Ms_prepageant_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
        // dd($this->records);
    }

    public function render()
    {
        return view('livewire.judge.prepageant.ms.score-board-component');
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
                $saveScore = Ms_prepageant_score::updateOrCreate(
                    [
                        'id' => $record->id,
                        'stage_id' => $this->stage,
                        'barangay_id' => $record->barangay_id,
                        'judge_id' => Auth::user()->id,
                    ],
                    [
                        'poise' => $record->poise == '' ? null : $record->poise,
                        'beauty' => $record->beauty == '' ? null : $record->beauty,
                        'intelligence' => $record->intelligence == '' ? null : $record->intelligence,
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
                Score::updateOrCreate(
                    [
                        'id' => $record->id,
                        'stage_id' => $this->stage,
                        'barangay_id' => $record->barangay_id,
                        'judge_id' => Auth::user()->id,
                    ],
                    [
                        'poise' => $record->poise == '' ? null : $record->poise,
                        'beauty' => $record->beauty == '' ? null : $record->beauty,
                        'intelligence' => $record->intelligence == '' ? null : $record->intelligence,
                    ]
                );
            }
        }
    }
}

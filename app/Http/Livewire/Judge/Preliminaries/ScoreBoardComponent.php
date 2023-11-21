<?php

namespace App\Http\Livewire\Judge\Preliminaries;

use Livewire\Component;
use App\Models\PrelimScore;

use Illuminate\Support\Facades\Auth;

class ScoreBoardComponent extends Component
{

    public $stage;
    public $records;
    protected $listeners = ['save'];

    protected $rules = [
        'records.*.beauty' => 'required',
        'records.*.poise' => 'required',
        'records.*.swimsuit' => 'required',
        'records.*.evening_gown' => 'required',
    ];

    public function render()
    {
        return view('livewire.judge.preliminaries.score-board-component');
    }

    public function mount()
    {
        $this->records = PrelimScore::where('stage_id', $this->stage)
            ->where('judge_id', Auth::user()->id)
            ->orderBy('barangay_id', 'ASC')
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
                $saveScore = PrelimScore::updateOrCreate(
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
                PrelimScore::updateOrCreate(
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

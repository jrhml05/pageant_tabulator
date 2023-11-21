<?php

namespace App\Http\Livewire\Judge;

use Livewire\Component;
use App\Models\FinalScore;
use Illuminate\Support\Facades\Auth;

class FinalScoreBoardComponent extends Component
{
    // public $stage;
    public $records;
    protected $listeners = ['lock'];

    public $score_lock = false;

    protected $rules = [
        'records.*.intelligence' => 'required',
        'records.*.beauty' => 'required',
    ];

    public function mount()
    {
        $this->records = FinalScore::where('stage_id', 4)
            ->where('judge_id', Auth::user()->id)
            ->orderBy('barangay_id', 'ASC')
            ->get();

        $is_lock = FinalScore::where('stage_id', 4)
        ->where('judge_id', Auth::user()->id)->first()->is_lock;

        if($is_lock) {
            $this->score_lock = true;
        }else{
            $this->score_lock = false;
        }
        // dd($this->records);
    }

    public function render()
    {
        return view('livewire.judge.final-score-board-component');
    }

    public function alertConfirm($param)
    {
        if($param == 1) {
            $this->dispatchBrowserEvent('swal:confirm', [
                'type' => 'info',
                'message' => 'Are you sure you want to lock in the scores?',
                'text' => 'If OK, the fields with scores will be disabled!'
            ]);
        }else{
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'warning',
                'message' => 'To request unlock, contact our tabulator staff.',
                'text' => '.',
                'button' => true,

            ]);
        }

    }

    public function lock()
    {
        foreach ($this->records as $record) {

            if (!$record->is_lock) {
                $lockScore = FinalScore::updateOrCreate(
                    [
                        'id' => $record->id,
                        'stage_id' => 4,
                        'barangay_id' => $record->barangay_id,
                        'judge_id' => Auth::user()->id,
                    ],
                    [
                        'is_lock' => 1,
                    ]
                );

                if ($lockScore) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'message' => 'Scores has been lock successfully!',
                        'text' => '.',
                        'button' => false,
                    ]);

                    $this->score_lock = true;
                }
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'message' => 'Sorry, Score Board has already been locked!',
                    'text' => 'Try to communicate with the tabulator team.',
                    'button' => true,

                ]);

                $this->score_lock = false;
            }

            $this->mount();
        }
    }

    public function updatedRecords()
    {
        foreach ($this->records as $record) {

            if (!$record->is_lock) {
                FinalScore::updateOrCreate(
                    [
                        'id' => $record->id,
                        'stage_id' => 4,
                        'barangay_id' => $record->barangay_id,
                        'judge_id' => Auth::user()->id,
                    ],
                    [
                        'beauty' => $record->beauty == '' ? null : $record->beauty,
                        'intelligence' => $record->intelligence == '' ? null : $record->intelligence,
                    ]
                );
            }
        }
    }
}

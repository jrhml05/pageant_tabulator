<?php

namespace App\Http\Livewire\Judge\Preliminaries\Mr;

use Livewire\Component;
use App\Models\Mr_prelim_score;

use Illuminate\Support\Facades\Auth;

class ScoreBoardComponent extends Component
{

    public $stage;
    public $records;

    protected $rules = [
        'records.*.national_costume' => 'required',
        'records.*.dept_uniform' => 'required',
        'records.*.swim_wear' => 'required',
        'records.*.formal_wear' => 'required',
        'records.*.qna' => 'required',
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
}

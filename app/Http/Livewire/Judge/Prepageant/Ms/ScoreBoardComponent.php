<?php

namespace App\Http\Livewire\Judge\Prepageant\Ms;

use App\Models\Ms_prepageant_score;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ScoreBoardComponent extends Component
{
    public $stage;

    public $records;

    protected $rules = [
        'records.*.rave_wear' => 'required',
        'records.*.talent' => 'required',
    ];

    public function mount()
    {
        $this->records = Ms_prepageant_score::where('judge_id', Auth::user()->id)
            ->orderBy('candidate_id', 'ASC')
            ->get();
    }

    public function render()
    {
        return view('livewire.judge.prepageant.ms.score-board-component');
    }
}

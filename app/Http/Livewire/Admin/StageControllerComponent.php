<?php

namespace App\Http\Livewire\Admin;

use App\Models\Stage;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class StageControllerComponent extends Component
{
    public $stages;
    protected $rules = [
        'stages.*.stage' => 'required',
    ];
    public function mount()
    {
        $this->stages = Stage::all();
    }
    public function render()
    {
        return view('livewire.admin.stage-controller-component');
    }

    public function updatedStages()
    {
        DB::table('stages')->update([
            'is_active' => false
        ]);
        // foreach ($this->stages as $stage) {

        //     Stage::updateOrCreate(
        //         [
        //             'id' => $stage->id,
        //         ],
        //         [
        //             'is_active' => true
        //         ]
        //     );

        // }
    }
}

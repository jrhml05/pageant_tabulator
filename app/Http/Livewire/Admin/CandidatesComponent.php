<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Barangay;
use App\Models\Candidate;

class CandidatesComponent extends Component
{
    public $first_name;
    public $middle_name;
    public $last_name;
    public $age;
    public $image_url;

    public $barangay_id;
    public $barangay_name;

    public $check = null;

    protected $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
    ];

    public function render()
    {
        return view('livewire.admin.candidates-component');
    }

    public function edit($barangay_id)
    {
        $this->dispatchBrowserEvent('show-modal');
        $this->barangay_id = $barangay_id;
        $this->barangay_name = Barangay::find($barangay_id)->name;

        $data = Candidate::where('barangay_id',$barangay_id)->first();

        $this->check = $data;
        // dd($data->image_url);

        $this->first_name = $data->first_name ?? "";
        $this->middle_name = $data->middle_name ?? "";
        $this->last_name = $data->last_name ?? "";
        $this->age = $data->age ?? "";
        $this->image_url = $data->image_url ?? "";

    }

    public function update()
    {
        $this->validate();

        $data = Candidate::updateOrCreate(
            [ 'barangay_id' => $this->barangay_id],
            [
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'age' => $this->age,
            ]
        );

        if($data) {
            session()->flash('success','Candidate Data has been updated successfully!');
            $this->dispatchBrowserEvent('hide-modal');
        } else {
            session()->flash('error','Something went wrong!');
        }
    }
}

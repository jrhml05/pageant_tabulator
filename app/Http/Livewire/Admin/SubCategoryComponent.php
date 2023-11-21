<?php

namespace App\Http\Livewire\Admin;

use App\Models\SubCategory;
use Livewire\Component;

class SubCategoryComponent extends Component
{
    public $sub_category_name;
    public $sub_category_percent;
    public $status;

    public $records;
    public $category;

    protected $rules = [
        'records.*.sub_category_name' => 'required',
        'records.*.sub_category_percent' => 'required',
    ];


    public function mount()
    {
        $this->records = SubCategory::where('category_id', $this->category)->get();
    }

    public function render()
    {
        return view('livewire.admin.sub-category-component');
    }

    public function addRow()
    {
        if($this->records->sum('sub_category_percent') == 100) {
            session()->flash('error', 'You cannot add another sub category because it is already 100 in total!');
        } else {
            $this->records->push(new SubCategory());
        }
    }

    public function remove($index)
    {
        $record = $this->records[$index];
        $this->records->forget($index);

        $record->delete();

        session()->flash('success', 'The data row has been removed successfully!');
    }

    public function saveForm()
    {
        // $this->validate();
        // dd($this->records);
        foreach ($this->records as $record) {
            SubCategory::updateOrCreate(
                ['category_id' => $this->category, 'id' => $record->id],
                ['category_id' => $this->category, 'sub_category_name' => $record->sub_category_name,'sub_category_percent' => $record->sub_category_percent]
            );
        }

        session()->flash('success', 'The data has been saved successfully!');
    }
}

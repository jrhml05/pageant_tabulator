<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Livewire\Component;

class CategoryComponent extends Component
{
    public $stage = 1;
    public $category_name;
    public $percent;
    public $status;

    public $records;

    protected $rules = [
        'records.*.category_name' => 'required',
        'records.*.percent' => 'required',
    ];


    public function mount()
    {
        // $this->groups = DB::table('settings')->selectRaw('groups')->groupBy('groups')->orderBy('groups')->get();
        $this->records = Category::where('stage_id', $this->stage)->get();

    }

    public function render()
    {
        return view('livewire.admin.category-component');
    }

    public function addRow()
    {
        if($this->records->sum('percent') == 100) {
            session()->flash('error', 'You cannot add another category because it is already 100 in total!');
        } else {
            $this->records->push(new Category());
        }

        // $this->records = new Collection($records->members);
        // $this->records->push(Setting::make());
    }

    // public function addSubCatRow()
    // {
    //     $this->subrecords->push(new SubCategory());
    // }

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
            Category::updateOrCreate(
                ['stage_id' => $this->stage, 'id' => $record->id],
                ['stage_id' => $this->stage, 'category_name' => $record->category_name, 'tags' => strtolower($record->category_name),'percent' => $record->percent]
            );
        }

        session()->flash('success', 'The data has been saved successfully!');
    }

    public function filter()
    {
        $this->mount();
    }
}

<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class ActiveStatusComponent extends Component
{

    public Model $model;

    public $field;

    public $is_active;

    public function mount()
    {
        $this->is_active = (bool) $this->model->getAttribute($this->field);
    }

    public function updating($field, $value)
    {
        $this->model->setAttribute($this->field, $value)->save();

    }

    public function render()
    {
        return view('livewire.admin.active-status-component');
    }
}

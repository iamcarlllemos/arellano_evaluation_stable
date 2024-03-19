<?php

namespace App\Livewire\Admin;

use App\Models\CriteriaModel;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class Criteria extends Component
{

    public $form;

    public $search;

    public $id;

    public $name;

    public function mount(Request $request) {

        $id = $request->input('id');
        $data = CriteriaModel::find($id);

        $this->id = $id;
        $this->name = $data->name ?? null;

    }

    public function placeholder() {
        return view('livewire.placeholder');
    }


    public function create() {

        $rules = [
            'name' => [
                'required',
                'string',
                'min:4',
                'unique:afears_criteria,name'
            ]
        ];

        $this->validate($rules);

        try {

            $model = new CriteriaModel;
            $model->name = $this->name;
            $model->save();

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Criteria `' . ucwords($this->name) . '` created successfully'
            ]);

            $this->name = '';

        } catch (\Exception $e) {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update() {

        $model = CriteriaModel::where('id', $this->id)->first();

        if ($model) {

            $rules = [
                'name' => [
                    'required',
                    'string',
                    'min:4',
                    Rule::unique('afears_criteria')->where(function ($query) {
                        return $query->where('id', $this->id);
                    })->ignore($this->id)
                ]
            ];

            $this->validate($rules);

            try {

                $model->name = $this->name;
                $model->save();

                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Criteria `' . ucwords($this->name) . '` updated successfully'
                ]);

            } catch (\Exception $e) {
                session()->flash('flash', [
                    'status' => 'failed',
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function delete() {

        $model = CriteriaModel::where('id', $this->id)->first();

        if($model) {

            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Criteria `'.$model->name.'` deleted successfully'
            ]);

            return redirect()->route('admin.programs.criteria');

        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }
    }

    public function render(Request $request) {

        $action = $request->input('action') ?? '';

        $criteria = CriteriaModel::
            when(strlen($this->search) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->get() ?? [];

        $data = [
            'criteria' => $criteria
        ];

        return view('livewire.admin.criteria', compact('data'));
    }
}

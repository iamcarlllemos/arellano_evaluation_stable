<?php

namespace App\Livewire\Admin;

use App\Models\CriteriaModel;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class Criteria extends Component
{

    public $form;
    public $select;
    public $search;

    public $id;
    public $name;

    public function mount(Request $request) {

        $id = $request->input('id');

        $data = CriteriaModel::find($id);

        $this->id = $id;
        $this->name = $data->name ?? '';
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

        $data = [
            'name' =>  htmlspecialchars($this->name)
        ];

        try {

            CriteriaModel::create($data);

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

                $model->name = htmlspecialchars($this->name);

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

        if($action == 'open') {
            $view = $request->input('view');
            if(in_array($view, ['courses'])) {
                $id = $request->input('id');
                $this->select = $id;
            }
        }

        $criteria = CriteriaModel::
            when(strlen($this->search) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->get();
    
        $criteria = $criteria->isEmpty() ? [] : $criteria;

        $data = [
            'criteria' => $criteria
        ];


        return view('livewire.admin.criteria', compact('data'));
    }
}

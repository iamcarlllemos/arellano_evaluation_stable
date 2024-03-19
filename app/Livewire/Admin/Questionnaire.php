<?php

namespace App\Livewire\Admin;

use App\Models\QuestionnaireModel;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;



class Questionnaire extends Component
{

    public $form;
    public $select;
    public $search;

    public $id;
    public $school_year_id;
    public $name;

    public function mount(Request $request) {

        $slug = $request->input('slug');

        $data = QuestionnaireModel::where('slug', $slug)->first();

        $this->id = $data->id ?? '';
        $this->school_year_id = $data->school_year_id ?? '';
        $this->name = $data->name ?? '';        
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {

        $rules = [
            'school_year_id' => 'required|integer|exists:afears_school_year,id|unique:afears_questionnaire,school_year_id',
            'name' => [
                'required',
                'string',
                'min:4',
            ]
        ];

        $this->validate($rules);

        $data = [
            'school_year_id' =>  $this->school_year_id,
            'name' =>  $this->name,
        ];

        try {

            QuestionnaireModel::create($data);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Questionnaire `' . ucwords($this->name) . '` created successfully'
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

        $model = QuestionnaireModel::where('id', $this->id)->first();
    
        if ($model) {

            $rules = [
                'school_year_id' => [
                    'required',
                    'integer',
                    'exists:afears_school_year,id',
                    Rule::unique('afears_questionnaire')
                        ->where(function ($query) {
                            $query->where('school_year_id', $this->school_year_id);
                        })
                    ->ignore($this->id),
                ],
                'name' => [
                    'required',
                    'string',
                    'min:4'
                ]
            ];
    
            $this->validate($rules);
            
            try {

                $model->school_year_id = $this->school_year_id;
                $model->name = $this->name;

                $model->save();
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Questionnaire `' . ucwords($this->name) . '` updated successfully'
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

        $model = QuestionnaireModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Questionnaire `'.$model->name.'` deleted successfully'
            ]);
            return redirect()->route('admin.programs.questionnaire');
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

        $questionnaire = QuestionnaireModel::with(['school_year'])
        ->when(strlen($this->search) >= 1, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->get();
        

        $questionnaire = $questionnaire->isEmpty() ? [] : $questionnaire;

        $data = [
            'questionnaire' => $questionnaire
        ];


        return view('livewire.admin.questionnaire', compact('data'));
    }
}

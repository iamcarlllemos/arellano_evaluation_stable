<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\QuestionnaireModel;


class Questionnaire extends Component
{

    public $form;
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

            $model = new QuestionnaireModel;

            $model->school_year_id = $this->school_year_id;
            $model->name = $this->name;

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Questionnaire `' . ucwords($this->name) . '` created successfully'
            ]);

           $this->reset();

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

        $questionnaire = QuestionnaireModel::with(['school_year'])
        ->when(strlen($this->search) >= 1, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->get();


        $data = [
            'questionnaire' => $questionnaire
        ];

        return view('livewire.admin.questionnaire', compact('data'));
    }
}

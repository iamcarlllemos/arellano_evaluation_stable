<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\QuestionnaireModel;
use Livewire\WithPagination;

class Questionnaire extends Component
{

    use WithPagination;

    public $form;

    public $search = [
        'type' => ''
    ];

    public $id;
    public $school_year_id;
    public $name;

    public $paginate_count;
    public $initPaginate = false;

    public $attr = [
        'school_year_id' => 'School year',
        'name' => 'Questionnaire name'
    ];

    protected $listeners = ['screen'];

    public function mount(Request $request) {

        $slug = $request->input('slug');
        $data = QuestionnaireModel::where('slug', $slug)->first();

        $this->id = $data->id ?? '';
        $this->school_year_id = $data->school_year_id ?? '';
        $this->name = $data->name ?? '';
    }

    public function placeholder() {
        return view('livewire.admin.placeholder');
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

        $this->validate($rules, [], $this->attr);

        try {

            $model = new QuestionnaireModel;

            $model->school_year_id = $this->school_year_id;
            $model->name = $this->name;

            $model->save();

            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Saved.'
            ]);

            $this->school_year_id = '';
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

            $this->validate($rules, [], $this->attr);

            try {

                $model->school_year_id = $this->school_year_id;
                $model->name = $this->name;

                $model->save();

                $this->dispatch('alert');
                session()->flash('alert', [
                    'message' => 'Update.'
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
            return redirect()->route('admin.programs.questionnaire');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }

    }

    public function screen($size) {
        switch($size) {
            case 'sm':
                $this->paginate_count = 5;
                break;
            case 'md':
                $this->paginate_count = 6;
                break;
            case 'lg':
                $this->paginate_count = 9;
                break;
            case 'xl':
                $this->paginate_count = 12;
                break;
        }
    }

    public function initPaginate() {
        if(!$this->initPaginate) {
            $this->dispatch('initPaginate');
            $this->initPaginate = true;
        }
    }

    public function render(Request $request) {

        $action = $request->input('action');

        $questionnaire = QuestionnaireModel::with(['school_year'])
        ->when(strlen($this->search['type']) >= 1, function ($query) {
            $query->where('name', 'like', '%' . $this->search['type'] . '%');
        })->paginate($this->paginate_count);

        $this->initPaginate();

        $data = [
            'questionnaire' => $questionnaire
        ];

        return view('livewire.admin.questionnaire', compact('data'));
    }
}

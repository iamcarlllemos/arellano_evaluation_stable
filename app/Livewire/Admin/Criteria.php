<?php

namespace App\Livewire\Admin;

use App\Models\CriteriaModel;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class Criteria extends Component
{

    use WithPagination;

    public $form;

    public $search = [
        'type' => ''
    ];

    public $id;
    public $name;

    public $initPaginate = false;

    public $attr = [
        'name' => 'Criteria name'
    ];

    public $paginate_count;
    protected $listeners = ['screen'];

    public function mount(Request $request) {

        $id = $request->input('id');
        $data = CriteriaModel::find($id);

        $this->id = $id;
        $this->name = $data->name ?? null;

    }

    public function placeholder() {
        return view('livewire.admin.placeholder');
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

        $this->validate($rules, [], $this->attr);

        try {

            $model = new CriteriaModel;
            $model->name = ucfirst($this->name);
            $model->save();

            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Saved.'
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

            $this->validate($rules, [], $this->attr);

            try {

                $model->name = ucfirst($this->name);
                $model->save();

                $this->dispatch('alert');
                session()->flash('alert', [
                    'message' => 'Updated.'
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
            return redirect()->route('admin.programs.criteria');
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

        $criteria = CriteriaModel::query();

        if (strlen($this->search['type']) >= 1) {
            $criteria->where('name', 'like', '%' . $this->search['type'] . '%');
        }

        $criteria = $criteria->paginate($this->paginate_count);

        $this->initPaginate();

        $data = [
            'criteria' => $criteria
        ];

        return view('livewire.admin.criteria', compact('data'));
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\SchoolYearModel;
use App\Models\SubjectModel;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class SchoolYear extends Component
{

    use WithPagination;

    public $form;

    public $search = [
        'type' => ''
    ];

    public $id;
    public $name;
    public $start_year;
    public $semester;
    public $status;

    public $initPaginate = false;

    public $attr = [
        'name' => 'Subject name',
        'start_year' => 'Start year',
        'end_year' => 'End Year',
        'semester' => 'Semester',
        'status' => 'Status'
    ];

    public $paginate_count;
    protected $listeners = ['screen'];

    public function mount() {

        $id = $this->form['id'];

        $data = SchoolYearModel::find($id);

        $this->id = $id;
        $this->name = $data->name ?? '';
        $this->start_year = $data->start_year ?? '';
        $this->semester = $data->semester ?? '';
        $this->status = $data->status ?? '';

    }

    public function create() {

        $rules = [
            'name' => 'required|min:4',
            'start_year' => [
                'required',
                Rule::unique('afears_school_year')->where(function ($query) {
                    return $query->where('start_year', $this->start_year)
                        ->where('end_year', $this->start_year + 1)
                        ->where('semester', $this->semester);
                })
            ],
            'semester' => [
                'required',
                Rule::unique('afears_school_year')->where(function ($query) {
                    return $query->where('start_year', $this->start_year)
                        ->where('end_year', $this->start_year + 1)
                        ->where('semester', $this->semester);
                })
            ],
            'status' => 'required|in:0,1,2,3'
        ];

        $this->validate($rules, [], $this->attr);

        try {

            $model = new SchoolYearModel;
            $model->name = $this->name;
            $model->start_year = $this->start_year;
            $model->end_year = $this->start_year + 1;
            $model->semester = $this->semester;
            $model->status = $this->status;

            $model->save();

            $this->resetExcept('form', 'initPaginate');

            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Saved.'
            ]);

        } catch (\Exception $e) {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update() {

        $model = SchoolYearModel::where('id', $this->id)->first();

        if ($model) {

            $rules = [
                'name' => 'required|min:4',
                'start_year' => [
                    'required',
                    Rule::unique('afears_school_year')->where(function ($query) {
                        return $query->where('start_year', $this->start_year)
                            ->where('end_year', $this->start_year + 1)
                            ->where('semester', $this->semester);
                    })->ignore($this->id)
                ],
                'semester' => [
                    'required',
                    Rule::unique('afears_school_year')->where(function ($query) {
                        return $query->where('start_year', $this->start_year)
                            ->where('end_year', $this->start_year + 1)
                            ->where('semester', $this->semester);
                    })->ignore($this->id)
                ],
                'status' => 'required|in:0,1,2,3'
            ];

            $this->validate($rules, [], $this->attr);

            try {

                $model->name = htmlspecialchars($this->name);
                $model->semester = $this->semester;
                $model->status = $this->status;

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

        $model = SchoolYearModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            return redirect()->route('admin.programs.school-year');
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

    public function placeholder() {
        return view('livewire.admin.placeholder');
    }

    public function render(Request $request) {

        session()->forget('no_questionnaire');

        $school_year = SchoolYearModel::
            when(strlen($this->search['type']) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search['type'] . '%')
                    ->orWhere('start_year', 'like', '%' . $this->search['type'] . '%')
                    ->orWhere('end_year', 'like', '%' . $this->search['type'] . '%')
                    ->orWhere(function($query) {
                        $query->whereRaw("CONCAT(start_year, '-', end_year) LIKE ?", ['%' . $this->search['type'] . '%']);
                    });
            })->paginate($this->paginate_count);

        $this->initPaginate();

        $data = [
            'school_year' => $school_year
        ];

        return view('livewire.admin.school-year', compact('data'));
    }
}

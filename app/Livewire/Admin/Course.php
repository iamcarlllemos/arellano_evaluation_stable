<?php

namespace App\Livewire\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Traits\Account;

use Livewire\Component;

use App\Models\DepartmentModel;
use App\Models\CourseModel;
use Livewire\WithPagination;

class Course extends Component
{

    use Account;
    use WithPagination;

    public $form;

    public $search = [
        'type' => '',
        'select' => ''
    ];

    public $id;

    public $department_id;
    public $code;
    public $name;

    public $initPaginate = false;

    public $attr = [
        'department_id' => 'Department name',
        'code' => 'Course code',
        'name' => 'Course name'
    ];

    public $paginate_count;
    protected $listeners = ['screen'];

    public function mount() {

        $id = $this->form['id'];

        $data = CourseModel::find($id);

        $this->id = $id;
        $this->department_id = $data->department_id ?? '';
        $this->code = $data->code ?? '';
        $this->name = $data->name ?? '';
    }

    public function create() {

        $rules = [
            'department_id' => 'required|integer|exists:afears_department,id',
            'code' => 'required|min:3',
            'name' => [
                'required',
                'string',
                'min:4',
                Rule::unique('afears_course')->where(function ($query) {
                    return $query->where('department_id', $this->department_id);
                })
            ]
        ];

        $this->validate($rules, [], $this->attr);

        try {

            $model = new CourseModel;

            $model->department_id = $this->department_id;
            $model->code = strtoupper($this->code);
            $model->name = ucfirst($this->name);

            $model->save();

            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Saved.'
            ]);

            $this->department_id = '';
            $this->code = '';
            $this->name = '';

        } catch (\Exception $e) {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update() {

        $model = CourseModel::where('id', $this->id)->first();

        if ($model) {

            $rules = [
                'department_id' => 'required|integer|exists:afears_department,id',
                'code' => 'required|min:3',
                'name' => [
                    'required',
                    'string',
                    'min:4',
                    Rule::unique('afears_course')->where(function ($query) {
                        return $query->where('department_id', $this->id);
                    })->ignore($this->id)
                ]
            ];

            $this->validate($rules, [], $this->attr);

            try {

                $model->department_id = $this->department_id;
                $model->code = strtoupper($this->code);
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

        $model = CourseModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            return redirect()->route('admin.programs.courses');
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

        $role = $this->admin()->role;
        $assigned_branch = $this->admin()->assigned_branch;

        $courses = CourseModel::with(['departments.branches'])
            ->when(strlen($this->search['type']) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search['type'] . '%')
                ->orWhere('code', 'like', '%' . $this->search['type'] . '%');
            })
            ->when($this->search['select'] != '', function ($query) {
                $query->where('department_id', $this->search['select']);
            })
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('departments.branches', function($subQuery) use ($assigned_branch) {
                    $subQuery->where('branch_id', $assigned_branch);
                });
            })
            ->paginate($this->paginate_count);

        $departments_dirty = DepartmentModel::with('branches')
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->where('branch_id', $assigned_branch);
            })
            ->get();

        $departments = [];

        if($role === 'admin') {
            foreach($departments_dirty as $department) {
                $departments[] = [
                    'id' => $department->id,
                    'name' => $department->name
                ];
            }
        } else {
            foreach($departments_dirty as $department) {
                $key = $department->branches->id;

                if(!isset($departments[$key])) {
                    $departments[$key] = [
                        'id' => $key,
                        'name' => $department->branches->name,
                        'departments' => []
                    ];
                }

                $departments[$key]['departments'][] = [
                    'id' => $department->id,
                    'name' => $department->name
                ];
            }
        }

        $departments = array_values($departments);

        $this->initPaginate();

        $data = [
            'departments' => $departments,
            'courses' => $courses
        ];


        return view('livewire.admin.course', compact('data'));
    }
}

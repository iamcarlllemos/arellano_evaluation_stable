<?php

namespace App\Livewire\Admin;

use App\Models\BranchModel;
use App\Models\DepartmentModel;
use App\Models\CourseModel;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class Course extends Component
{

    public $form;
    public $select;
    public $search;

    public $id;
    public $department_id;
    public $code;
    public $name;

    public function mount(Request $request) {

        $id = $request->input('id');

        $data = CourseModel::find($id);

        $this->id = $id;
        $this->department_id = $data->department_id ?? '';
        $this->code = $data->code ?? '';
        $this->name = $data->name ?? '';
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {

        $rules = [
            'department_id' => 'required|integer|exists:afears_department,id',
            'code' => 'required|min:4',
            'name' => [
                'required',
                'string',
                'min:4',
                Rule::unique('afears_course')->where(function ($query) {
                    return $query->where('department_id', $this->department_id);
                })
            ]
        ];

        $this->validate($rules);

        $data = [
            'department_id' => htmlspecialchars($this->department_id),
            'code' => htmlspecialchars($this->code),
            'name' =>  htmlspecialchars($this->name)
        ];

        try {

            CourseModel::create($data);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Course `' . ucwords($this->name) . '` created successfully'
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
                'code' => 'required|min:4',
                'name' => [
                    'required',
                    'string',
                    'min:4',
                    Rule::unique('afears_course')->where(function ($query) {
                        return $query->where('department_id', $this->id);
                    })->ignore($this->id)
                ]
            ];
    
            $this->validate($rules);
            
            try {

                $model->department_id = $this->department_id;
                $model->code = htmlspecialchars($this->code);
                $model->name = htmlspecialchars($this->name);

                $model->save();
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Course `' . ucwords($this->name) . '` updated successfully'
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
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Course `'.$model->name.'` deleted successfully'
            ]);
            return redirect()->route('admin.programs.courses');
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

        $role = auth()->user()->role;
        $assigned_branch = auth()->user()->assigned_branch;

        $courses = CourseModel::with(['departments.branches'])
            ->when(strlen($this->search) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->when($this->select != '', function ($query) {
                $query->where('department_id', $this->select);
            })
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('departments.branches', function($subQuery) use ($assigned_branch) {
                    $subQuery->where('branch_id', $assigned_branch); 
                });
            })
            ->get();
    
        $courses = $courses->isEmpty() ? [] : $courses;

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

        $data = [
            'departments' => $departments,
            'courses' => $courses
        ];


        return view('livewire.admin.course', compact('data'));
    }
}

<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Traits\ExecuteRule;
use App\Traits\Account;

use App\Models\CourseModel;
use App\Models\SubjectModel;

class Subject extends Component
{

    use ExecuteRule;
    use Account;


    public $form;
    public $select;
    public $search;

    public $id;

    public $course_id;
    public $code;
    public $name;

    public $attr = [
        'course_id' => 'Course name',
        'code' => 'Subject code',
        'name' => 'Subject name'
    ];

    public function mount(Request $request) {

        $id = $request->input('id');

        $data = SubjectModel::find($id);

        $this->id = $id;
        $this->course_id = $data->course_id ?? '';
        $this->code = $data->code ?? '';
        $this->name = $data->name ?? '';
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {

        $rules = [
            'course_id' => 'required|integer|exists:afears_course,id',
            'code' => 'required|min:4',
            'name' => [
                'required',
                'string',
                'min:4',
                Rule::unique('afears_subject')->where(function ($query) {
                    return $query->where('course_id', $this->course_id);
                })
            ]
        ];

        $this->validate($rules, [], $this->attr);

        try {

            $model = new SubjectModel;
            $model->course_id = $this->course_id;
            $model->code = strtoupper($this->code);
            $model->name = ucfirst($this->name);

            $model->save();

            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Saved.'
            ]);

            $this->course_id = '';
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

        $model = SubjectModel::where('id', $this->id)->first();

        if ($model) {

            $rules = [
                'course_id' => 'required|integer|exists:afears_course,id',
                'code' => 'required|min:4',
                'name' => [
                    'required',
                    'string',
                    'min:4',
                    Rule::unique('afears_subject')->where(function ($query) {
                        return $query->where('course_id', $this->id);
                    })->ignore($this->id)
                ]
            ];

            $this->validate($rules, [], $this->attr);

            try {

                $model->course_id = $this->course_id;
                $model->code = htmlspecialchars($this->code);
                $model->name = htmlspecialchars($this->name);

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

        $model = SubjectModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            return redirect()->route('admin.programs.subjects');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }
    }
    public function render(Request $request) {

        $action = $request->input('action');

        $role = $this->admin()->role;
        $assigned_branch = $this->admin()->assigned_branch;

        $subjects = SubjectModel::with(['courses.departments.branches'])
            ->when(strlen($this->search) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->when($this->select != '', function ($query) {
                $query->where('course_id', $this->select);
            })
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('courses.departments.branches', function($subQuery) use ($assigned_branch) {
                    $subQuery->where('id', $assigned_branch);
                });
            })
            ->get();

        $subjects = $subjects->isEmpty() ? [] : $subjects;

        $courses_dirty = CourseModel::with('departments.branches')
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('departments.branches', function($subQuery) use ($assigned_branch) {
                    $subQuery->where('id', $assigned_branch);
                });
            })->get();

        $courses = [];

        if($role === 'admin') {
            foreach($courses_dirty as $course) {
                $courses[] = [
                    'id' => $course->id,
                    'name' => $course->name
                ];
            }
        } else {
            foreach($courses_dirty as $course) {
                $key = $course->departments->branches->id;

                if(!isset($courses[$key])) {
                    $courses[$key] = [
                        'id' => $key,
                        'name' => $course->departments->branches->name,
                        'courses' => []
                    ];
                }

                $courses[$key]['courses'][] = [
                    'id' => $course->id,
                    'name' => $course->name
                ];
            }
        }

        $courses = array_values($courses);

        $data = [
            'courses' => $courses,
            'subjects' => $subjects
        ];

        return view('livewire.admin.subject', compact('data'));
    }
}

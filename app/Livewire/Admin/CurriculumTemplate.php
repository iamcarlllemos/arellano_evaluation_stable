<?php

namespace App\Livewire\Admin;
use Livewire\Component;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Traits\SearchCurriculumTemplate;
use App\Traits\Account;

use App\Models\CourseModel;
use App\Models\CurriculumTemplateModel;
use App\Models\DepartmentModel;
use App\Models\SubjectModel;

class CurriculumTemplate extends Component
{

    use SearchCurriculumTemplate;
    use Account;

    public $form;
    public $select;
    public $search = [
        'course' => '',
        'year' => '',
        'semester' => ''
    ];

    public $id;
    public $departments = [];
    public $courses = [];
    public $subjects = [];
    public $department_id;
    public $course_id;
    public $subject_id;
    public $year_level;
    public $subject_sem;

    public $attr = [
        'department_id' => 'Department name',
        'course_id' => 'Course name',
        'subject_id' => 'Subject name',
        'year_level' => 'Year level',
        'subject_sem' => 'Semester'
    ];

    public function loadDepartments($id = null) {

        if($id == null) {

            $role = $this->admin()->role;
            $assigned_branch = $this->admin()->assigned_branch;

            $department_dirty = DepartmentModel::with('branches')
                ->when($role == 'admin', function($query) use ($assigned_branch) {
                    $query->where('branch_id', $assigned_branch);
                })
                ->get();

            $departments = [];

            if($role === 'admin') {
                foreach($department_dirty as $department) {
                    $departments[] = [
                        'id' => $department->id,
                        'name' => $department->name
                    ];
                }
            } else {
                foreach($department_dirty as $item) {
                    $key = $item->branches->id;
                    if(!isset($departments[$key])) {
                        $departments[$key] = [
                            'id' => $key,
                            'name' => $item->branches->name,
                            'departments' => []
                        ];
                    }

                    $departments[$key]['departments'][] = [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                }
                $departments = array_values($departments);
            }

            return $this->departments = $departments;
        } else {
            $this->department_id = $id;
            $this->loadCourses($id);
            $this->loadSubjects();
        }

    }

    public function loadCourses($id = null) {
        if($id != null) {
            $data = CourseModel::where('department_id', $this->department_id)->get();
            $this->loadSubjects();
            return $this->courses = $data;
        }
    }

    public function loadYear($id) {
        $this->year_level = $id;
    }

    public function loadSubjects() {
        $data = SubjectModel::with(['courses.departments'])
            ->when(!empty($this->course_id), function($subQuery) {
                $subQuery->whereHas('courses', function($query) {
                    $query->where('id', $this->course_id);
                });
            })
            ->when(!empty($this->department_id), function($subQuery) {
                $subQuery->whereHas('courses.departments', function($query) {
                    $query->where('id', $this->department_id);
                });
            })
            ->get();
        $this->subjects = $data;
    }

    public function mount(Request $request) {

        $this->loadDepartments();

        $action = $request->input('action');
        $id = $request->input('id');
        $data = CurriculumTemplateModel::first();

        if($data) {
            if(in_array($action, ['update', 'delete'])) {

                $this->id = $id;
                $this->department_id = $data->department_id;
                $this->course_id = $data->course_id;
                $this->subject_id = $data->subject_id;
                $this->subject_sem = $data->subject_sem;
                $this->year_level = $data->year_level;

                $dirty = DepartmentModel::with('branches')->get();

                $clean = [];

                foreach($dirty as $item) {
                    $clean[] = (object)[
                        'id' => $item->id,
                        'name' => $item->name . ' - (' . $item['branches']->name .  ')'
                    ];
                }
                $this->departments = $clean;
                $this->courses = CourseModel::all();
                $this->subjects = SubjectModel::all();
            }  elseif(in_array($action, ['create'])) {
                $this->id = $id;
                $this->department_id = '';
                $this->course_id = '';
                $this->subject_id =  '';
                $this->subject_sem = '';
                $this->year_level = '';
            } else {

                $courses_dirty = CourseModel::with('departments.branches')->get();

                $courses = [];

                foreach($courses_dirty as $item) {
                    $branch_key = $item->departments->branches->id;
                    $branch_name = $item->departments->branches->name;
                    $key = $branch_key;

                    if(!isset($courses[$branch_key])) {
                        $courses[$branch_key] = [
                            'id' => $branch_key,
                            'name' => $branch_name,
                            'courses' => []
                        ];
                    }

                    $courses[$branch_key]['courses'][] = [
                        'id' => $item->id,
                        'name' => $item->name . ' (' . strtoupper($item->code) . ') '
                    ];
                }

                $courses = array_values($courses);
                $this->courses = $courses;
            }
        }
    }

    public function placeholder() {
        return view('livewire.admin.placeholder');
    }

    public function create() {

        $rules = [
            'department_id' => 'required|integer|exists:afears_department,id',
            'course_id' => 'required|integer|exists:afears_course,id',
            'subject_id' => [
                'required',
                'integer',
                'exists:afears_subject,id',
                Rule::unique('afears_curriculum_template')->where(function($query) {
                    $query->where('department_id', $this->department_id)
                        ->where('course_id', $this->course_id)
                        ->where('subject_id', $this->subject_id)
                        ->where('subject_sem', $this->subject_sem)
                        ->where('year_level', $this->year_level);
                })
            ],
            'subject_sem' => 'required|integer|in:1,2',
            'year_level' => 'required|integer|in:1,2,3,4',
        ];

        $this->validate($rules, [], $this->attr);

        try {

            $model = new CurriculumTemplateModel;
            $model->department_id = $this->department_id;
            $model->course_id = $this->course_id;
            $model->subject_id = $this->subject_id;
            $model->subject_sem = $this->subject_sem;
            $model->year_level = $this->year_level;

            $model->save();

            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Added.'
            ]);

            $this->department_id = '';
            $this->course_id = '';
            $this->subject_id = '';
            $this->subject_sem = '';
            $this->year_level = '';

        } catch (\Exception $e) {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete() {

        $model = CurriculumTemplateModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Saved.'
            ]);
            return redirect()->route('admin.linking.curriculum-template');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }
    }

    public function render(Request $request) {

        $action = $request->input('action');

        $keywords = [
            'course' => $this->search['course'] ?? 0,
            'year_level' => $this->search['year'] ?? 0,
            'semester' => $this->search['semester'] ?? 0
        ];

        $this->dispatch('reinitializeJstree');

        $templates = $this->find($keywords);

        $data = [
            'templates' => $templates
        ];

        return view('livewire.admin.curriculum-template', compact('data'));
    }
}

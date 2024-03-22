<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\BranchModel;
use App\Models\CurriculumTemplateModel;
use App\Models\FacultyModel;
use App\Models\FacultyTemplateModel;
use Livewire\WithPagination;

class FacultyTemplate extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $form;

    public $select = [
        'branch' => '',
        'year'=> '',
        'semester' => ''
    ];

    public $search ;

    public $id;
    public $department_id;
    public $employee_number;
    public $firstname;
    public $lastname;
    public $middlename;
    public $gender;
    public $image;
    public $email;
    public $template;
    public $link_multiple = [];
    public object $curriculum_template;

    public $paginate_count;
    public $initPaginate = false;
    protected $listeners = ['screen'];

    public function mount(Request $request) {

        $action = $request->input('action');
        $id = $request->input('id');

        $this->id = $id;

        if (in_array($action, ['template', 'connect'])) {

            $data = FacultyModel::with(['templates.curriculum_template.subjects.courses.departments.branches', 'departments.branches'])->where('id', $id)->get()[0]->toArray();

            $template_data = [];
            foreach ($data['templates'] as $template) {
                $courseName = $template['curriculum_template'][0]['subjects']['courses']['name'];
                $subjectName = $template['curriculum_template'][0]['subjects']['name'];
                $yearLevel = $template['curriculum_template'][0]['year_level'];
                $semester = $template['curriculum_template'][0]['subject_sem'];
                $branchName = $template['curriculum_template'][0]['subjects']['courses']['departments']['branches']['name'];

                $key = "$branchName-$courseName-$yearLevel-$semester";

                if (!isset($template_data[$key])) {
                    $template_data[$key] = [
                        'branch' => $branchName,
                        'course' => $courseName,
                        'year' => $yearLevel,
                        'semester' => $semester,
                        'subjects' => []
                    ];
                }

                $template_data[$key]['subjects'][] = $subjectName;
            }

            $template_data = array_values($template_data);

            $data['templates'] = $template_data;


            $this->template = $data;
        }
    }

    public function loadCurriculumTemplate() {
        $id = $this->id;
        $role = auth()->guard('admins')->user()->role;
        $assigned_branch = auth()->guard('admins')->user()->assigned_branch;
        $curriculum_template = CurriculumTemplateModel::select('*')
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('departments', function($subQuery) use ($assigned_branch) {
                    $subQuery->where('branch_id', $assigned_branch);
                });
            })
            ->when(strlen($this->search) >= 1, function ($sQuery) {
                $sQuery->whereHas('courses', function($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%');
                    $subQuery->orWhere('code', 'like', '%' . $this->search . '%');
                });
                $sQuery->orWhereHas('subjects', function($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%');
                });
                $sQuery->orWhereHas('departments', function ($dQuery) {
                    $dQuery->where('name', 'like', '%' . $this->search . '%');
                    $dQuery->orWhereHas('branches', function ($bQuery) {
                        $bQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when(!empty($this->select['branch']), function ($query) {
                $query->whereHas('departments.branches', function ($subQuery) {
                    $subQuery->where('branch_id', $this->select['branch']);
                });
            })
            ->when(!empty($this->select['year']), function ($query) {
                $query->where('year_level', $this->select['year']);
            })
            ->when(!empty($this->select['semester']), function ($query) {
                $query->where('subject_sem', $this->select['semester']);
            })
            ->selectRaw('(CASE WHEN EXISTS (
                SELECT 1
                FROM afears_faculty_template
                WHERE afears_faculty_template.template_id = afears_curriculum_template.id
                AND afears_faculty_template.faculty_id = ?
            ) THEN 1 ELSE 0 END) as is_exists', [$id])
            ->with(['departments', 'courses', 'subjects'])
            ->get();

            $this->curriculum_template = $curriculum_template;
    }

    public function placeholder() {
        return view('livewire.admin.placeholder');
    }

    public function toggleLinkMultiple($isTrue) {
        $dirty = $this->link_multiple;

        $cleaned = [];

        foreach($dirty as $dirt) {
            $d = explode(',', $dirt);
            $cleaned[] = [
                'faculty_id'=> $d[0],
                'template_id' => $d[1]
            ];
        }

        if(count($cleaned) > 0) {
            foreach($cleaned as $clean) {
                $rules = [
                    'faculty_id' => 'required|exists:afears_faculty,id',
                    'template_id' => 'required|exists:afears_curriculum_template,id'
                ];

                $validator = Validator::make($clean, $rules);

                if($validator->fails()) {
                    dd(123);
                }

            }
            if($isTrue) {
                FacultyTemplateModel::insert($cleaned);

            } else {
                $facultyIds = array_column($cleaned, 'faculty_id');
                $templateIds = array_column($cleaned, 'template_id');
                FacultyTemplateModel::whereIn('faculty_id', $facultyIds)
                    ->whereIn('template_id', $templateIds)
                    ->delete();
            }
            $this->link_multiple = [];
        }
    }

    public function toggleLink($faculty_id, $template_id) {

        $rules = [
            'faculty_id' => 'required|exists:afears_faculty,id',
            'template_id' => 'required|exists:afears_curriculum_template,id'
        ];

        $data = [
            'faculty_id' => $faculty_id,
            'template_id' => $template_id
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            dd($validator->errors());
        }

        $exists = FacultyTemplateModel::where('faculty_id', $faculty_id)
        ->where('template_id', $template_id)->exists();

        if($exists) {
            FacultyTemplateModel::where('faculty_id', $faculty_id)
            ->where('template_id', $template_id)->delete();
        } else {
            FacultyTemplateModel::create([
                'faculty_id' => $faculty_id,
                'template_id' => $template_id
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

        $action = $request->input('action') ?? '';

        $role = auth()->guard('admins')->user()->role;
        $assigned_branch = auth()->guard('admins')->user()->assigned_branch;

        $faculty = FacultyModel::with(['departments.branches'])
            ->when(strlen($this->search) >= 1, function ($sQuery) {
                $sQuery->where(function($query) {
                    $query->where('firstname', 'like', '%' . $this->search . '%');
                });
                $sQuery->orWhereHas('departments', function ($dQuery) {
                    $dQuery->where('name', 'like', '%' . $this->search . '%');
                    $dQuery->orWhereHas('branches', function ($bQuery) {
                        $bQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->select['branch'] != '', function ($query) {
                $query->whereHas('departments.branches', function ($subQuery) {
                    $subQuery->where('branch_id', $this->select);
                });
            })
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('departments.branches', function($subQuery) use ($assigned_branch) {
                    $subQuery->where('branch_id', $assigned_branch);
                });
            })->paginate($this->paginate_count);

        $branches = BranchModel::with('departments')
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->where('id', $assigned_branch);
            })->get();

        $this->loadCurriculumTemplate();
        $this->initPaginate();

        $data = [
            'branches' => $branches,
            'faculty' => $faculty,
        ];

        return view('livewire.admin.faculty-template', compact('data'));

    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\BranchModel;
use App\Models\StudentModel;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


class Student extends Component
{

    use WithFileUploads;

    public $form;
    public $select;
    public $search;

    public $id;
    public $status;
    public $course_id;
    public $student_number;
    public $firstname;
    public $lastname;
    public $middlename;
    public $gender;
    public $birthday;
    public $year_level;
    public $image;
    public $email;
    public $username;
    public $password;
    public $password_repeat;

    public function mount(Request $request) {

        $id = $request->input('id');
        $data = StudentModel::find($id);

        $this->id = $id;
        $this->course_id = $data->course_id ?? '';
        $this->student_number = $data->student_number ?? '';
        $this->firstname = $data->firstname ?? '';
        $this->lastname = $data->lastname ?? '';
        $this->middlename = $data->middlename ?? '';
        $this->gender = $data->gender ?? '';
        $this->birthday = $data->birthday ?? '';
        $this->year_level = $data->year_level ?? '';
        $this->image = $data->image ?? '';
        $this->email = $data->email ?? '';
        $this->username = $data->username ?? '';
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {

        $rules = [
            'course_id' => 'required|integer|exists:afears_course,id',
            'student_number' => 'required|unique:afears_student,student_number',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'string|min:8',
            'gender' => 'required|integer|in:1,2,3,4',
            'birthday' => 'required',
            'year_level' => 'required|integer|in:1,2',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5000',
            'email' => 'required|email|unique:afears_student,email',
            'username' => 'required|string|unique:afears_student,username',
            'password' => 'required|string|min:8|same:password_repeat',
            'password_repeat' => 'required|string|min:8|same:password'
        ];


        $this->status = 'failed';

        $this->validate($rules);

        $temp_filename = time();
        $extension =$this->image->getClientOriginalExtension();

        $filename = $temp_filename . '.' . $extension;

        $data = [
            'course_id' => $this->course_id,
            'student_number' => $this->student_number,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'middlename' => $this->middlename,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'year_level' => $this->year_level,
            'image' => $filename,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->password),
        ];

        try {

            StudentModel::create($data);
            $this->image->storeAs('public/images/students', $filename);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Student `' . ucwords($this->firstname . ' ' . $this->lastname) . '` created successfully'
            ]);

            $this->course_id = '';
            $this->student_number = '';
            $this->firstname = '';
            $this->lastname = '';
            $this->middlename = '';
            $this->gender = '';
            $this->birthday = '';
            $this->year_level = '';
            $this->image = '';
            $this->email = '';
            $this->username = '';
            $this->password = '';
            $this->password_repeat = '';

        } catch (\Exception $e) {

            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }       
    }

    public function update() {

        $model = StudentModel::where('id', $this->id)->first();
    
        if ($model) {

            $rules = [
                'course_id' => 'required|integer|exists:afears_course,id',
                'student_number' => [
                    'required',
                    Rule::unique('afears_student')->where(function($query) {
                        return $query->where('student_number', $this->student_number);
                    })->ignore($this->id)
                ],
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'middlename' => 'string|min:8',
                'gender' => 'required|integer|in:1,2,3,4',
                'birthday' => 'required',
                'year_level' => 'required|integer|in:1,2',
                'email' =>  [
                    'required',
                    'string',
                    'email',
                    Rule::unique('afears_student')->where(function($query) {
                        return $query->where('email', $this->email);
                    })->ignore($this->id)
                ],
                'username' => [
                    'required',
                    'string',
                    Rule::unique('afears_student')->where(function($query) {
                        return $query->where('username', $this->username);
                    })->ignore($this->id)
                ],
            ];
    
            $this->validate($rules);
            
            if($this->image instanceof TemporaryUploadedFile) {

                $rules = [
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:5000'
                ];

                $this->validate($rules);

                Storage::disk('public')->delete('images/students/' . $model->image);
        
                $temp_filename = time();
                $extension = $this->image->getClientOriginalExtension();
        
                $filename = $temp_filename . '.' . $extension;
        
                $this->image->storeAs('public/images/students', $filename);
                $this->image = $filename;
                $model->image = $filename;

            }

            if(!empty($this->password && !empty($this->password_repeat))) {

                $rules = [
                    'password' => 'required|string|min:8|same:password_repeat',
                    'password_repeat' => 'required|string|min:8|same:password'
                ];

                $this->validate($rules);

                try {
                    $model->password = Hash::make($this->password);
                    $model->save();
                    $this->password = '';
                    $this->password_repeat = '';
                } catch (\Exception $e) {
    
                    session()->flash('flash', [
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ]);
                }    

            }


            try {

                $model->course_id = $this->course_id;
                $model->student_number = $this->student_number;
                $model->firstname = $this->firstname;
                $model->lastname = $this->lastname;
                $model->middlename = $this->middlename;
                $model->gender = $this->gender;
                $model->birthday = $this->birthday;
                $model->year_level = $this->year_level;
                $model->email = $this->email;
                $model->username = $this->username;
                $model->save();
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Student `' . ucwords($this->firstname . ' ' . $this->lastname) . '` updated successfully'
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

        $model = StudentModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Student `' . ucwords($this->firstname . ' ' . $this->lastname) . '` deleted successfully'
            ]);
            return redirect()->route('admin.accounts.student');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }
    }
    public function render(Request $request) {
        
        $action = $request->input('action') ?? '';

        $role = auth()->user()->role;
        $assigned_branch = auth()->user()->assigned_branch;

        $students = StudentModel::with(['courses.departments.branches'])
            ->when(strlen($this->search) >= 1, function ($sQuery) {
                $sQuery->where(function($query) {
                    $query->where('firstname', 'like', '%' . $this->search . '%');
                });
                $sQuery->orWhereHas('courses', function ($cQuery) {
                    $cQuery->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('code', 'like', '%' . $this->search . '%');
                    })->orWhereHas('departments', function ($dQuery) {
                        $dQuery->where('name', 'like', '%' . $this->search . '%');
                        $dQuery->orWhereHas('branches', function ($bQuery) {
                            $bQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                    });
                });
            })
            ->when($this->select != '', function ($query) {
                $query->whereHas('courses.departments.branches', function ($subQuery) {
                    $subQuery->where('branch_id', $this->select);
                });
            })
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('courses.departments.branches', function($subQuery) use ($assigned_branch) {
                    $subQuery->where('branch_id', $assigned_branch);
                });
            })
            ->get();
       
            $branches = BranchModel::with('departments')
                ->when($role == 'admin', function($query) use ($assigned_branch) {
                    $query->where('id', $assigned_branch);
                })
                ->get();
    
        $students = $students->isEmpty() ? [] : $students;
        $data = [
            'branches' => $branches,
            'students' => $students
        ];


        return view('livewire.admin.student', compact('data'));
       
    }
}

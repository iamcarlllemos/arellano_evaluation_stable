<?php

namespace App\Livewire\Admin;

use App\Mail\Mailer;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Traits\Account;

use App\Models\BranchModel;
use App\Models\StudentModel;
use Illuminate\Support\Facades\Mail;
use Livewire\WithPagination;

class Student extends Component
{

    use WithFileUploads;
    use Account;
    use WithPagination;

    public $form;

    public $search = [
        'type' => '',
        'select' => ''
    ];

    public $id;

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
    public $is_email;

    public $initPaginate = false;

    public $attr = [
        'course_id' => 'Course name',
        'student_number' => 'Student number',
        'firstname' => 'First name',
        'lastname' => 'Last name',
        'middlename' => 'Middle name',
        'gender' => 'Gender',
        'birthday' => 'Birthday',
        'year_level' => 'Year level',
        'image' => 'Profile photo',
        'email' => 'Email',
        'username' => 'Username',
        'password' => 'Password',
        'password_repeat' => 'Password repeat'
    ];

    public $paginate_count;
    protected $listeners = ['screen'];

    public function mount() {

        $id = $this->form['id'];
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

    public function create() {

        $rules = [
            'course_id' => 'required|integer|exists:afears_course,id',
            'student_number' => 'required|unique:afears_student,student_number',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'string',
            'gender' => 'required|integer|in:1,2,3,4',
            'birthday' => 'required',
            'year_level' => 'required|integer|in:1,2',
            'email' => 'required|email|unique:afears_student,email',
            'username' => 'required|string|unique:afears_student,username',
            'password' => 'required|string|min:8|same:password_repeat',
            'password_repeat' => 'required|string|min:8|same:password'
        ];

        $this->validate($rules, [], $this->attr);

        if($this->image instanceof TemporaryUploadedFile) {

            $rules = [
                'image' => 'image|mimes:jpeg,png,jpg|max:2000'
            ];

            $this->validate($rules, [], $this->attr);

            $temp_filename = time();
            $extension =$this->image->getClientOriginalExtension();

            $filename = $temp_filename . '.' . $extension;

            $this->image->storeAs('public/images/student', $filename);

        }


        try {

            $model = new StudentModel;

            $model->course_id = $this->course_id;
            $model->student_number = $this->student_number;
            $model->firstname = $this->firstname;
            $model->lastname = $this->lastname;
            $model->middlename = $this->middlename;
            $model->gender = $this->gender;
            $model->birthday = $this->birthday;
            $model->year_level = $this->year_level;
            $model->image = $filename ?? null;
            $model->email = $this->email;
            $model->username = $this->username;
            $model->password = Hash::make($this->password);

            $model->save();

            if($this->is_email) {
                $data = [
                    'view' => 'mail.notify',
                    'subject' => 'Student Account Creation',
                    'name' => ucwords($this->firstname . ' ' . $this->lastname),
                    'role' => 'student',
                    'number' => $this->student_number,
                    'username' => $this->username,
                    'password' => $this->password,
                ];

                try {
                    Mail::to($this->email)
                    ->send(new Mailer($data));

                    $this->dispatch('alert');
                    session()->flash('alert', [
                        'message' => 'Saved and email sent.'
                    ]);

                } catch (\Throwable $th) {
                    $this->dispatch('alert');
                    session()->flash('alert', [
                        'message' => $th->getMessage()
                    ]);
                }
            } else {
                $this->dispatch('alert');
                session()->flash('alert', [
                    'message' => 'Saved.'
                ]);
            }

            $this->resetExcept('form', 'initPaginate');

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

            $this->validate($rules, [], $this->attr);

            if($this->image instanceof TemporaryUploadedFile) {

                $rules = [
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:5000'
                ];

                $this->validate($rules, [], $this->attr);

                Storage::disk('public')->delete('images/student/' . $model->image);

                $temp_filename = time();
                $extension = $this->image->getClientOriginalExtension();

                $filename = $temp_filename . '.' . $extension;

                $this->image->storeAs('public/images/student', $filename);
                $this->image = $filename;
                $model->image = $filename;

            }

            if(!empty($this->password && !empty($this->password_repeat))) {

                $rules = [
                    'password' => 'required|string|min:8|same:password_repeat',
                    'password_repeat' => 'required|string|min:8|same:password'
                ];

                $this->validate($rules, [], $this->attr);

                try {
                    $model->password = Hash::make($this->password);
                    $model->save();
                    $this->except('password', 'password_repeat');
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

        $model = StudentModel::where('id', $this->id)->first();

        if($model) {
            Storage::disk('public')->delete('images/student/' . $model->image);
            $model->delete();
            return redirect()->route('admin.accounts.student');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }
    }

    public function image_remove() {

        $rules = [
            'id' => 'required|exists:afears_student,id'
        ];

        $this->validate($rules);

        $model = StudentModel::find($this->id);

        Storage::disk('public')->delete('images/student/' . $model->image);

        $model->image = null;
        $model->save();

        $this->dispatch('alert');
        session()->flash('alert', [
            'message' => 'Image removed.'
        ]);

        $this->reset('image');

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

        $students = StudentModel::with(['courses.departments.branches'])
            ->when(strlen($this->search['type']) >= 1, function ($sQuery) {
                $sQuery->where(function($query) {
                    $query->where('firstname', 'like', '%' . $this->search['type'] . '%');
                });
                $sQuery->orWhereHas('courses', function ($cQuery) {
                    $cQuery->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search['type'] . '%')
                            ->orWhere('code', 'like', '%' . $this->search['type'] . '%');
                    })->orWhereHas('departments', function ($dQuery) {
                        $dQuery->where('name', 'like', '%' . $this->search['type'] . '%');
                        $dQuery->orWhereHas('branches', function ($bQuery) {
                            $bQuery->where('name', 'like', '%' . $this->search['type'] . '%');
                        });
                    });
                });
            })
            ->when($this->search['select'] != '', function ($query) {
                $query->whereHas('courses.departments.branches', function ($subQuery) {
                    $subQuery->where('branch_id', $this->search['select']);
                });
            })
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('courses.departments.branches', function($subQuery) use ($assigned_branch) {
                    $subQuery->where('branch_id', $assigned_branch);
                });
            })
            ->paginate($this->paginate_count);

        $branches = BranchModel::with('departments')
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->where('id', $assigned_branch);
            })
            ->get();

        $this->initPaginate();

        $data = [
            'branches' => $branches,
            'students' => $students
        ];

        return view('livewire.admin.student', compact('data'));

    }
}

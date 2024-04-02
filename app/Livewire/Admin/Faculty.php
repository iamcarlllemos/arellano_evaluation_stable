<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use App\Models\BranchModel;
use App\Models\FacultyModel;

use App\Traits\Account;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;

class Faculty extends Component
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
    public $department_id;
    public $employee_number;
    public $firstname;
    public $lastname;
    public $middlename;
    public $gender;
    public $image;
    public $email;
    public $username;
    public $password;
    public $password_repeat;

    public $initPaginate = false;

    public $attr = [
        'department_id' => 'Department name',
        'employee_number' => 'Employee number',
        'firstname' => 'First name',
        'lastname' => 'Last name',
        'middlename' => 'Middle name',
        'gender' => 'Gender',
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
        $data = FacultyModel::find($id);

        $this->id = $id;
        $this->department_id = $data->department_id ?? '';
        $this->employee_number = $data->employee_number ?? '';
        $this->firstname = $data->firstname ?? '';
        $this->lastname = $data->lastname ?? '';
        $this->middlename = $data->middlename ?? '';
        $this->gender = $data->gender ?? '';
        $this->image = $data->image ?? '';
        $this->email = $data->email ?? '';
        $this->username = $data->username ?? '';
    }

    public function create() {

        $rules = [
            'department_id' => 'required|integer|exists:afears_department,id',
            'employee_number' => 'required|unique:afears_faculty,employee_number',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'string',
            'email' => 'required|email|unique:afears_faculty,email',
            'gender' => 'required|integer|in:1,2,3',
            'image' => 'image|mimes:jpeg,png,jpg|max:2000',
            'username' => 'required|unique:afears_faculty,username',
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

            $this->image->storeAs('public/images/faculty', $filename);

        }

        try {

            $model =  new FacultyModel;
            $model->department_id = $this->department_id;
            $model->employee_number = $this->employee_number;
            $model->firstname = $this->firstname;
            $model->lastname = $this->lastname;
            $model->middlename = $this->middlename;
            $model->email = $this->email;
            $model->gender = $this->gender;
            $model->image = $filename ?? null;
            $model->username = $this->username;
            $model->password = Hash::make($this->password);

            $model->save();

            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Saved.'
            ]);

            $this->department_id = '';
            $this->employee_number = '';
            $this->firstname = '';
            $this->lastname = '';
            $this->middlename = '';
            $this->gender = '';
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

        $model = FacultyModel::where('id', $this->id)->first();

        if ($model) {

            $rules = [
                'department_id' => 'required|integer|exists:afears_department,id',
                'employee_number' => [
                    'required',
                    Rule::unique('afears_faculty')->where(function($query) {
                        return $query->where('employee_number', $this->employee_number);
                    })->ignore($this->id)
                ],
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'middlename' => 'string',
                'gender' => 'required|integer|in:1,2,3',
                'email' =>  [
                    'required',
                    'string',
                    'email',
                    Rule::unique('afears_faculty')->where(function($query) {
                        return $query->where('email', $this->email);
                    })->ignore($this->id)
                ],
            ];

            $this->validate($rules, [], $this->attr);

            if($this->image instanceof TemporaryUploadedFile) {

                $rules = [
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:5000'
                ];

                $this->validate($rules, [], $this->attr);

                Storage::disk('public')->delete('images/faculty/' . $model->image);

                $temp_filename = time();
                $extension = $this->image->getClientOriginalExtension();

                $filename = $temp_filename . '.' . $extension;

                $this->image->storeAs('public/images/faculty', $filename);
                $this->image = $filename;
                $model->image = $filename;

            }

            try {

                $model->department_id = $this->department_id;
                $model->employee_number = $this->employee_number;
                $model->firstname = $this->firstname;
                $model->lastname = $this->lastname;
                $model->middlename = $this->middlename;
                $model->gender = $this->gender;
                $model->email = $this->email;
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

        $model = FacultyModel::where('id', $this->id)->first();

        if($model) {
            Storage::disk('public')->delete('images/faculty/' . $model->image);
            $model->delete();
            return redirect()->route('admin.accounts.faculty');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }
    }

    public function image_remove() {

        $rules = [
            'id' => 'required|exists:afears_faculty,id'
        ];

        $this->validate($rules);

        $model = FacultyModel::find($this->id);

        Storage::disk('public')->delete('images/faculty/' . $model->image);

        $model->image = null;
        $model->save();

        $this->dispatch('alert');
        session()->flash('alert', [
            'message' => 'Image removed.'
        ]);

        $this->image = '';

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

        $faculty = FacultyModel::with(['departments.branches'])
            ->when(strlen($this->search['type']) >= 1, function ($sQuery) {
                $sQuery->where(function($query) {
                    $query->where('firstname', 'like', '%' . $this->search['type'] . '%');
                });
                $sQuery->orWhereHas('departments', function ($dQuery) {
                    $dQuery->where('name', 'like', '%' . $this->search['type'] . '%');
                    $dQuery->orWhereHas('branches', function ($bQuery) {
                        $bQuery->where('name', 'like', '%' . $this->search['type'] . '%');
                    });
                });
            })
            ->when($this->search['select'] != '', function ($query) {
                $query->whereHas('departments.branches', function ($subQuery) {
                    $subQuery->where('branch_id', $this->search['select']);
                });
            })
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('departments.branches', function($subQuery) use ($assigned_branch) {
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
            'faculty' => $faculty
        ];

        return view('livewire.admin.faculty', compact('data'));

    }
}

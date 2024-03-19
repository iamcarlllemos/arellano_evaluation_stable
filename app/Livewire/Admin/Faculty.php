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

class Faculty extends Component
{

    use WithFileUploads;

    public $form;
    public $select;
    public $search;

    public $id;
    public $department_id;
    public $employee_number;
    public $firstname;
    public $lastname;
    public $middlename;
    public $birthday;
    public $year_level;
    public $gender;
    public $image;
    public $email;
    public $username;


    public function mount(Request $request) {

        $id = $request->input('id');
        $data = FacultyModel::find($id);

        $this->id = $id;
        $this->department_id = $data->department_id ?? '';
        $this->employee_number = $data->employee_number ?? '';
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
            'employee_number' => 'required|unique:afears_faculty,employee_number',
            'department_id' => 'required|integer|exists:afears_department,id',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'string',
            'email' => 'required|email|unique:afears_faculty,email',
            'gender' => 'required|integer|in:1,2,3',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5000',
        ];

        $this->validate($rules);

        $temp_filename = time();
        $extension =$this->image->getClientOriginalExtension();

        $filename = $temp_filename . '.' . $extension;

        $data = [
            'department_id' => $this->department_id,
            'employee_number' => $this->employee_number,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'middlename' => $this->middlename,
            'email' => $this->email,
            'gender' => $this->gender,
            'image' => $filename,
        ];

        try {

            FacultyModel::create($data);
            $this->image->storeAs('public/images/faculty', $filename);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Faculty `' . ucwords($this->firstname . ' ' . $this->lastname) . '` created successfully'
            ]);

            $this->department_id = '';
            $this->employee_number = '';
            $this->firstname = '';
            $this->lastname = '';
            $this->middlename = '';
            $this->gender = '';
            $this->image = '';
            $this->email = '';

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

            $this->validate($rules);

            if($this->image instanceof TemporaryUploadedFile) {

                $rules = [
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:5000'
                ];

                $this->validate($rules);

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

                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Faculty `' . ucwords($this->firstname . ' ' . $this->lastname) . '` updated successfully'
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
            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Faculty `' . ucwords($this->firstname . ' ' . $this->lastname) . '` deleted successfully'
            ]);
            return redirect()->route('admin.accounts.faculty');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
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
            ->when($this->select != '', function ($query) {
                $query->whereHas('departments.branches', function ($subQuery) {
                    $subQuery->where('branch_id', $this->select);
                });
            })
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('departments.branches', function($subQuery) use ($assigned_branch) {
                    $subQuery->where('branch_id', $assigned_branch);
                });
            })
            ->get();


        $branches = BranchModel::with('departments')
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->where('id', $assigned_branch);
            })
            ->get();

        $faculty = $faculty->isEmpty() ? [] : $faculty;

        $data = [
            'branches' => $branches,
            'faculty' => $faculty
        ];


        return view('livewire.admin.faculty', compact('data'));

    }
}

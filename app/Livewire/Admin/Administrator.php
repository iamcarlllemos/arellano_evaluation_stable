<?php

namespace App\Livewire\Admin;

use App\Models\BranchModel;
use App\Models\StudentModel;
use App\Models\User;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


class Administrator extends Component
{

    use WithFileUploads;

    public $form;
    public $select;
    public $search;

    public $id;
    public $firstname;
    public $lastname;
    public $image;
    public $email;
    public $role;
    public $branch;
    public $username;
    public $password;
    public $password_repeat;

    public function mount(Request $request) {

        $id = $request->input('id');
        $action = $request->input('action');
        $data = User::find($id);

        if(in_array($action, ['update', 'delete'])) {
            $name = explode(' ', $data->name);
        }

        $this->id = $id;
        $this->firstname = $name[0] ?? '';
        $this->lastname = $name[1] ?? '';
        $this->image = $data->profile_photo_path ?? '';
        $this->email = $data->email ?? '';
        $this->role = $data->role ?? '';
        $this->branch = $data->assigned_branch ?? '';
        $this->username = $data->username ?? '';
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {

        $rules = [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:afears_student,email',
            'role' => 'required|in:admin,superadmin|string',
            'username' => 'required|string|unique:afears_student,username',
            'password' => 'required|string|min:8|same:password_repeat',
            'password_repeat' => 'required|string|min:8|same:password'
        ];


        $this->validate($rules);


        $data = [
            'name' => $this->firstname . ' ' . $this->lastname,
            'email' => $this->email,
            'role' => $this->role,
            'username' => $this->username,
            'assigned_branch' => 0,
            'password' => Hash::make($this->password),
        ];

        try {

            User::create($data);
            

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Student `' . ucwords($this->firstname . ' ' . $this->lastname) . '` created successfully'
            ]);

            $this->firstname = '';
            $this->lastname = '';
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

        $model = User::where('id', $this->id)->first();
    
        if ($model) {

            $rules = [
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'email' =>  [
                    'required',
                    'string',
                    'email',
                    Rule::unique('users')->where(function($query) {
                        return $query->where('email', $this->email);
                    })->ignore($this->id)
                ],
                'username' => [
                    'required',
                    'string',
                    Rule::unique('users')->where(function($query) {
                        return $query->where('username', $this->username);
                    })->ignore($this->id)
                ],
                'role' => 'required|in:admin,superadmin',
                'branch' => 'required|exists:afears_branch,id|integer'
            ];
    
            $this->validate($rules);
            
            if($this->image instanceof TemporaryUploadedFile) {

                $rules = [
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:5000'
                ];

                $this->validate($rules);

                Storage::disk('public')->delete('images/users/' . $model->image);
        
                $temp_filename = time();
                $extension = $this->image->getClientOriginalExtension();
        
                $filename = $temp_filename . '.' . $extension;
        
                $this->image->storeAs('public/images/users', $filename);
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

                $model->name = $this->firstname . ' ' . $this->lastname;
                $model->email = $this->email;
                $model->username = $this->username;
                $model->role = $this->role;
                $model->assigned_branch = $this->branch;
                $model->save();
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'User `' . ucwords($this->firstname . ' ' . $this->lastname) . '` updated successfully'
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

        $model = User::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'User `' . ucwords($this->firstname . ' ' . $this->lastname) . '` deleted successfully'
            ]);
            return redirect()->route('admin.accounts.administrator');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }
    }
    public function render(Request $request) {
        
        $action = $request->input('action') ?? '';

        $users = User::where('id', '!=', auth()->user()->id)->get();       

    
        $users = $users->isEmpty() ? [] : $users;
        $data = [
            'branches' => BranchModel::with('departments')->get(),
            'users' => $users
        ];


        return view('livewire.admin.administrator', compact('data'));
       
    }
}

<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Traits\Account;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use App\Models\BranchModel;
use App\Models\User;
use Livewire\WithPagination;

class Administrator extends Component
{

    use WithFileUploads;
    use Account;
    use WithPagination;

    public $form;

    public $search = [
        'type' => '',
        'select' => '',
        'role' => ''
    ];

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

    public $initPaginate = false;

    public $attr = [
        'firstname' => 'First name',
        'lastname' => 'Last name',
        'middlename' => 'Middle name',
        'email' => 'Email',
        'role' => 'Admin role',
        'branch' => 'Assigned branch',
        'username' => 'Username',
        'password' => 'Password',
        'password_repeat' => 'Password repeat'
    ];

    public $paginate_count;
    protected $listeners = ['screen'];

    public function mount() {

        $id = $this->form['id'];
        $action = $this->form['action'];
        $data = User::find($id);

        if(in_array($action, ['update', 'delete'])) {
            $name = explode(' ', $data->name);
        }

        $this->id = $id;
        $this->firstname = $name[0] ?? '';
        $this->lastname = $name[1] ?? '';
        $this->email = $data->email ?? '';
        $this->role = $data->role ?? '';
        $this->branch = $data->assigned_branch ?? '';
        $this->username = $data->username ?? '';
    }

    public function create() {

        $rules = [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,superadmin|string',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8|same:password_repeat',
            'password_repeat' => 'required|string|min:8|same:password'
        ];


        $this->validate($rules, [], $this->attr);

        try {

            $model = new User;

            $model->name = $this->firstname . ' ' . $this->lastname;
            $model->email = $this->email;
            $model->role = $this->role;
            $model->username = $this->username;
            $model->assigned_branch = $this->branch;
            $model->password = $this->password;

            $model->save();

            $this->resetExcept('form', 'initPaginate');

            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Saved.'
            ]);

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

            $this->validate($rules, [], $this->attr);

            if(!empty($this->password && !empty($this->password_repeat))) {

                $rules = [
                    'password' => 'required|string|min:8|same:password_repeat',
                    'password_repeat' => 'required|string|min:8|same:password'
                ];

                $this->validate($rules, [], $this->attr);

                try {
                    $model->password = Hash::make($this->password);
                    $model->save();
                    $this->reset('password', 'password_repeat');
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

        $model = User::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            return redirect()->route('admin.accounts.administrator');
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

    public function render() {

        $users = User::when(!empty($this->search['select']), function($query) {
            $query->where('assigned_branch', $this->search['select']);
        })->when(!empty($this->search['role']), function($query) {
            $query->where('role', $this->search['role']);
        })->when(strlen($this->search['type']) > 1, function($query) {
            $query->where('name', 'like', '%' . $this->search['type'] . '%')
                ->OrWhere('email', 'like', '%' . $this->search['type'] . '%')
                ->OrWhere('username', 'like', '%' . $this->search['type'] . '%');
        })->where('id', '!=', $this->admin()->id)
        ->paginate($this->paginate_count);

        $this->initPaginate();

        $data = [
            'branches' => BranchModel::with('departments')->get(),
            'users' => $users
        ];

        return view('livewire.admin.administrator', compact('data'));

    }
}


<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Traits\ExecuteRule;
use App\Traits\Account;

use App\Models\BranchModel;
use App\Models\DepartmentModel;

class Department extends Component
{

    use WithFileUploads;
    use ExecuteRule;
    use Account;
    use WithPagination;

    public $form;

    public $search = [
        'select' => '',
        'type' => ''
    ];

    public $id;

    public $branch_id;
    public $name;

    public $initPaginate = false;

    public $attr = [
        'branch_id' => 'Branch name',
        'name' => 'Department name'
    ];

    public $paginate_count;
    protected $listeners = ['screen'];

    public function mount(Request $request) {
        $id = $request->input('id');
        $data = DepartmentModel::find($id);

        $this->id = $id;
        $this->branch_id = $data->branch_id ?? '';
        $this->name = $data->name ?? '';
    }

    public function placeholder() {
        return view('livewire.admin.placeholder');
    }

    public function create() {

        $rules = [
            'branch_id' => 'required|integer|exists:afears_branch,id',
            'name' => [
                'required',
                'string',
                'min:4',
                Rule::unique('afears_department')->where(function ($query) {
                    return $query->where('branch_id', $this->branch_id);
                })
            ]
        ];

        $this->validate($rules, [], $this->attr);

        try {

            $model = new DepartmentModel;
            $model->branch_id = $this->branch_id;
            $model->name = ucfirst($this->name);

            $model->save();

            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Saved.'
            ]);

            $this->branch_id = '';
            $this->name = '';

        } catch (\Exception $e) {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update() {

        $model = DepartmentModel::where('id', $this->id)->first();

        if ($model) {

            $rules = [
                'branch_id' => 'required|integer|exists:afears_branch,id',
                'name' => [
                    'required',
                    'string',
                    'min:4',
                    Rule::unique('afears_department')->where(function ($query) {
                        return $query->where('branch_id', $this->id);
                    })->ignore($this->id)
                ]
            ];

            $this->validate($rules, [], $this->attr);

            try {

                $model->branch_id = $this->branch_id;
                $model->name = ucfirst($this->name);

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

        $model = DepartmentModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            return redirect()->route('admin.programs.departments');
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

    public function render(Request $request) {

        $action = $request->input('action');

        $role = $this->admin()->role;
        $assigned_branch = $this->admin()->assigned_branch;

        $departments = DepartmentModel::with('branches')
            ->when(strlen($this->search['type']) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search['type'] . '%');
            })
            ->when($this->search['select'] != '', function ($query) {
                $query->where('branch_id', $this->search['select']);
            })
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->where('branch_id', $assigned_branch);
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
            'departments' => $departments
        ];

        return view('livewire.admin.department', compact('data'));
    }
}

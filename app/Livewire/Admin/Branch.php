<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

use App\Models\BranchModel;


class Branch extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $form;

    public $search = [
        'type' => ''
    ];

    public $id;

    public $name;
    public $image;
    public $initPaginate = false;

    public $attr = [
        'name' => 'Branch name',
        'image' => 'Branch image',
    ];

    public $paginate_count;
    protected $listeners = ['screen'];

    public function mount() {

        $id = $this->form['id'];
        $data = BranchModel::find($id);

        $this->id = $id;
        $this->name = $data->name ?? '';
        $this->image = $data->image ?? '';

    }

    public function create() {

        $rules = [
            'name' => 'required|string|min:4|unique:afears_branch',
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

            $this->image->storeAs('public/images/branches', $filename);

        }

        try {

            $model = new BranchModel;

            $model->name = ucfirst($this->name);
            $model->image = $filename ?? null;

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

        $model = BranchModel::where('id', $this->id)->first();

        if ($model) {

            $rules = [
                'name' => [
                    'required',
                    'string',
                    'min:4',
                    Rule::unique('afears_branch')->ignore($this->id),
                ],
            ];

            $this->validate($rules, [], $this->attr);

            if($this->image instanceof TemporaryUploadedFile) {

                $rules = [
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:5000'
                ];

                $this->validate($rules, [], $this->attr);

                Storage::disk('public')->delete('images/branches/' . $model->image);

                $temp_filename = time();
                $extension = $this->image->getClientOriginalExtension();

                $filename = $temp_filename . '.' . $extension;

                $this->image->storeAs('public/images/branches', $filename);
                $this->image = $filename;

                $model->image = $filename;

            }

            try {

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

        $model = BranchModel::where('id', $this->id)->first();

        if($model) {
            Storage::disk('public')->delete('images/branches/' . $model->image);
            $model->delete();
            return redirect()->route('admin.programs.branches');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }

    }

    public function image_remove() {

        $rules = [
            'id' => 'required|exists:afears_branch,id'
        ];

        $this->validate($rules);

        $model = BranchModel::find($this->id);

        Storage::disk('public')->delete('images/branches/' . $model->image);

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

    public function render() {

        $data = BranchModel::when(strlen($this->search['type'] >= 1), function($query) {
            $query->where('name', 'like', '%' . $this->search['type'] . '%');
        })->paginate($this->paginate_count);

        $this->initPaginate();

        return view('livewire.admin.branch', compact('data'));
    }
}

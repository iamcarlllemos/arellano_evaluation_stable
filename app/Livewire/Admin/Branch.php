<?php

namespace App\Livewire\Admin;

use App\Models\BranchModel;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class Branch extends Component
{

    use WithFileUploads;

    public $form;
    public $search;
    public $id;
    public $status;
    public $name;
    public $image;

    public function mount(Request $request) {
        $id = $request->input('id');
        $data = BranchModel::find($id);

        $this->id = $id;
        $this->name = $data->name ?? '';
        $this->image = $data->image ?? '';
    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {
        $rules = [
            'name' => 'required|string|min:4|unique:afears_branch',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5000'
        ];

        $this->status = 'failed';

        $this->validate($rules);

        $temp_filename = time();
        $extension =$this->image->getClientOriginalExtension();

        $filename = $temp_filename . '.' . $extension;

        $data = [
            'name' => htmlspecialchars($this->name),
            'image' =>  $filename
        ];

        
        try {

            BranchModel::create($data);
            $this->image->storeAs('public/images/branches', $filename);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Branch `' . ucwords($this->name) . '` created successfully'
            ]);

            $this->name = '';
            $this->image = '';

        } catch (\Exception $e) {

            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }       
    }

    public function update(Request $request) {

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
    
            $this->validate($rules);

            if($this->image instanceof TemporaryUploadedFile) {

                $rules = [
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:5000'
                ];

                $this->validate($rules);

                Storage::disk('public')->delete('images/branches/' . $model->image);
        
                $temp_filename = time();
                $extension = $this->image->getClientOriginalExtension();
        
                $filename = $temp_filename . '.' . $extension;
        
                $this->image->storeAs('public/images/branches', $filename);
                $this->image = $filename;
                $model->image = $filename;

            }
    
            try {

                $model->name = $this->name;
                $model->save();
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Branch `' . ucwords($this->name) . '` updated successfully'
                ]);
    
            } catch (\Exception $e) {
    
                session()->flash('flash', [
                    'status' => 'failed',
                    'message' => $e->getMessage()
                ]);
            }    
        }
    }

    public function delete(Request $request) {

        $model = BranchModel::where('id', $this->id)->first();

        if($model) {
            Storage::disk('public')->delete('images/branches/' . $model->image);
            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Branch `'.$model->name.'` deleted successfully'
            ]);
            return redirect()->route('admin.programs.branches');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }

    }
    public function render() {
        
        $data = BranchModel::when(strlen($this->search >= 1), function($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->get();

        return view('livewire.admin.branch', compact('data'));
    }
}

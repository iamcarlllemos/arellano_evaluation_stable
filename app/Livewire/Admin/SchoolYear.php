<?php

namespace App\Livewire\Admin;

use App\Models\SchoolYearModel;
use App\Models\SubjectModel;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class SchoolYear extends Component
{

    public $form;
    public $select;
    public $search;

    public $id;
    public $name;
    public $start_year;
    public $semester;
    public $status;

    public function mount(Request $request) {

        $id = $request->input('id');

        $data = SchoolYearModel::find($id);

        $this->id = $id;
        $this->name = $data->name ?? '';
        $this->start_year = $data->start_year ?? '';
        $this->semester = $data->semester ?? '';
        $this->status = $data->status ?? '';

    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function create() {

        $rules = [
            'name' => 'required|min:4',
            'start_year' => [
                'required',
                Rule::unique('afears_school_year')->where(function ($query) {
                    return $query->where('start_year', $this->start_year)
                        ->where('end_year', $this->start_year + 1)
                        ->where('semester', $this->semester);
                })
            ],
            'semester' => [
                'required',
                Rule::unique('afears_school_year')->where(function ($query) {
                    return $query->where('start_year', $this->start_year)
                        ->where('end_year', $this->start_year + 1)
                        ->where('semester', $this->semester);
                })
            ],
            'status' => 'required|in:0,1,2,3'
        ];

        $this->validate($rules);

        $data = [
            'name' =>  htmlspecialchars($this->name),
            'start_year' => htmlspecialchars($this->start_year),
            'end_year' => htmlspecialchars($this->start_year + 1),
            'semester' => htmlspecialchars($this->semester),
            'status' => $this->status
        ];

        try {

            SchoolYearModel::create($data);

            session()->flash('flash', [
                'status' => 'success',
                'message' => 'School Year `' . ucwords($this->name) . '` created successfully'
            ]);

            $this->name = '';
            $this->start_year = '';
            $this->semester = '';
            $this->status = '';

        } catch (\Exception $e) {

            session()->flash('flash', [
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }       
    }

    public function update() {

        $model = SchoolYearModel::where('id', $this->id)->first();
    
        if ($model) {

            $rules = [
                'name' => 'required|min:4',
                'start_year' => [
                    'required',
                    Rule::unique('afears_school_year')->where(function ($query) {
                        return $query->where('start_year', $this->start_year)
                            ->where('end_year', $this->start_year + 1)
                            ->where('semester', $this->semester);
                    })->ignore($this->id)
                ],
                'semester' => [
                    'required',
                    Rule::unique('afears_school_year')->where(function ($query) {
                        return $query->where('start_year', $this->start_year)
                            ->where('end_year', $this->start_year + 1)
                            ->where('semester', $this->semester);
                    })->ignore($this->id)
                ],
                'status' => 'required|in:0,1,2,3'
            ];
    
            $this->validate($rules);
            
            try {

                $model->name = htmlspecialchars($this->name);
                $model->semester = $this->semester;
                $model->status = $this->status;

                $model->save();
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'School year `' . ucwords($this->name) . '` updated successfully'
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

        $model = SchoolYearModel::where('id', $this->id)->first();

        if($model) {
            $model->delete();
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'School year `'.$model->name.'` deleted successfully'
            ]);
            return redirect()->route('admin.programs.school-year');
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$this->id.'`. Unable to delete.'
            ]);
        }
    }
    public function render(Request $request) {
        
        $action = $request->input('action') ?? '';

        if($action == 'open') {
            $view = $request->input('view');
            if(in_array($view, ['courses'])) {
                $id = $request->input('id');
                $this->select = $id;
            }
        }

        $school_year = SchoolYearModel::
            when(strlen($this->search) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('start_year', 'like', '%' . $this->search . '%')
                    ->orWhere('end_year', 'like', '%' . $this->search . '%')
                    ->orWhere(function($query) {
                        $query->whereRaw("CONCAT(start_year, '-', end_year) LIKE ?", ['%' . $this->search . '%']);
                    });
            })->get();
            
        $school_year = $school_year->isEmpty() ? [] : $school_year;

        $data = [
            'school_year' => $school_year
        ];

        return view('livewire.admin.school-year', compact('data'));
    }
}

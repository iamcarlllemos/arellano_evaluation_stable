<?php

namespace App\Livewire\Faculty;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

use App\Models\FacultyTemplateModel;

class Subject extends Component
{

    public $evaluate;
    public $semester;
    public $subject;

    public function mount(Request $request) {

        $evaluate = $request->input('evaluate');
        $semester = $request->input('semester');

        $this->evaluate = $evaluate;
        $this->semester = $semester;

        $input = [
            'id' => $evaluate,
            'semester' => $semester
        ];

        $rules = [
            'id' => [
                'required',
                'integer',
                Rule::exists('afears_school_year')->where(function($query) use($evaluate, $semester) {
                    $query->where('id', $evaluate)
                        ->where('semester', $semester)
                        ->where('status', '1');
                })
            ],
            'semester' => [
                'required',
                'integer'
            ]
        ];


        $validate = Validator::make($input, $rules);

        if($validate->fails()) {
            return redirect()->route('faculty.dashboard');
        }

        $id = auth()->guard('faculty')->user()->id;

        $data = FacultyTemplateModel::with('faculty.templates.curriculum_template.subjects.courses.departments.branches')
            ->where('faculty_id', $id)
            ->get()[0];

        $this->subject = $data;
    }

    public function render()
    {
        return view('livewire.faculty.subject');
    }
}

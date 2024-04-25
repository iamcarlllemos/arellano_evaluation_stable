<?php

namespace App\Livewire\Faculty;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

use App\Models\StudentModel;
use App\Models\CurriculumTemplateModel;
use App\Models\FacultyModel;
use App\Models\FacultyTemplateModel;

class Subject extends Component
{

    public $evaluate;
    public $semester;
    public $subjects;

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

        $user_id = auth()->guard('faculty')->user()->id;

        $user_data = FacultyModel::find($user_id);


        $data = FacultyTemplateModel::with('faculty.templates.curriculum_template.subjects.courses.departments.branches')
            ->where('faculty_id', $user_id)
            ->select('*', DB::raw('(CASE WHEN EXISTS (
                    SELECT 1
                    FROM afears_response
                    INNER JOIN afears_curriculum_template ON afears_response.template_id = afears_curriculum_template.id
                    WHERE user_id = ' . $user_id . '
                        AND evaluation_id = ' . $evaluate . '
                        AND semester = ' . $semester . '
                ) THEN true ELSE false END) AS is_exists'))
            ->get();

        dd($data->toArray());
        $this->subjects = $data;
    }

    public function render()
    {
        return view('livewire.faculty.subject');
    }
}

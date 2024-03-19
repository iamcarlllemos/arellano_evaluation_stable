<?php

namespace App\Livewire\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

use App\Models\StudentModel;
use App\Models\CurriculumTemplateModel;

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
                        ->where('status', 1);
                })
            ],
            'semester' => [
                'required',
                'integer'
            ]
        ];

        $validate = Validator::make($input, $rules);


        if($validate->fails()) {
            return redirect()->route('user.dashboard');
        }

        $user_id = auth()->guard('users')->user()->id;

        $user_data = StudentModel::find($user_id);
        $course = $user_data->course_id;
        $year = $user_data->year_level;
    
    
        $data = CurriculumTemplateModel::with('subjects.courses.departments.branches')
            ->where('course_id', $course)
            ->where('year_level', $year)
            ->where('subject_sem', $semester)
            ->select('*', DB::raw('(CASE WHEN EXISTS (
                    SELECT 1
                    FROM afears_response
                    WHERE user_id = ' . $user_id . '
                        AND evaluation_id = ' . $evaluate . '
                        AND template_id = afears_curriculum_template.id
                        AND semester = ' . $semester . '
                ) THEN true ELSE false END) AS is_exists'))
            ->get();

        $this->subjects = $data;
    }

    public function render()
    {
        return view('livewire.user.subject');
    }
}

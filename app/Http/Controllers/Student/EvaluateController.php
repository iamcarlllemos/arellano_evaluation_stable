<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FacultyModel;

class EvaluateController extends Controller
{
    public function index(Request $request) {

        $course = auth()->guard('students')->user()->course_id;
        $year = auth()->guard('students')->user()->year_level;
        $semester = $request->input('semester');
        $subject_id = $request->input('subject');
        $template_id = $request->input('template');

        $dirty_faculty = FacultyModel::with('templates.curriculum_template.courses.departments.branches')
        ->whereHas('templates', function($subquery) use ($template_id) {
            $subquery->where('template_id', $template_id);
        })
        ->whereHas('templates.curriculum_template', function ($subquery) use ($course, $year, $semester, $subject_id) {
            $subquery->where('course_id', $course)
                ->where('year_level', $year)
                ->where('subject_sem', $semester)
                ->where('subject_id', $subject_id);
        })
        ->get();

        $faculty = [];

        foreach($dirty_faculty as $item) {
            $key = $item->templates[0]->curriculum_template[0]->courses->departments->branches->name;
            if(!isset($faculty[$key])) {
                $faculty[$key] = (object) [
                    'id' => $item->templates[0]->curriculum_template[0]->courses->departments->branches->id,
                    'name' => $key,
                    'branches' => []
                ];
            }

            $faculty[$key]->branches[] = (object) [
                'id' =>  $item->id,
                'name' => $item->firstname . ' ' . $item->lastname . ' - ('.$item->templates[0]->curriculum_template[0]->courses->name.') '
            ];
        }

        $step = session('response')['step'];

        $data = [
            'breadcrumbs' => 'Dashboard,evaluate',
            'livewire' => [
                'component' => 'student.evaluate',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'action' => 'save',
                        'save' => [
                            'title' => 'Create Course',
                            'subtitle' => 'Create or add new courses.',
                            'data' => [
                                'faculty_id' => [
                                    'label' => 'Faculty Name',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'data' => $faculty,
                                        'group' => 'branches',
                                        'no_data' => 'No Faculty Found'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'start_time' => [
                                    'label' => 'Start Time',
                                    'type' => 'time',
                                    'placeholder' => '',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6'
                                ],
                                'end_time' => [
                                    'label' => 'End Time',
                                    'type' => 'time',
                                    'placeholder' => '',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6'
                                ]
                            ]
                        ],
                    ],
                ]
            ],
        ];

        return view('student.template', compact('data'));
    }
}

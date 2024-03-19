<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FacultyModel;

class EvaluateController extends Controller
{
    public function index(Request $request) {

        $course = auth()->guard('users')->user()->course_id;
        $year = auth()->guard('users')->user()->year_level;
        $semester = $request->input('semester');

        $dirty_faculty = FacultyModel::with('templates.curriculum_template.courses.departments.branches')->where(function($query) use($course, $year, $semester) {
            $query->whereHas('templates.curriculum_template', function ($subquery) use ($course, $year, $semester) {
                $subquery->where('course_id', $course);
                $subquery->where('year_level', $year);
                $subquery->where('subject_sem', $semester);
            });
        })->get();

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
                'component' => 'user.evaluate',
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

        return view('user.template', compact('data'));
    }
}

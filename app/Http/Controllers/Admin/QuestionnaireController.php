<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\QuestionnaireModel;
use App\Models\SchoolYearModel;

class QuestionnaireController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action');
        $slug = $request->input('slug');

        if(in_array($action, ['update', 'delete'])) {
            $data = QuestionnaireModel::where('slug', $slug);
            if(!$data->exists()) {
                return redirect()->route('admin.programs.questionnaire');
            }

        }

        $school_year = [];

        $dirty_sy = SchoolYearModel::all();

        foreach($dirty_sy as $year) {
            $school_year[] = (object) [
                'id' => $year->id,
                'name' => 'SY: ' . $year->start_year . ' - ' . $year->end_year . ' ('.$year->name.')',
            ];
        }

        $data = [
            'breadcrumbs' => 'Dashboard,programs,questionnaire',
            'livewire' => [
                'component' => 'admin.questionnaire',
                'data' => [
                    'lazy' => true,
                    'form' => [
                        'slug' => $slug,
                        'action' => $action,
                        'index' => [
                            'title' => 'All Questionnaires',
                            'subtitle' => 'List of all questionnaires created.'
                        ],
                        'create' => [
                            'title' => 'Create Questionnaire',
                            'subtitle' => 'Create or add new criteria.',
                            'data' => [
                                'school_year_id' => [
                                    'label' => 'School Year',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $school_year,
                                        'no_data' => 'Create school year first'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ],
                                'name' => [
                                    'label' => 'Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ]
                            ]
                        ],
                        'update' => [
                            'title' => 'Update Questionnaire',
                            'subtitle' => 'Create or add new criteria.',
                            'data' => [
                                'school_year_id' => [
                                    'label' => 'School Year',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $school_year,
                                        'no_data' => 'Create school year first'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ],
                                'name' => [
                                    'label' => 'Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ]
                            ]
                        ],
                        'delete' => [
                            'title' => 'Delete Questionnaire',
                            'subtitle' => 'Create or add new criteria.',
                            'data' => [
                                'school_year_id' => [
                                    'label' => 'School Year',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $school_year,
                                        'no_data' => 'Create school year first'
                                    ],
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12'
                                ],
                                'name' => [
                                    'label' => 'Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12'
                                ]
                            ]
                        ],
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SchoolYearModel;

class SchoolYearController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = SchoolYearModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.departments');
            }
        }

        $data = [
            'breadcrumbs' => 'Dashboard,programs,school year',
            'livewire' => [
                'component' => 'admin.school-year',   
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'action' => $action,
                        'index' => [
                            'title' => 'All School Years',
                            'subtitle' => 'List of all school years created.'
                        ],
                        'create' => [
                            'title' => 'Create School Year',
                            'subtitle' => 'Create or add new school year.',
                            'data' => [
                                'name' => [
                                    'label' => 'Description',
                                    'type' => 'text',
                                    'placeholder' => 'Type something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'start_year' => [
                                    'label' => 'Start Year',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            '2024' => 2024,
                                            '2025' => 2025,
                                            '2026' => 2026,
                                            '2027' => 2027,
                                            '2028' => 2028,
                                            '2029' => 2029,
                                            '2030' => 2030,
                                        ],
                                        'no_data' => 'No school year set'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-6',
                                ],
                                'semester' => [
                                    'label' => 'Semester',
                                    'type' => 'select',
                                    'placeholder' => 'Type something...',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            '1' => '1st Semester',
                                            '2' => '2nd Semester'
                                        ],
                                        'no_data' => 'No semester being set'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-6',
                                ],
                                'status' => [
                                    'label' => 'Status',
                                    'type' => 'select',
                                    'placeholder' => 'Type something...',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            '0' => 'No Yet Opened',
                                            '1' => 'On Going',
                                            '2' => 'Closed',
                                            '3' => 'Finished'
                                        ],
                                        'no_data' => 'No status being set'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                            ]
                        ],
                    'update' => [
                        'title' => 'Update School Year',
                        'subtitle' => 'Apply changes to selected school year.',
                        'data' => [
                            'name' => [
                                'label' => 'Description',
                                'type' => 'text',
                                'placeholder' => 'Type something...',
                                'required' => true,
                                'disabled' => false,
                                'css' => 'col-span-12',
                            ],
                            'start_year' => [
                                'label' => 'Start Year',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => false,
                                    'group' => '',
                                    'data' => [
                                        '2024' => 2024,
                                        '2025' => 2025,
                                        '2026' => 2026,
                                        '2027' => 2027,
                                        '2028' => 2028,
                                        '2029' => 2029,
                                        '2030' => 2030,
                                    ],
                                    'no_data' => 'No school year set'
                                ],
                                'required' => true,
                                'disabled' => false,
                                'css' => 'col-span-6',
                            ],
                            'semester' => [
                                'label' => 'Semester',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => false,
                                    'group' => '',
                                    'data' => [
                                        '1' => '1st Semester',
                                        '2' => '2nd Semester'
                                    ],
                                    'no_data' => 'No semester being set'
                                ],
                                'required' => true,
                                'disabled' => false,
                                'css' => 'col-span-6',
                            ],
                            'status' => [
                                'label' => 'Status',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => false,
                                    'group' => '',
                                    'data' => [
                                        '0' => 'No Yet Opened',
                                        '1' => 'On Going',
                                        '2' => 'Closed',
                                        '3' => 'Finished'
                                    ],
                                    'no_data' => 'No status being set'
                                ],
                                'required' => true,
                                'disabled' => false,
                                'css' => 'col-span-12',
                            ],
                        ]
                    ],
                    'delete' => [
                        'title' => 'Delete School Year',
                        'subtitle' => 'Permanently delete selected school year.',
                        'data' => [
                            'name' => [
                                'label' => 'Description',
                                'type' => 'text',
                                'placeholder' => 'Type something...',
                                'required' => true,
                                'disabled' => true,
                                'css' => 'col-span-12'
                            ],
                            'start_year' => [
                                'label' => 'Start Year',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => false,
                                    'group' => '',
                                    'data' => [
                                        '2024' => 2024,
                                        '2025' => 2025,
                                        '2026' => 2026,
                                        '2027' => 2027,
                                        '2028' => 2028,
                                        '2029' => 2029,
                                        '2030' => 2030,
                                    ],
                                    'no_data' => 'No school year set'
                                ],
                                'required' => true,
                                'disabled' => true,
                                'css' => 'col-span-6',
                            ],
                            'semester' => [
                                'label' => 'Semester',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => false,
                                    'group' => '',
                                    'data' => [
                                        '1' => '1st Semester',
                                        '2' => '2nd Semester'
                                    ],
                                    'no_data' => 'No semester being set'
                                ],
                                'required' => true,
                                'disabled' => true,
                                'css' => 'col-span-6',
                            ],
                            'status' => [
                                'label' => 'Status',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => false,
                                    'group' => '',
                                    'data' => [
                                        '0' => 'No Yet Opened',
                                        '1' => 'On Going',
                                        '2' => 'Closed',
                                        '3' => 'Finished'
                                    ],
                                    'no_data' => 'No status being set'
                                ],
                                'required' => true,
                                'disabled' => true,
                                'css' => 'col-span-12',
                            ],
                        ]
                    ]
                        
                    ],
                ]

            ]
        ];

        return view('template', compact('data'));
    }
}

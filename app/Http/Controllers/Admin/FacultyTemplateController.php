<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CourseModel;
use App\Models\DepartmentModel;
use App\Models\FacultyModel;

class FacultyTemplateController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete', 'template', 'connect'])) {

            $id = $request->input('id');

            $data = FacultyModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('linking.faculty-template');
            }

        }

        $dirty = DepartmentModel::with('branches')->get();

        $departments = [];
        
        foreach($dirty as $item) {
            $departments[] = (object)[
                'id' => $item->id,
                'name' => $item->name . ' - (' . $item['branches']->name . ')',
            ];
        }

        $data = [
            'breadcrumbs' => 'Dashboard,linking,faculty templates',
            'livewire' => [
                'component' => 'admin.faculty-template',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'title' => [
                            'index' => 'All Faculty Templates',
                            'create' => 'Create Faculty Template',
                            'update' => 'Update Faculty Template',
                            'delete' => 'Delete Faculty Template',
                            'template' => 'Connect to Template'
                        ],
                        'subtitle' => [
                            'index' => 'List of all faculty templates created.',
                            'create' => 'Create or add new faculty template.',
                            'update' => 'Apply changed to selected faculty template.',
                            'delete' => 'Permanently delete selected faculty template.',
                            'template' => 'Connect to faculty to different curriculum template.'
                        ],
                        'action' => $action,
                        'data' => [
                            'employee_number' => [
                                'label' => 'Employee Number',
                                'type' => 'text',
                                'placeholder' => 'ex. 20-00780',
                                'is_required' => true,
                                'col-span' => '6',
                            ],
                            'department_id' => [
                                'label' => 'Department',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => true,
                                    'data' => $departments,
                                    'no_data' => 'Create department first'
                                ],
                                'is_required' => true,
                                'col-span' => '6',
                            ],
                            'firstname' => [
                                'label' => 'First Name',
                                'type' => 'text',
                                'placeholder' => 'ex. John Paul',
                                'is_required' => true,
                                'col-span' => '4',
                            ],
                            'middlename' => [
                                'label' => 'Middle Name',
                                'type' => 'text',
                                'placeholder' => 'ex. Mariano',
                                'is_required' => false,
                                'col-span' => '4',
                            ],
                            'lastname' => [
                                'label' => 'Last Name',
                                'type' => 'text',
                                'placeholder' => 'ex. Llemos',
                                'is_required' => true,
                                'col-span' => '4',
                            ],
                            'email' => [
                                'label' => 'Email',
                                'type' => 'email',
                                'placeholder' => 'Type ...',
                                'is_required' => true,
                                'col-span' => '6',
                            ],
                            'gender' => [
                                'label' => 'Gender',
                                'type' => 'select',
                                'options' => [
                                    'is_from_db' => false,
                                    'data' => [
                                        '1' => 'Male',
                                        '2' => 'Female',
                                        '3' => 'Prefer not to say'
                                    ],
                                    'no_data' => 'No data'
                                ],
                                'is_required' => true,
                                'col-span' => '6',
                            ],  
                            'image' => [
                                'label' => 'Profile Image',
                                'type' => 'file',
                                'is_required' => true,
                                'col-span' => '12',
                            ],
                        ]
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}

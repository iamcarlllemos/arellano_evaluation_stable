<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CourseModel;
use App\Models\DepartmentModel;

class CourseController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $role = auth()->user()->role;
        $assigned_branch = auth()->user()->assigned_branch;

        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = CourseModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.departments');
            }

        }

        $departments_dirty = DepartmentModel::with('branches')
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->where('branch_id', $assigned_branch);
            })
            ->get();

        $departments = [];
        
        if($role === 'admin') {
            foreach($departments_dirty as $department) {
                $departments[] = [
                    'id' => $department->id,
                    'name' => $department->name
                ];
            }
        } else {
            foreach($departments_dirty as $department) {
                $key = $department->branches->id;
                
                if(!isset($departments[$key])) {
                    $departments[$key] = (object) [
                        'id' => $key,
                        'name' => $department->branches->name,
                        'departments' => []
                    ];
                }

                $departments[$key]->departments[] = (object) [
                    'id' => $department->id,
                    'name' => $department->name
                ];
            }
        }

        $data = [
            'breadcrumbs' => 'Dashboard,programs,courses',
            'livewire' => [
                'component' => 'admin.course',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'action' => $action,
                        'index' => [
                            'title' => 'All Courses',
                            'subtitle' => 'Lists of all create courses.'
                        ],
                        'create' => [
                            'title' => 'Create Course',
                            'subtitle' => 'Create or add new courses.',
                            'data' => [
                                'department_id' => [
                                    'label' => 'Department Name',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => 'departments',
                                        'data' => $departments,
                                        'no_data' => 'Creat department first.'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'code' => [
                                    'label' => 'Course Code',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'name' => [
                                    'label' => 'Course Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                            ]
                        ],
                        'update' => [
                            'title' => 'Update Course',
                            'subtitle' => 'Apply changes to selected course.',
                            'data' => [
                                'department_id' => [
                                    'label' => 'Department Name',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => 'departments',
                                        'data' => $departments,
                                        'no_data' => 'Creat department first.'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'code' => [
                                    'label' => 'Course Code',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'name' => [
                                    'label' => 'Course Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                            ]
                        ],
                        'delete' => [
                            'title' => 'Delete Course',
                            'subtitle' => 'Permanently delete selected course.',
                            'data' => [
                                'department_id' => [
                                    'label' => 'Department Name',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => 'departments',
                                        'data' => $departments,
                                        'no_data' => 'Creat department first.'
                                    ],
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12',
                                ],
                                'code' => [
                                    'label' => 'Course Code',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12',
                                ],
                                'name' => [
                                    'label' => 'Course Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12',
                                ],
                            ]
                        ],
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}

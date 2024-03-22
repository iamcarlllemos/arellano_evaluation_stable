<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BranchModel;
use App\Models\DepartmentModel;

class DepartmentController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';

        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = DepartmentModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('programs.departments');
            }
        }


        $branches = BranchModel::with('departments')->get();

        $data = [
            'breadcrumbs' => 'Dashboard,programs,departments',
            'branches' => $branches,
            'livewire' => [
                'component' => 'admin.department',
                'data' => [
                    'lazy' => true,
                    'form' => [
                        'action' => $action,
                        'index' => [
                            'title' => 'All Departments',
                            'subtitle' => 'Lists of all departments created.'
                        ],
                        'create' => [
                            'title' => 'Create Department',
                            'subtitle' => 'Create or add new departments.',
                            'data' => [
                                'branch_id' => [
                                    'label' => 'Branch Name',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $branches,
                                        'no_data' => 'create branch first.'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'name' => [
                                    'label' => 'Department Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                            ],
                        ],
                        'update' => [
                            'title' => 'Update Department',
                            'subtitle' => 'Apply changes to selected department.',
                            'data' => [
                                'branch_id' => [
                                    'label' => 'Branch Name',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $branches,
                                        'no_data' => 'create branch first.'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'name' => [
                                    'label' => 'Department Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                            ],
                        ],
                        'delete' => [
                            'title' => 'Delete Department',
                            'subtitle' => 'Permanently delete selected department.',
                            'data' => [
                                'branch_id' => [
                                    'label' => 'Branch Name',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $branches,
                                        'no_data' => 'create branch first.'
                                    ],
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12',
                                ],
                                'name' => [
                                    'label' => 'Department Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12',
                                ],
                            ],
                        ],
                    ],
                ]
            ]

        ];

        return view('template', compact('data'));
    }
}

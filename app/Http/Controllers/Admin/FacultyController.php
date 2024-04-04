<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\Account;

use App\Models\DepartmentModel;
use App\Models\FacultyModel;

class FacultyController extends Controller
{

    use Account;

    public function index(Request $request) {

        $id = $request->input('id');
        $action = $request->input('action');

        $role = $this->admin()->role;
        $assigned_branch = $this->admin()->assigned_branch;

        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {
            $data = FacultyModel::where('id', $id);
            if(!$data->exists()) {
                return redirect()->route('admin.accounts.student');
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
            'breadcrumbs' => 'Dashboard,accounts,faculties',
            'livewire' => [
                'component' => 'admin.faculty',
                'data' => [
                    'lazy' => true,
                    'form' => [
                        'id' => $id,
                        'action' => $action,
                        'index' => [
                            'title' => 'All Faculties',
                            'subtitle' => 'List of all faculties created.'
                        ],
                        'create' => [
                            'title' => 'Create Faculty',
                            'subtitle' => 'Create or add new faculty.',
                            'data' => [
                                'employee_number' => [
                                    'label' => 'Employee Number',
                                    'type' => 'text',
                                    'placeholder' => 'ex. 20-00780',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'department_id' => [
                                    'label' => 'Department',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => 'departments',
                                        'data' => $departments,
                                        'no_data' => 'Create department first'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'firstname' => [
                                    'label' => 'First Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. John Paul',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'middlename' => [
                                    'label' => 'Middle Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Mariano',
                                    'required' => false,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'lastname' => [
                                    'label' => 'Last Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Llemos',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'ex. faculty@email.com',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'gender' => [
                                    'label' => 'Gender',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            '1' => 'Male',
                                            '2' => 'Female',
                                            '3' => 'Prefer not to say'
                                        ],
                                        'no_data' => 'No data'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'image' => [
                                    'label' => 'Profile Image',
                                    'type' => 'file',
                                    'required' => false,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'ex. faculty01',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'password' => [
                                    'label' => 'Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'password_repeat' => [
                                    'label' => 'Repeat Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'is_email' => [
                                    'label' => 'Notify this user via provided email',
                                    'type' => 'checkbox',
                                    'required' => false,
                                    'disabled' => false,
                                    'css' => 'col-span-12'
                                ]
                            ]
                        ],
                        'update' => [
                            'title' => 'Update Faculty',
                            'subtitle' => 'Apply changes to selected faculty',
                            'data' => [
                                'employee_number' => [
                                    'label' => 'Employee Number',
                                    'type' => 'text',
                                    'placeholder' => 'ex. 20-00780',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'department_id' => [
                                    'label' => 'Department',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => 'departments',
                                        'data' => $departments,
                                        'no_data' => 'Create department first'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'firstname' => [
                                    'label' => 'First Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. John Paul',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'middlename' => [
                                    'label' => 'Middle Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Mariano',
                                    'required' => false,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'lastname' => [
                                    'label' => 'Last Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Llemos',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'ex. faculty@email.com',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'gender' => [
                                    'label' => 'Gender',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            '1' => 'Male',
                                            '2' => 'Female',
                                            '3' => 'Prefer not to say'
                                        ],
                                        'no_data' => 'No data'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'image' => [
                                    'label' => 'Profile Image',
                                    'type' => 'file',
                                    'required' => false,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'ex. faculty01',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12',
                                ],
                                'password' => [
                                    'label' => 'Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'password_repeat' => [
                                    'label' => 'Repeat Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                            ]
                        ],
                        'delete' => [
                            'title' => 'Delete Faculty',
                            'subtitle' => 'Permanently delete selected faculty',
                            'data' => [
                                'employee_number' => [
                                    'label' => 'Employee Number',
                                    'type' => 'text',
                                    'placeholder' => 'ex. 20-00780',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'department_id' => [
                                    'label' => 'Department',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => 'departments',
                                        'data' => $departments,
                                        'no_data' => 'Create department first'
                                    ],
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'firstname' => [
                                    'label' => 'First Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. John Paul',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'middlename' => [
                                    'label' => 'Middle Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Mariano',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'lastname' => [
                                    'label' => 'Last Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Llemos',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'ex. faculty@email.com',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'gender' => [
                                    'label' => 'Gender',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            '1' => 'Male',
                                            '2' => 'Female',
                                            '3' => 'Prefer not to say'
                                        ],
                                        'no_data' => 'No data'
                                    ],
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
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

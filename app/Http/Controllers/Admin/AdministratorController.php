<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BranchModel;
use App\Models\User;

class AdministratorController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';

        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = User::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('accounts.student');
            }

        }

        $branches = BranchModel::all();

        $data = [
            'breadcrumbs' => 'Dashboard,accounts,administrators',
            'livewire' => [
                'component' => 'admin.administrator',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'action' => $action,
                        'index' => [
                            'title' => 'All Administrators',
                            'subtitle' => 'List of all administrators created.'
                        ],
                        'create' => [
                            'title' => 'Create Administrator',
                            'subtitle' => 'Create or add new administrator based on their roles.',
                            'data' => [
                                'firstname' => [
                                    'label' => 'First Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. John Paul',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'lastname' => [
                                    'label' => 'Last Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Llemos',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'role' => [
                                    'label' => 'Role',
                                    'type' => 'select',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            'superadmin' => 'Super Administrator',
                                            'admin' => 'Administrator',
                                        ],
                                        'no_data' => 'No data found'
                                    ]
                                ],
                                'branch' => [
                                    'label' => 'Branch',
                                    'type' => 'select',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $branches,
                                        'no_data' => 'No data found'
                                    ],
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                                'password' => [
                                    'label' => 'Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                                'password_repeat' => [
                                    'label' => 'Password Repeat',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                            ]
                        ],
                        'update' => [
                            'title' => 'Update Administrator',
                            'subtitle' => 'Apply changes to selected administrator.',
                            'data' => [
                                'firstname' => [
                                    'label' => 'First Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. John Paul',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'lastname' => [
                                    'label' => 'Last Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Llemos',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'role' => [
                                    'label' => 'Role',
                                    'type' => 'select',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            'superadmin' => 'Super Administrator',
                                            'admin' => 'Administrator',
                                        ],
                                        'no_data' => 'No data found'
                                    ]
                                ],
                                'branch' => [
                                    'label' => 'Branch',
                                    'type' => 'select',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $branches,
                                        'no_data' => 'No data found'
                                    ],
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                                'password' => [
                                    'label' => 'Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                                'password_repeat' => [
                                    'label' => 'Password Repeat',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                            ]
                        ],
                        'delete' => [
                            'title' => 'Delete Administrator',
                            'subtitle' => 'Permanently delete to selected administrator.',
                            'data' => [
                                'firstname' => [
                                    'label' => 'First Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. John Paul',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'lastname' => [
                                    'label' => 'Last Name',
                                    'type' => 'text',
                                    'placeholder' => 'ex. Llemos',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'role' => [
                                    'label' => 'Role',
                                    'type' => 'select',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            'superadmin' => 'Super Administrator',
                                            'admin' => 'Administrator',
                                        ],
                                        'no_data' => 'No data found'
                                    ]
                                ],
                                'branch' => [
                                    'label' => 'Branch',
                                    'type' => 'select',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $branches,
                                        'no_data' => 'No data found'
                                    ],
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-12',
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

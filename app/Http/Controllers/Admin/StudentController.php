<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CourseModel;
use App\Models\StudentModel;

class StudentController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action') ?? '';
        
        $get_data = [];

        if(in_array($action, ['update', 'delete'])) {

            $id = $request->input('id');

            $data = StudentModel::where('id', $id);

            if(!$data->exists()) {
                return redirect()->route('accounts.student');
            }

        }

        $dirty = CourseModel::with('departments.branches')->get();

        $courses = [];
        
        foreach($dirty as $item) {
            $courses[] = (object)[
                'id' => $item->id,
                'name' => $item->name . ' - (' . $item['departments']['branches']->name . ')',
            ];
        }

        $data = [
            'breadcrumbs' => 'Dashboard,accounts,students',
            'livewire' => [
                'component' => 'admin.student',
                'data' => [
                    'lazy' => false,
                    'form' => [
                        'action' => $action,
                        'index' => [
                            'title' => 'All Students',
                            'subtitle' => 'List of all students created.'
                        ],
                        'create' => [
                            'title' => 'Create Student',
                            'subtitle' => 'Create or add new students.',
                            'data' => [
                                'student_number' => [
                                    'label' => 'Student Number',
                                    'type' => 'text',
                                    'placeholder' => 'ex. 20-00780',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'course_id' => [
                                    'label' => 'Course',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $courses,
                                        'no_data' => 'Create course first'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'year_level' => [
                                    'label' => 'Year Level',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            '1' => '(1st) First Year',
                                            '2' => '(2nd) Second Year',
                                            '3' => '(3rd) Third Year',
                                            '4' => '(4th) Fourth Year'
                                        ],
                                        'no_data' => 'No data found'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
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
                                'birthday' => [
                                    'label' => 'Birthday',
                                    'type' => 'date',
                                    'placeholder' => 'Type ...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'image' => [
                                    'label' => 'Profile Image',
                                    'type' => 'file',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-32',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-3',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-3',
                                ],
                                'password' => [
                                    'label' => 'Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-32',
                                ],
                                'password_repeat' => [
                                    'label' => 'Password Repeat',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-32',
                                ],
                            ]
                        ],
                        'update' => [
                            'title' => 'Update Student',
                            'subtitle' => 'Apply changes to selected student.',
                            'data' => [
                                'student_number' => [
                                    'label' => 'Student Number',
                                    'type' => 'text',
                                    'placeholder' => 'ex. 20-00780',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'course_id' => [
                                    'label' => 'Course',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $courses,
                                        'no_data' => 'Create course first'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'year_level' => [
                                    'label' => 'Year Level',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            '1' => '(1st) First Year',
                                            '2' => '(2nd) Second Year',
                                            '3' => '(3rd) Third Year',
                                            '4' => '(4th) Fourth Year'
                                        ],
                                        'no_data' => 'No data found'
                                    ],
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
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
                                'birthday' => [
                                    'label' => 'Birthday',
                                    'type' => 'date',
                                    'placeholder' => 'Type ...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'image' => [
                                    'label' => 'Profile Image',
                                    'type' => 'file',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-32',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-3',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-3',
                                ],
                                'password' => [
                                    'label' => 'Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-32',
                                ],
                                'password_repeat' => [
                                    'label' => 'Password Repeat',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-32',
                                ],
                            ]
                        ],
                        'delete' => [
                            'title' => 'Delete Student',
                            'subtitle' => 'Permanently delete selected student.',
                            'data' => [
                                'student_number' => [
                                    'label' => 'Student Number',
                                    'type' => 'text',
                                    'placeholder' => 'ex. 20-00780',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'course_id' => [
                                    'label' => 'Course',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => true,
                                        'group' => '',
                                        'data' => $courses,
                                        'no_data' => 'Create course first'
                                    ],
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'year_level' => [
                                    'label' => 'Year Level',
                                    'type' => 'select',
                                    'options' => [
                                        'is_from_db' => false,
                                        'group' => '',
                                        'data' => [
                                            '1' => '(1st) First Year',
                                            '2' => '(2nd) Second Year',
                                            '3' => '(3rd) Third Year',
                                            '4' => '(4th) Fourth Year'
                                        ],
                                        'no_data' => 'No data found'
                                    ],
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-4',
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
                                'birthday' => [
                                    'label' => 'Birthday',
                                    'type' => 'date',
                                    'placeholder' => 'Type ...',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'image' => [
                                    'label' => 'Profile Image',
                                    'type' => 'file',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-32',
                                ],
                                'email' => [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'placeholder' => 'Type ...',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-3',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'Type...',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-3',
                                ],
                                'password' => [
                                    'label' => 'Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-32',
                                ],
                                'password_repeat' => [
                                    'label' => 'Password Repeat',
                                    'type' => 'password',
                                    'placeholder' => '••••••••',
                                    'required' => true,
                                    'disabled' => true,
                                    'css' => 'col-span-12 md:col-span-32',
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

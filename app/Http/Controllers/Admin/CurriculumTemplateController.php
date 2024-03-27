<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BranchModel;
use App\Models\CurriculumTemplateModel;
use App\Models\DepartmentModel;
use App\Livewire\CurriculumTemplate;

class CurriculumTemplateController extends Controller
{
    public function index(Request $request) {

        $id = $request->input('id');
        $action = $request->input('action');

        if(in_array($action, ['update', 'delete'])) {
            $data = CurriculumTemplateModel::where('id', $id);
            if(!$data->exists()) {
                return redirect()->route('linking.curriculum-template');
            }
        }

        $data = [
            'breadcrumbs' => 'Dashboard,linking,curriculum template',
            'livewire' => [
                'component' => 'admin.curriculum-template',
                'data' => [
                    'lazy' => true,
                    'form' => [
                        'title' => [
                            'index' => 'All Curriculum Templates',
                            'create' => 'Create Curriculum Template',
                            'update' => 'Update Curriculum Template',
                            'delete' => 'Delete Curriculum Template'
                        ],
                        'subtitle' => [
                            'index' => 'Lists of all curriculum templates created.',
                            'create' => 'Create or add new curriculum template.',
                            'update' => 'Apply changed to selected curriculum template.',
                            'delete' => 'Permanently delete selected curriculum template.',
                        ],
                        'id' => $id,
                        'action' => $action,
                        'data' => [
                            'year_level' => [
                                'label' => 'Year Level',
                                'type' => 'select',
                                'on_change' => 'loadYear',
                                'options' => [
                                    'is_from_db' => false,
                                    'data' => [
                                        '1' => '(1st) First Year',
                                        '2' => '(2nd) Second Year',
                                        '3' => '(3rd) Third Year',
                                        '4' => '(4th) Fourth Year'
                                    ],
                                    'no_data' => 'No data found'
                                ],
                                'is_required' => true,
                                'col-span' => '6',
                            ],
                            'subject_sem' => [
                                'label' => 'Semester',
                                'type' => 'select',
                                'on_change' => '',
                                'options' => [
                                    'is_from_db' => false,
                                    'data' => [
                                        '1' => '(1st) First Semester',
                                        '2' => '(2nd) Second Semester',
                                    ],
                                    'no_data' => 'No data found'
                                ],
                                'is_required' => true,
                                'col-span' => '6',
                            ]
                        ],
                    ],
                ]
            ]

        ];

        return view('template', compact('data'));
    }
}

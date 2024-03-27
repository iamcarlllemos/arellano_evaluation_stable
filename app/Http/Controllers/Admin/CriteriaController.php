<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CriteriaModel;

class CriteriaController extends Controller
{
    public function index(Request $request) {

        $action = $request->input('action');
        $id = $request->input('id');

        if(in_array($action, ['update', 'delete'])) {
            $data = CriteriaModel::where('id', $id);
            if(!$data->exists()) {
                return redirect()->route('admin.programs.criteria');
            }
        }

        $data = [
            'breadcrumbs' => 'Dashboard,programs,criteria',
            'livewire' => [
                'component' => 'admin.criteria',
                'data' => [
                    'lazy' => true,
                    'form' => [
                        'id' => $id,
                        'action' => $action,
                        'index' => [
                            'title' => 'All Criterias',
                            'subtitle' => 'List of all criterias created.'
                        ],
                        'create' => [
                            'title' => 'Create Criteria',
                            'subtitle' => 'Create or add new criteria.',
                            'data' => [
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
                            'title' => 'Update Criteria',
                            'subtitle' => 'Apply changes to selected criteria.',
                            'data' => [
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
                            'title' => 'Delete Criteria',
                            'subtitle' => 'Permanently delete selected criteria.',
                            'data' => [
                                'name' => [
                                    'label' => 'Name',
                                    'type' => 'text',
                                    'placeholder' => 'Write something...',
                                    'required' => false,
                                    'disabled' => true,
                                    'css' => 'col-span-12'
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}

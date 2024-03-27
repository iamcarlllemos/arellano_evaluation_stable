<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CriteriaModel;
use App\Models\QuestionnaireModel;

class QuestionnaireItemController extends Controller
{
    public function index($slug) {

        $slug = QuestionnaireModel::where('slug', $slug);

        if(!$slug->exists()) {
            return redirect()->route('admin.programs.questionnaire');
        }

        $id = $slug->first()->id;

        $criteria = CriteriaModel::all();

        $data = [
            'breadcrumbs' => 'Dashboard,programs,questionnaire',
            'livewire' => [
                'component' => 'admin.questionnaire-item',
                'data' => [
                    'lazy' => true,
                    'form' => [
                        'id' => $id,
                        'data' => [
                            'questionnaire_id' => [
                                'label' => 'Questionnaire ',
                                'type' => 'hidden',
                                'placeholder' => '',
                                'value' => $id
                            ],
                            'questionnaire_item_id' => [
                                'label' => '',
                                'type' => 'hidden',
                                'placeholder' => '',
                                'value' => $id
                            ],
                            'criteria_id' => [
                                'label' => 'Criteria',
                                'type' => 'select',
                                'placeholder' => 'Type...',
                                'options' => [
                                    'is_from_db' => true,
                                    'data' => $criteria,
                                    'no_data' => 'Create criteria first'
                                ]
                            ],
                            'item' => [
                                'label' => 'Questionnaire Item',
                                'type' => 'textarea',
                                'placeholder' => 'Write Something...',
                            ]
                        ]
                    ],
                ]
            ]
        ];


        return view('template', compact('data'));
    }
}

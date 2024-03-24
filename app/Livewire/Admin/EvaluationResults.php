<?php

namespace App\Livewire\Admin;

use App\Models\DepartmentModel;
use App\Models\FacultyModel;
use App\Models\QuestionnaireModel;
use App\Models\ResponseModel;
use Livewire\Component;

class EvaluationResults extends Component
{
    public $form;

    public $view;

    public function mount() {

        $action = $this->form['action'];
        $id = $this->form['id'];
        $faculty = $this->form['faculty'];
        $template = $this->form['template'];
        $subject = $this->form['subject'];

        if($action == 'view') {


            $questionnaire = QuestionnaireModel::with('questionnaire_item.criteria')
            ->where('school_year_id', $id)->get()[0];

            $responses = ResponseModel::with('items.questionnaire.criteria')
                ->where('evaluation_id', $id)
                ->where('template_id', $template)
                ->where('faculty_id', $faculty)->get();

            $sorted_responses = [];

            $comments = [];

            // sorted responses
            foreach ($responses as $response) {
                foreach ($response['items'] as $item) {
                    $sorted_responses[] = [
                        'questionnaire_id' => $item['questionnaire_id'],
                        'response_id' => $item['response_id'],
                        'response_rating' => $item['response_rating'],
                    ];
                }

                $comments[] = $response['comment'];
            }

            $evaluation_result = [];

            // bind tally of responses to designated questionnaires
            foreach ($questionnaire['questionnaire_item'] as $item) {
                $key = $item['criteria_id'];
                if (!isset($evaluation_result[$key])) {
                    $evaluation_result[$key] = [
                        'id' => $item['id'],
                        'criteria_name' => $item['criteria']['name'],
                        'total_responses' => 0,
                        'comments' => $comments,
                        'items' => []
                    ];
                }

                foreach ($sorted_responses as $response) {
                    if ($response['questionnaire_id'] == $item['id']) {
                        $evaluation_result[$key]['total_responses'] = count($responses);
                        if (!isset($evaluation_result[$key]['items'][$response['questionnaire_id']])) {
                            $evaluation_result[$key]['items'][$response['questionnaire_id']] = [
                                'id' => $item['id'],
                                'response_id' => $response['response_id'],
                                'name' => $item['item'],
                                'weighted_mean' => '',
                                'mean_squared' => '',
                                'standard_deviation' => '',
                                'interpretation' => '',
                                'comments' => $comments,
                                'tally' => [
                                    '1' => 0,
                                    '2' => 0,
                                    '3' => 0,
                                    '4' => 0
                                ]
                            ];
                        }

                        $evaluation_result[$key]['items'][$response['questionnaire_id']]['tally'][$response['response_rating']]++;
                    }
                }
            }

            // reset indexed values
            $evaluation_result = array_values($evaluation_result);
            foreach ($evaluation_result as &$criteria) {
                $criteria['items'] = array_values($criteria['items']);
            }

            // compute weighted mean
           foreach ($evaluation_result as &$criteria) {
                foreach ($criteria['items'] as &$item) {
                    $tally = [];
                    foreach ($item['tally'] as $key => $value) {
                        $tally[$key] = $key * $value;
                    }
                    $total = array_sum($tally) / (int) $criteria['total_responses'];
                    $item['weighted_mean'] = $total;
                }
            }

            // compute mean squared
            foreach ($evaluation_result as &$criteria) {
                foreach ($criteria['items'] as &$item) {
                    $tally = [];
                    foreach ($item['tally'] as $key => &$value) {
                        $squared = ($key * $key);
                        $tally[$key] = $squared * $value;
                    }
                    $total = array_sum($tally) / (int) $criteria['total_responses'];
                    $item['mean_squared'] = $total;
                }
            }

            // compute standard deviation
            foreach ($evaluation_result as &$criteria) {
                foreach ($criteria['items'] as &$item) {
                    $sd = sqrt((int)$item['mean_squared'] - (int) $item['weighted_mean']);
                    $item['standard_deviation'] = $sd;
                }
            }

            // put the interpretation
            foreach ($evaluation_result as &$criteria) {
                foreach ($criteria['items'] as &$item) {
                    $interpretation = $this->interpretation($item['weighted_mean']);
                    $item['interpretation'] = $interpretation;
                }
            }

            $evaluation_result = array_values($evaluation_result);

            $view = [
                'faculty' => FacultyModel::with([
                        'templates' => function($query) use ($template, $subject) {
                            $query->where('template_id', $template)
                                ->whereHas('curriculum_template.subjects', function($query) use ($subject) {
                                    $query->where('subject_id', $subject);
                                });
                        },
                        'templates.curriculum_template.subjects.courses.departments.branches'
                    ])
                    ->where('id', $faculty)
                    ->whereHas('templates', function($query) use ($template, $subject) {
                        $query->where('template_id', $template)
                            ->whereHas('curriculum_template.subjects', function($query) use ($subject) {
                                $query->where('subject_id', $subject);
                            });
                    })
                    ->get()[0],
                'evaluation_result' => $evaluation_result,
            ];

            $this->view = $view;
        }
    }

    public function interpretation($value) {
        if($value >= 0 && $value <= 1.49) {
            return '
            <div class="flex justify-center items-center px-4 py-3 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <div class="text-sm font-bold uppercase text-center">
                    Disagree
                </div>
                </button>
            </div>
            ';
        } else if($value >= 1.50 && $value <= 2.49) {
            return '
            <div class="flex justify-center items-center px-4 py-3 text-orange-800 rounded-lg bg-orange-50 dark:bg-gray-800 dark:text-orange-400" role="alert">
                <div class="text-sm font-bold uppercase text-center">
                    Neutral
                </div>
                </button>
            </div>
            ';
        } else if ($value >= 2.50 && $value <= 3.49){
            return '
            <div class="flex justify-center items-center px-4 py-3 text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-400" role="alert">
                <div class="text-sm font-bold uppercase text-center">
                    Agree
                </div>
                </button>
            </div>
            ';
        } else if($value >= 3.50) {
            return '
            <div class="flex justify-center items-center px-4 py-3 text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <div class="text-sm font-bold uppercase text-center">
                    Strongly Agree
                </div>
                </button>
            </div>
            ';
        }
    }

    public function render()
    {

        $dirty_departments = DepartmentModel::with('branches')->get();

        $departments = [];

        foreach($dirty_departments as $department) {
            $key = $department->branches->id;
            if(!isset($departments[$key])) {
                $departments[$key] = [
                    'id' => $key,
                    'name' => $department->branches->name,
                    'departments' => []
                ];

                $departments[$key]['departments'][] = [
                    'id' => $department->id,
                    'name' => $department->name
                ];
            }
        }

        $faculty = FacultyModel::with('templates.curriculum_template.subjects.courses.departments.branches')->get();

        $data = [
            'departments' => $departments,
            'faculty' => $faculty
        ];

        return view('livewire.admin.evaluation-result', compact('data'));
    }
}

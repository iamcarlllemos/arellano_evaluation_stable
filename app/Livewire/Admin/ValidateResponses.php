<?php

namespace App\Livewire\Admin;

use App\Models\FacultyModel;
use App\Models\QuestionnaireModel;
use App\Models\ResponseModel;
use chillerlan\QRCode\QRCode;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\Censored;


class ValidateResponses extends Component
{

    use WithFileUploads;
    use Censored;

    public $form;
    public $type;
    public $code;

    public $result_view;

    public $display = [
        'wm' => false,
        'sqm' => false,
        'std' => false,
        'itrprtn' => false,
        'comments' => false
    ];

    public $attr = [
        'code' => 'Code'
    ];

    public function onchangeType() {
        $this->reset('result_view');
    }

    public function submit() {

        if($this->type == 1) {
            $this->get_responses($this->code);
        } else if($this->type == 2) {
            $rule = [
                'code' => 'required|image|mimes:jpeg,png,jpg|max:5000',
            ];

            $this->validate($rule, [], $this->attr);

            $reference = $this->decode_qr($this->code->path());
            if($reference) {
                $this->get_responses($reference);
            }

            $this->reset('code');
        }

        if(!session()->has('error')) {
            $this->reset('code', 'type');
        } else {
            $this->reset('result_view');
        }
    }

    public function decode_qr($image) {
        try {
            $qr = new QRCode();
            $string = $qr->readFromFile($image);
            $split = explode('_', $string);
            $end = end($split);
            return $end;
        } catch (\Throwable $e) {
            session()->flash('error', ucfirst($e->getMessage()));
        }
    }

    public function get_responses($reference) {

        if(!session()->has('settings')) {
            $this->result_settings();
        }

        $this->display = session('settings')['evaluation_result_display'];

        $evaluation_result = [
            'total_responses' => 0,
            'total_items' => 0,
            'comments' => '',
            'respondents' => [
                'total_respondents' => 0,
                'respondents' => 0,
                'not_responded' => 0
            ],
            'total_interpretation' => [
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0
            ],
            'averages' => [
                'mean' => 0,
                'squared_mean' => 0,
                'standard_deviation' => 0,
                'descriptive_interpretation' => 0,
            ],
            'stats' => [],
        ];

        $responseModel = new ResponseModel;

        $responses_data = $responseModel->where('id', $reference);

        if($responses_data->exists()) {

            $responses_data = $responseModel->where('id', $reference)->get()[0];
            $evaluation_id = $responses_data->evaluation_id;
            $template_id = $responses_data->template_id;
            $faculty_id = $responses_data->faculty_id;

            // questionnaires
            $questionnaire = QuestionnaireModel::with('questionnaire_item.criteria')
                ->where('school_year_id', $evaluation_id)->get()[0];

            $responses = ResponseModel::with('students.courses.departments.branches', 'items.questionnaire.criteria')
                ->where('evaluation_id', $evaluation_id)
                ->where('template_id', $template_id)
                ->where('faculty_id', $faculty_id)
                ->where('id', $reference)->get();

            $sorted_responses = [];

            foreach ($responses as $response) {
                foreach ($response['items'] as $item) {
                    $sorted_responses[] = [
                        'questionnaire_id' => $item['questionnaire_id'],
                        'response_id' => $item['response_id'],
                        'response_rating' => $item['response_rating'],
                    ];
                }


                $student_name = ucwords($response['students']['firstname']. ' ' . $response['students']['lastname']);
                $comments[] = [
                    'commented_by' => $this->applyCensored($student_name),
                    'comment' => $response['comment']
                ];
            }

            $evaluation_result = [
                'total_responses' => 0,
                'total_items' => 0,
                'comments' => $comments,
                'respondents' => [
                    'total_respondents' => 0,
                    'respondents' => 0,
                    'not_responded' => 0
                ],
                'total_interpretation' => [
                    '1' => 0,
                    '2' => 0,
                    '3' => 0,
                    '4' => 0
                ],
                'averages' => [
                    'mean' => 0,
                    'squared_mean' => 0,
                    'standard_deviation' => 0,
                    'descriptive_interpretation' => 0,
                ],
                'stats' => [],
            ];

            // bind tally of responses to designated questionnaires
            foreach ($questionnaire['questionnaire_item'] as $item) {
                $key = $item['criteria_id'];
                if (!isset($evaluation_result['stats'][$key])) {
                    $evaluation_result['stats'][$key] = [
                        'id' => $item['id'],
                        'criteria_name' => $item['criteria']['name'],
                        'items' => []
                    ];
                }

                $evaluation_result['total_items']++;

                foreach ($sorted_responses as $response) {
                    if ($response['questionnaire_id'] == $item['id']) {
                        $evaluation_result['total_responses'] = count($responses);
                        if (!isset($evaluation_result['stats'][$key]['items'][$response['questionnaire_id']])) {
                            $evaluation_result['stats'][$key]['items'][$response['questionnaire_id']] = [
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

                        $evaluation_result['stats'][$key]['items'][$response['questionnaire_id']]['tally'][$response['response_rating']]++;
                    }
                }

            }

            // reset indexed values
            $evaluation_result['stats'] = array_values($evaluation_result['stats']);
            foreach ($evaluation_result['stats'] as &$criteria) {
                $criteria['items'] = array_values($criteria['items']);
            }

            // compute weighted mean
            foreach ($evaluation_result['stats'] as $key => &$criteria) {
                foreach ($criteria['items'] as &$item) {
                    $tally = [];
                    foreach ($item['tally'] as $key => $value) {
                        $tally[$key] = $key * $value;
                    }
                    $total = array_sum($tally) / (int) $evaluation_result['total_responses'];
                    $item['weighted_mean'] = $total;
                }
            }

            // compute mean squared
            foreach ($evaluation_result['stats'] as &$criteria) {
                foreach ($criteria['items'] as &$item) {
                    $tally = [];
                    foreach ($item['tally'] as $key => &$value) {
                        $squared = ($key * $key);
                        $tally[$key] = $squared * $value;
                    }
                    $total = array_sum($tally) / (int) $evaluation_result['total_responses'];
                    $item['mean_squared'] = $total;
                }
            }

            // compute standard deviation
            foreach ($evaluation_result['stats'] as &$criteria) {
                foreach ($criteria['items'] as &$item) {
                    $sd = sqrt((int)$item['mean_squared'] - (int) $item['weighted_mean']);
                    $item['standard_deviation'] = $sd;
                }
            }

            // put the interpretation
            foreach ($evaluation_result['stats'] as &$criteria) {
                foreach ($criteria['items'] as &$item) {
                    $interpretation = $this->interpretation($item['weighted_mean']);
                    $item['interpretation'] = $interpretation;
                    $evaluation_result['total_interpretation'][$interpretation]++;
                }
            }

            // compute average mean

            $mean = 0;
            $squared = 0;
            $std = 0;

            foreach($evaluation_result['stats'] as $key => $results) {

                foreach($results['items'] as $items) {
                    $mean += $items['weighted_mean'];
                    $squared += $items['mean_squared'];
                    $std += $items['standard_deviation'];
                }

                if($evaluation_result['total_responses'] > 0) {
                    $evaluation_result['averages'] = [
                        'mean' => $mean / $evaluation_result['total_items'],
                        'squared_mean' => $squared / $evaluation_result['total_items'],
                        'standard_deviation' => $std / $evaluation_result['total_items'],
                        'descriptive_interpretation' => $this->interpretation($mean / $evaluation_result['total_items'])
                    ];
                } else {
                    $evaluation_result['averages'] = [
                        'mean' => 0,
                        'squared_mean' => 0,
                        'standard_deviation' => 0,
                        'descriptive_interpretation' => 0
                    ];
                }

            }

            $evaluation_result['stats'] = array_values($evaluation_result['stats']);

            $faculty = FacultyModel::with([
                'templates' => function($query) use ($template_id) {
                    $query->where('template_id', $template_id);
                },
                'templates.curriculum_template.subjects.courses.departments.branches'
            ])
            ->where('id', $faculty_id)
            ->whereHas('templates', function($query) use ($template_id) {
                $query->where('template_id', $template_id);
            })
            ->get()[0];


            $this->result_view = [
                'student' => $responses[0]->students,
                'faculty' => $faculty,
                'evaluation_result' => $evaluation_result
            ];

        } else {
            session()->flash('error', 'No response result found.');
        }
    }

    public function result_settings() {
        $to_save = [
            'settings' => [
                'evaluation_result_display' => $this->display
            ]
        ];

        session($to_save);
    }

    public function interpretation($value) {
        if($value >= 0 && $value <= 1.49) {
            return 1;
        } else if($value >= 1.50 && $value <= 2.49) {
            return 2;
        } else if ($value >= 2.50 && $value <= 3.49){
            return 3;
        } else if($value >= 3.50) {
            return 4;
        }
    }

    public function render()
    {
        return view('livewire.admin.validate-responses');
    }

}

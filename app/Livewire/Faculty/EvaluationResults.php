<?php

namespace App\Livewire\Faculty;

use App\Models\DepartmentModel;
use App\Models\FacultyModel;
use App\Models\QuestionnaireModel;
use App\Models\ResponseModel;
use App\Models\StudentModel;
use Livewire\Component;
use App\Traits\Censored;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use Illuminate\Support\Facades\Response;

class EvaluationResults extends Component
{

    use Censored;

    public $form;
    public $view;
    public $key;
    public $toKey;

    public $display = [
        'wm' => false,
        'sqm' => false,
        'std' => false,
        'itrprtn' => false,
        'comments' => false
    ];

    public function generate_random_code($length = 8) {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomWord = '';
        for ($i = 0; $i < $length; $i++) {
            $randomWord .= $characters[rand(0, strlen($characters) - 1)];
        }
        $this->key = $randomWord;
    }

    public function result_view() {

        $action = $this->form['action'];
        $id = $this->form['id'];
        $faculty = $this->form['faculty'];
        $template = $this->form['template'];
        $subject = $this->form['subject'];
        $semester = $this->form['semester'];

        if(!session()->has('settings')) {
            $this->result_settings();
        }

        $this->display = session('settings')['evaluation_result_display'];

        if($action == 'view') {

            $questionnaire = QuestionnaireModel::with('questionnaire_item.criteria')
            ->where('school_year_id', $id)->get()[0];

            $responses = ResponseModel::with('students', 'items.questionnaire.criteria')
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


                $student_name = $response['students']['firstname']. ' ' . $response['students']['lastname'];
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
                        'descriptive_interpretation' => $this->interpretation($squared / $evaluation_result['total_items'])
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
            $evaluation_result['respondents'] = $this->respondents();

            $view = [
                'faculty' => FacultyModel::with([
                    'templates' => function($query) use ($template, $subject) {
                        $query->where('template_id', $template);
                    },
                    'templates.curriculum_template.subjects.courses.departments.branches'
                ])
                ->where('id', $faculty)
                ->get()[0],
                'evaluation_result' => $evaluation_result,
            ];

            $this->view = $view;
        }
    }

    public function mount() {

        $this->result_view();
        $this->generate_random_code();

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

    public function result_settings() {
        $to_save = [
            'settings' => [
                'evaluation_result_display' => $this->display
            ]
        ];

        session($to_save);
    }

    public function respondents() {
        $total_respondents = StudentModel::with('courses.template')
            ->whereHas('courses.template', function($query) {
                $query->where('id', $this->form['template'])
                    ->where('subject_id', $this->form['subject']);
            })
            ->get()->count();

        $respondents = ResponseModel::where('evaluation_id', $this->form['id'])
            ->where('template_id', $this->form['template'])
            ->get()->count();

        $not_responded = $total_respondents - $respondents;

        $data = [
            'total_respondents' => $total_respondents,
            'respondents' => $respondents,
            'not_responded' => $not_responded
        ];

        return $data;
    }

    public function placeholder() {
        return view('livewire.admin.placeholder');
    }

    public function render() {

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

        $faculty = FacultyModel::with('departments.branches','templates.curriculum_template.subjects.courses.departments.branches')->get();

        $data = [
            'departments' => $departments,
            'faculty' => $faculty
        ];

        return view('livewire.faculty.evaluation-results', compact('data'));
    }

    public function save_pdf() {

        $data = [
            'display' => $this->display,
            'view' => $this->view,
            'has_image' => true
        ];

        $pdf = PDF::loadView('printable.result-view-single', $data);

        $faculty = $this->view['faculty'];
        $filename = strtolower('evaluation_result_of_' . $faculty['firstname'] . '_' . $faculty['lastname'] . '_' .time().'.pdf');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);

    }

    public function save_excel() {

        $data = [
            'display' => $this->display,
            'view' => $this->view,
            'has_image' => false
        ];

        $spreadsheet = new Spreadsheet();

        // Set active sheet
        $spreadsheet->setActiveSheetIndex(0);

        // HTML content to be converted to Excel
        $html = View::make('printable.result-view-single', $data)->render();

        // Load HTML content into PHPExcel
        $reader = new HTML();
        $spreadsheet = $reader->loadFromString($html);

        // Set column widths after loading HTML content
        $sheet = $spreadsheet->getActiveSheet();

        for ($i = 'A'; $i <= $sheet->getHighestColumn(); $i++) {
            if($i != 'B') {
                $sheet->getColumnDimension($i)->setAutoSize(true);
            } else {
                $sheet->getColumnDimension('B')->setWidth(5);
            }
        }

        $faculty = $this->view['faculty'];
        $filename = strtolower('evaluation_result_of_' . $faculty['firstname'] . '_' . $faculty['lastname'] . '_' .time().'.xlsx');

        // Save Excel file
        $tempFilePath = storage_path('app/'.$filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        // Return the Excel file as a downloadable response
        return Response::download($tempFilePath, $filename)->deleteFileAfterSend(true);

    }
}

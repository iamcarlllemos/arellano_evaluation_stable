<?php

namespace App\Livewire\Admin;

use App\Models\DepartmentModel;
use App\Models\FacultyModel;
use App\Models\FacultyTemplateModel;
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
use App\Traits\Account;
use Livewire\WithPagination;



class EvaluationResults extends Component
{

    use Censored;
    use Account;
    use WithPagination;

    public $form;
    public $view;
    public $view_all;
    public $key;
    public $toKey;
    public $tab;

    public $display = [
        'wm' => false,
        'sqm' => false,
        'std' => false,
        'itrprtn' => false,
        'comments' => false
    ];

    public $search = [
        'type' => '',
        'select' => ''
    ];

    public $initPaginate = false;
    public $paginate_count;
    protected $listeners = ['screen'];

    public function generate_random_code($length = 8) {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomWord = '';
        for ($i = 0; $i < $length; $i++) {
            $randomWord .= $characters[rand(0, strlen($characters) - 1)];
        }

        $this->key = $randomWord;
    }

    public function delete_responses() {
        $rules = [
            'key' => 'required',
            'toKey' => 'required|same:key'
        ];

        $this->validate($rules, [
            'toKey.same' => 'Entered code must match with the given code.'
        ], [
            'toKey' => 'Code'
        ]);

        $eval_id = $this->form['id'];
        $temp_id = $this->form['template'];
        $fac_id = $this->form['faculty'];
        $subj_id = $this->form['subject'];
        $tab = $this->form['tab'];

        ResponseModel::where('evaluation_id', $eval_id)
            ->where('template_id', $temp_id)
            ->where('faculty_id', $fac_id)
            ->delete();

        sleep(1);
        return redirect()->route('admin.programs.results', ['id' => $eval_id, 'action' => 'view', 'faculty' => $fac_id, 'template' => $temp_id, 'subject' => $subj_id, 'tab' => $tab]);
    }

    public function result_view() {

        $action = $this->form['action'];
        $id = $this->form['id'];
        $faculty = $this->form['faculty'];
        $template = $this->form['template'];
        $subject = $this->form['subject'];
        $tab = $this->form['tab'];

        if(!session()->has('settings')) {
            $this->result_settings();
        }

        $this->display = session('settings')['evaluation_result_display'];

        if($action == 'view') {

            $questionnaire = QuestionnaireModel::with('questionnaire_item.criteria')
            ->where('school_year_id', $id);

            if($questionnaire->count() != 0) {

                session()->forget('no_questionnaire');

                $questionnaire = $questionnaire->get()[0];

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
                $evaluation_result['respondents'] = $this->respondents();

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
                    'all_templates' => FacultyModel::with(
                                'templates.curriculum_template.subjects.courses.departments.branches'
                            )
                            ->where('id', $faculty)
                            ->get()[0],

                    'evaluation_result' => $evaluation_result,
                ];

                $this->view = $view;

            } else {

                session(['no_questionnaire' => 'No questionnaires added, please create first.']);

                return redirect()->route('admin.programs.results', ['id' => $this->form['id']]);
            }
        }
    }

    public function change_tabs($tab) {
        $this->tab = $tab;
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

    public function all_subjects() {
        $action = $this->form['action'];
        $id = $this->form['id'];
        $faculty = $this->form['faculty'];
        $subject = $this->form['subject'];
        $template_id = $this->form['template'];

        if(!session()->has('settings')) {
            $this->result_settings();
        }

        $this->display = session('settings')['evaluation_result_display'];

        if($action == 'view') {

            $questionnaire = QuestionnaireModel::with('questionnaire_item.criteria')
                ->where('school_year_id', $id);


            session()->forget('no_questionnaire');

            $questionnaire = $questionnaire->get()[0];

            $faculty_template = FacultyTemplateModel::with('faculty.departments.branches', 'curriculum_template.subjects.courses.departments.branches')
                ->where('faculty_id', $faculty)->get();

            $this->tab = $faculty_template[0]->template_id;

            $evaluation_results = [];

            foreach ($faculty_template as $template) {

                $key_template = $template->template_id;

                $responses = ResponseModel::with('students', 'items.questionnaire.criteria')
                    ->where('evaluation_id', $id)
                    ->where('template_id', $key_template)
                    ->where('faculty_id', $faculty)
                    ->get();

                $sorted_responses = [];
                $comments = [];

                foreach ($responses as $response) {
                    foreach ($response->items as $item) {
                        $key = $response->template_id;
                        $sorted_responses[$key][] = [
                            'questionnaire_id' => $item->questionnaire_id,
                            'response_id' => $item->response_id,
                            'response_rating' => $item->response_rating,
                        ];

                        $student_name = $response->students->firstname . ' ' . $response->students->lastname;
                        $comments[] = [
                            'commented_by' => $this->applyCensored($student_name),
                            'comment' => $response->comment
                        ];
                    }
                }

                $evaluation_result = [
                    'total_responses' => count($responses),
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
                    'stats' => []
                ];

                // Iterate through each questionnaire item
                foreach ($questionnaire['questionnaire_item'] as $item) {
                    $criteria_id = $item['criteria_id'];
                    $questionnaire_id = $item['id'];
                    $item_name = $item['item'];

                    // If $key_template doesn't exist in $sorted_responses, set all values to 0
                    if (!array_key_exists($key_template, $sorted_responses)) {
                        $sorted_responses[$key_template] = []; // Assuming $sorted_responses is an array
                    }

                    // If criteria does not exist in evaluation result, initialize it
                    if (!isset($evaluation_result['stats'][$criteria_id])) {
                        $evaluation_result['stats'][$criteria_id] = [
                            'criteria_id' => $criteria_id,
                            'criteria_name' => $item['criteria']['name'],
                            'total_responses' => count($sorted_responses[$key_template]),
                            'comments' => '', // Assuming $comments is defined elsewhere
                            'total_items' => 0,
                            'items' => [] // Initialize items array
                        ];
                    }

                    // Add item to the questionnaire under the criteria
                    $evaluation_result['stats'][$criteria_id]['items'][$questionnaire_id] = [
                        'id' => $questionnaire_id,
                        'response_id' => $questionnaire_id,
                        'name' => $item_name,
                        'weighted_mean' => 0,
                        'mean_squared' => 0,
                        'standard_deviation' => 0,
                        'interpretation' => 0,
                        'tally' => [
                            '1' => 0,
                            '2' => 0,
                            '3' => 0,
                            '4' => 0
                        ]
                    ];

                    // If $key_template exists in $sorted_responses, retrieve response rating for the current questionnaire item
                    if (array_key_exists($key_template, $sorted_responses)) {
                        foreach ($sorted_responses[$key_template] as $response) {
                            if ($response['questionnaire_id'] === $questionnaire_id) {
                                $response_rating = $response['response_rating']; // Get the response rating
                                // Increment tally for the retrieved response rating
                                $evaluation_result['stats'][$criteria_id]['items'][$questionnaire_id]['tally'][(string)$response_rating]++;
                                // Increment total items count under the criteria
                                $evaluation_result['stats'][$criteria_id]['total_items']++;
                            }
                        }
                    }
                }



                // Compute weighted mean, mean squared, standard deviation, and interpretation
                foreach ($evaluation_result['stats'] as $criteria_id => &$criteria) {
                    foreach ($criteria['items'] as $questionnaire_id => &$item) {
                        $tally = array_map(function ($key, $value) {
                            return $key * $value;
                        }, array_keys($item['tally']), $item['tally']);

                        $divisor = $evaluation_result['total_responses'] == 0 ? 1 : $evaluation_result['total_responses'];
                        $item['weighted_mean'] = array_sum($tally) / $divisor;

                        $squared_tally = array_map(function ($key, $value) {
                            return pow($key, 2) * $value;
                        }, array_keys($item['tally']), $item['tally']);

                        $item['mean_squared'] = array_sum($squared_tally) / $divisor;

                        $item['standard_deviation'] = sqrt($item['mean_squared'] - pow($item['weighted_mean'], 2));

                        $item['interpretation'] = $this->interpretation($item['weighted_mean']);
                        $evaluation_result['total_interpretation'][$item['interpretation']]++;
                    }
                }

                // Compute averages
                $total_items = 0;
                $total_mean = 0;
                $total_squared_mean = 0;
                $total_standard_deviation = 0;

                foreach ($evaluation_result['stats'] as $criteria) {
                    foreach ($criteria['items'] as $item) {
                        $total_items++;
                        $total_mean += $item['weighted_mean'];
                        $total_squared_mean += $item['mean_squared'];
                        $total_standard_deviation += $item['standard_deviation'];
                    }
                }

                if ($total_items > 0) {
                    $evaluation_result['averages'] = [
                        'mean' => $total_mean / $total_items,
                        'squared_mean' => $total_squared_mean / $total_items,
                        'standard_deviation' => $total_standard_deviation / $total_items,
                        'descriptive_interpretation' => $this->interpretation($total_mean / $total_items)
                    ];
                } else {
                    $evaluation_result['averages'] = [
                        'mean' => 0,
                        'squared_mean' => 0,
                        'standard_deviation' => 0,
                        'descriptive_interpretation' => 0
                    ];
                }

                $evaluation_result['template'] = $template->toArray();
                $evaluation_result['respondents'] = $this->respondents(1, array_values($evaluation_result));
                $evaluation_results[] = $evaluation_result;
            }

            $view = [
                'faculty' => FacultyModel::with([
                    'templates' => function($query) use ($template_id, $subject) {
                        $query->where('template_id', $template_id)
                            ->whereHas('curriculum_template.subjects', function($query) use ($subject) {
                                $query->where('subject_id', $subject);
                            });
                    },
                    'templates.curriculum_template.subjects.courses.departments.branches'
                ])
                ->where('id', $faculty)
                ->whereHas('templates', function($query) use ($template_id, $subject) {
                    $query->where('template_id', $template_id)
                        ->whereHas('curriculum_template.subjects', function($query) use ($subject) {
                            $query->where('subject_id', $subject);
                        });
                })
                ->get()[0],
                'evaluation_results' => $evaluation_results
            ];

            return $view;
        }
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

        $role = $this->admin()->role;
        $assigned_branch = $this->admin()->assigned_branch;

        $faculty = FacultyModel::with(['departments.branches'])
            ->when(strlen($this->search['type']) >= 1, function ($sQuery) {
                $sQuery->where(function($query) {
                    $query->where('firstname', 'like', '%' . $this->search['type'] . '%');
                });
                $sQuery->orWhereHas('departments', function ($dQuery) {
                    $dQuery->where('name', 'like', '%' . $this->search['type'] . '%');
                    $dQuery->orWhereHas('branches', function ($bQuery) {
                        $bQuery->where('name', 'like', '%' . $this->search['type'] . '%');
                    });
                });
            })
            ->when($this->search['select'] != '', function ($query) {
                $query->whereHas('departments.branches', function ($subQuery) {
                    $subQuery->where('branch_id', $this->search['select']);
                });
            })
            ->when($role == 'admin', function($query) use ($assigned_branch) {
                $query->whereHas('departments.branches', function($subQuery) use ($assigned_branch) {
                    $subQuery->where('branch_id', $assigned_branch);
                });
            })
        ->paginate($this->paginate_count);

        $data = [
            'departments' => $departments,
            'faculty' => $faculty
        ];

        return view('livewire.admin.evaluation-result', compact('data'));
    }

    public function save_all_pdf() {

        $view = $this->all_subjects();

        $data = [
            'view' => $view,
            'has_image' => true
        ];

        $pdf = PDF::loadView('printable.result-view-all', $data);

        $faculty = $this->view['faculty'];
        $filename = strtolower('evaluation_result_of_' . $faculty['firstname'] . '_' . $faculty['lastname'] . '_' .time().'.pdf');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);
    }

    public function save_pdf() {

        $data = [
            'view' => $this->view,
        ];

        $pdf = PDF::loadView('printable.result-view', $data);

        $faculty = $this->view['faculty'];
        $filename = strtolower('evaluation_result_of_' . $faculty->firstname . '_' . $faculty->lastname . '_' .time().'.pdf');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);

    }

    public function save_excel() {

        $data = [
            'display' => $this->display,
            'view' => $this->view
        ];

        $spreadsheet = new Spreadsheet();

        // Set active sheet
        $spreadsheet->setActiveSheetIndex(0);

        // HTML content to be converted to Excel
        $html = View::make('printable.result-view', $data)->render();

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
        $filename = strtolower('evaluation_result_of_' . $faculty->firstname . '_' . $faculty->lastname . '_' .time().'.xlsx');

        // Save Excel file
        $tempFilePath = storage_path('app/'.$filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        // Return the Excel file as a downloadable response
        return Response::download($tempFilePath, $filename)->deleteFileAfterSend(true);

    }

    public function save_all_excel() {

        $view = $this->all_subjects();

        $data = [
            'view' => $view,
            'has_image' => false
        ];

        $spreadsheet = new Spreadsheet();

        // Set active sheet
        $spreadsheet->setActiveSheetIndex(0);

        // HTML content to be converted to Excel
        $html = View::make('printable.result-view-all', $data)->render();

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
        $filename = strtolower('evaluation_result_of_' . $faculty->firstname . '_' . $faculty->lastname . '_' .time().'.xlsx');

        // Save Excel file
        $tempFilePath = storage_path('app/'.$filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        // Return the Excel file as a downloadable response
        return Response::download($tempFilePath, $filename)->deleteFileAfterSend(true);

    }
}

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

        ResponseModel::with('template')
            ->where('evaluation_id', $eval_id)
            ->where('template_id', $temp_id)
            ->where('faculty_id', $fac_id)
            ->whereHas('template', function ($query) use ($subj_id) {
                $query->where('subject_id', $subj_id);
        })->delete();

        return redirect()->route('admin.programs.results', ['id' => $eval_id, 'action' => 'view', 'faculty' => $fac_id, 'template' => $temp_id, 'subject' => $subj_id]);
    }

    public function result_view() {

        $action = $this->form['action'];
        $id = $this->form['id'];
        $faculty = $this->form['faculty'];

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

            $evaluation_result = [];

            foreach($faculty_template as $template) {
                $key_template = $template->template_id;

                $responses = ResponseModel::with('students', 'items.questionnaire.criteria')
                    ->where('evaluation_id', $id)
                    ->where('template_id', $key_template)
                    ->where('faculty_id', $faculty)->get();

                $sorted_responses = [];

                $comments = [];
                foreach ($responses as $response) {
                    $key = $response->template_id;
                    foreach ($response['items'] as $item) {
                        $sorted_responses[$key] = [
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

                foreach ($questionnaire['questionnaire_item'] as $item) {
                    $key = $item['criteria_id'];

                    $evaluation_result[$key_template] = [
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

                    if (!isset($evaluation_result[$key_template]['stats'][$key])) {
                        $evaluation_result[$key_template]['stats'][$key] = [
                            'id' => $item['id'],
                            'criteria_name' => $item['criteria']['name'],
                            'items' => []
                        ];
                    }

                    $evaluation_result[$key_template]['total_items']++;

                    foreach ($sorted_responses as $response) {
                        if ($response['questionnaire_id'] == $item['id']) {
                            $evaluation_result[$key_template]['total_responses'] = count($responses);
                            if (!isset($evaluation_result[$key_template]['stats'][$key]['items'][$response['questionnaire_id']])) {
                                $evaluation_result[$key_template]['stats'][$key]['items'][$response['questionnaire_id']] = [
                                    'id' => $item['id'],
                                    'response_id' => $response['response_id'],
                                    'name' => $item['item'],
                                    'weighted_mean' => 0,
                                    'mean_squared' => 0,
                                    'standard_deviation' => 0,
                                    'interpretation' => 0,
                                    'comments' => $comments,
                                    'tally' => [
                                        '1' => 0,
                                        '2' => 0,
                                        '3' => 0,
                                        '4' => 0
                                    ]
                                ];
                            }

                            $evaluation_result[$key_template]['stats'][$key]['items'][$response['questionnaire_id']]['tally'][$response['response_rating']]++;
                        }
                    }

                }


                $evaluation_result[$key_template]['stats'] = array_values($evaluation_result[$key_template]['stats']);
                foreach ($evaluation_result[$key_template]['stats'] as &$criteria) {
                    $criteria['items'] = array_values($criteria['items']);
                }

                // compute weighted mean
                foreach ($evaluation_result[$key_template]['stats'] as $criteriaKey => &$criteria) {
                    foreach ($criteria['items'] as $itemKey => $item) {
                        $tally = [];
                        foreach ($item['tally'] as $rating => $value) {
                            $tally[$rating] = $rating * $value;
                        }
                        $total = array_sum($tally) / (int) $evaluation_result[$key_template]['total_responses'];
                        $evaluation_result[$key_template]['stats'][$criteriaKey]['items'][$itemKey]['weighted_mean'] = $total;
                    }
                }

                foreach ($evaluation_result[$key_template]['stats'] as $criteriaKey => &$criteria) {
                    foreach ($criteria['items'] as $itemKey => $item) {
                        $tally = [];
                        foreach ($item['tally'] as $rating => $value) {
                            $squared = ($rating * $rating);
                            $tally[$rating] = $squared * $value;
                        }
                        $total = array_sum($tally) / (int) $evaluation_result[$key_template]['total_responses'];
                        $evaluation_result[$key_template]['stats'][$criteriaKey]['items'][$itemKey]['mean_squared'] = $total;
                    }
                }

                foreach ($evaluation_result[$key_template]['stats'] as &$criteria) {
                    foreach ($criteria['items'] as $itemKey => $item) {
                        $total = sqrt((int)$item['mean_squared'] - (int) $item['weighted_mean']);
                        $evaluation_result[$key_template]['stats'][$criteriaKey]['items'][$itemKey]['standard_deviation'] = $total;
                    }
                }

                foreach ($evaluation_result[$key_template]['stats'] as &$criteria) {
                    foreach ($criteria['items'] as $itemKey => $item) {
                        $interpretation = $this->interpretation($item['weighted_mean']);
                        $evaluation_result[$key_template]['stats'][$criteriaKey]['items'][$itemKey]['interpretation'] = $interpretation;
                        $evaluation_result[$key_template]['total_interpretation'][$interpretation]++;
                    }
                }


                // computer averages

                $mean = 0;
                $squared = 0;
                $std = 0;

                foreach($evaluation_result[$key_template]['stats'] as $key => $results) {

                    foreach($results['items'] as $items) {
                        $mean += $items['weighted_mean'];
                        $squared += $items['mean_squared'];
                        $std += $items['standard_deviation'];
                    }

                    if($evaluation_result[$key_template]['total_responses'] > 0) {
                        $evaluation_result[$key_template]['averages'] = [
                            'mean' => $mean / $evaluation_result[$key_template]['total_items'],
                            'squared_mean' => $squared / $evaluation_result[$key_template]['total_items'],
                            'standard_deviation' => $std / $evaluation_result[$key_template]['total_items'],
                            'descriptive_interpretation' => $this->interpretation($mean / $evaluation_result[$key_template]['total_items'])
                        ];
                    } else {
                        $evaluation_result[$key_template]['averages'] = [
                            'mean' => 0,
                            'squared_mean' => 0,
                            'standard_deviation' => 0,
                            'descriptive_interpretation' => 0
                        ];
                    }

                }

                $evaluation_result[$key_template]['template'] = $template->toArray();
                $evaluation_result[$key_template]['respondents'] = $this->respondents(1, array_values($evaluation_result));
                $evaluation_result = array_values($evaluation_result);
            }

            $this->view = $evaluation_result;


        }
    }

    public function mount() {
        $this->result_view();
        $this->generate_random_code();
    }

    public function changeTabs($tab) {
        $this->tab = $tab;
        $filteredArray = array_filter($this->view, function ($array) use ($tab) {
            return isset($array['template']['curriculum_template'][0]['subject_id']) && $array['template']['curriculum_template'][0]['subject_id'] === $tab;
        });
        $filteredArray = array_values($filteredArray);

        $filteredArray[0]['respondents'] = $this->respondents($tab, $filteredArray);

        $this->dispatch('tabs', tab: $tab, data: $filteredArray);
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

    public function respondents($subj_id, $data) {
        $total_respondents = StudentModel::with('courses.template')
            ->whereHas('courses.template', function($query) use ($subj_id, $data) {
                $query->where('id', $data[0]['template']['template_id']);
            })->whereHas('courses.template', function($query) use ($subj_id) {
                $query->where('subject_id', $subj_id);
            })
            ->get()->count();

        $respondents = ResponseModel::where('evaluation_id', $this->form['id'])
            ->where('template_id', $data[0]['template']['template_id'])
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

    public function save_pdf() {

        $data = [
            'display' => $this->display,
            'view' => $this->view,
            'has_image' => true
        ];

        $pdf = PDF::loadView('printable.result-view', $data);

        $faculty = $this->view[0]['template']['faculty'];
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

        $faculty = $this->view[0]['template']['faculty'];
        $filename = strtolower('evaluation_result_of_' . $faculty['firstname'] . '_' . $faculty['lastname'] . '_' .time().'.xlsx');

        // Save Excel file
        $tempFilePath = storage_path('app/'.$filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        // Return the Excel file as a downloadable response
        return Response::download($tempFilePath, $filename)->deleteFileAfterSend(true);

    }
}

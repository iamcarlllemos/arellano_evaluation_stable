<?php

namespace App\Livewire\Student;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\QuestionnaireModel;
use App\Models\QuestionnaireItemModel;

use App\Models\FacultyModel;
use App\Models\FacultyTemplateModel;
use App\Models\SchoolYearModel;

use App\Models\ResponseModel;
use App\Models\ResponseItemModel;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Livewire\Component;

class Evaluate extends Component
{

    public $form;

    public $evaluate;
    public $semester;
    public $template_id;

    public $step;
    public $faculty_id;

    public $faculty = [];

    public $start_time;
    public $end_time;
    public $comments;

    public $is_exists = false;

    public $questionnaire;
    public $responses = [];

    public function validate_step() {
        $data = [
            'step' => $this->step
        ];

        $rules = [
            'step' => 'required|integer|in:1,2,3,4'
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return false;
        }

        return true;
    }

    public function move($step) {
        if($step == 1) {
            $data = session('response');
            $data['step'] = $step;
            $this->step = $step;
            session()->put('response', $data);
        } else if($step == 2) {
            $this->step02($step);
        } else if($step == 3) {
            $this->step03($step);
        } else if($step == 4) {
            $this->step04($step);
        }
    }

    public function step02($step) {

        $attr = [
            'faculty_id' => 'Faculty',
            'start_time' => 'Scheduled start time',
            'end_time' => 'Scheduled end time'
        ];

        $rules = [
            'faculty_id' => 'required|exists:afears_faculty,id|integer',
            'start_time' => 'required',
            'end_time' => 'required',
        ];

        $this->validate($rules, [], $attr);

        $response = session('response');

        if(array_key_exists('faculty', $response)) {
            $response['step'] = $step;
            $response['faculty'] = [
                'is_preview' => false,
                'user_id' => auth()->guard('students')->user()->id,
                'evaluation_id' => $this->evaluate,
                'faculty_id' => $this->faculty_id,
                'template_id' => $this->template_id,
                'semester' => $this->semester,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'comment' => $this->comments
            ];
            session()->put('response', $response);
        } else {
            $data['response'] = [
                'step' => $step,
                'faculty' => [
                    'is_preview' => false,
                    'user_id' => auth()->guard('students')->user()->id,
                    'evaluation_id' => $this->evaluate,
                    'faculty_id' => $this->faculty_id,
                    'template_id' => $this->template_id,
                    'semester' => $this->semester,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'comment' => $this->comments,
                ]
            ];

            session($data);

        }

        $this->remember_responses();
        $this->get_questionnaires();
    }

    public function step03($step) {
        $dirty_item_id = array_keys($this->responses);

        $questionnaire_id = $this->questionnaire->id;
        $data = QuestionnaireItemModel::where('questionnaire_id', $questionnaire_id)->get();

        $cleaned_item_id = $data->pluck('id')->toArray();

        $imposter = array_diff($cleaned_item_id, $dirty_item_id);

        if(!empty($imposter)) {
            session()->flash('error', $imposter);
            $data = session('response');
            $data['record'] = $this->responses;
            session()->put('response', $data);
        } else {
            $data = session('response');
            $data['step'] = $step;
            $data['record'] = $this->responses;
            session()->put('response', $data);
            $this->faculty_info(session('response.faculty'));
        }

        $this->get_questionnaires();
        $this->is_responded($step);

    }

    public function step04($step) {
        $data = session('response');
        if(!$data['faculty']['is_preview']) {
            if (array_key_exists('step', $data) && array_key_exists('faculty', $data) && array_key_exists('record', $data)) {
                $data['faculty']['comment'] = $this->comments;
                if(!empty($this->comments)) {

                    $inserted = ResponseModel::create($data['faculty']);

                    $response_items = [];

                    foreach($data['record'] as $key => $value) {
                        $response_items[] = [
                            'response_id' => $inserted->id,
                            'questionnaire_id' => $key,
                            'response_rating' => $value
                        ];
                    }

                    ResponseItemModel::insert($response_items);

                    $data['step'] = $step;
                    session()->put('response', $data);
                    $this->is_responded(4);
                } else {
                    $this->get_questionnaires();
                    session()->flash('error', ['comment']);
                }
            }
        } else {
            $data['step'] = $step;
            session()->put('response', $data);
            $this->is_responded(4);
        }
    }

    public function get_questionnaires() {
        $questionnaire = QuestionnaireModel::with('school_year', 'questionnaire_item.criteria')->where(function($query) {
            $query->whereHas('school_year', function($subQuery) {
                $subQuery->where('semester', $this->semester);
            });
        });

        if($questionnaire->count() > 0) {
            $data = $questionnaire->get()[0];
            $sorted_item = [];
            foreach($data['questionnaire_item'] as $item) {
                $key = $item['criteria_id'];
                if(!isset($sorted_item[$key])) {
                    $sorted_item[$key] = [
                        'id' => $item['id'],
                        'criteria_name' => $item['criteria']['name'],
                        'item' => []
                    ];
                }

                $sorted_item[$key]['item'][] = [
                    'id' => $item['id'],
                    'name' => $item['item']
                ];
            }

            $data['sorted_items'] = array_values($sorted_item);

            $this->questionnaire = $data;
        } else {
            return redirect()->route('student.subject', ['evaluate' => $this->evaluate, 'semester' => $this->semester])->with('error', 'No questionnaires created yet');
        }

    }

    public function remember_responses() {
        $response = session('response');
        if(array_key_exists('record', $response)) {
            foreach($response['record'] as $item => $value) {
                $this->responses[$item] = $value;
            }
        }
    }

    public function is_responded($step) {

        $user_id = auth()->guard('students')->user()->id;
        $response_items = ResponseModel::with('items')->where('user_id', $user_id)
            ->where('evaluation_id', $this->evaluate)
            ->where('template_id', $this->template_id)
            ->where('semester', $this->semester);

        if($response_items->exists()) {

            $this->is_exists = true;
            $data = $response_items->get()[0];
            $this->comments = $data->comment;

            $reference = 'au_afears_response_'.$data->id;

            $response = session('response');
            $response['step'] = $step;
            $response['faculty'] = [
                'is_preview' => true,
                'user_id' => $data->user_id,
                'evaluation_id' => $data->evaluation_id,
                'faculty_id' => $data->faculty_id,
                'template_id' => $data->template_id,
                'semester' => $data->semester,
                'start_time' => $data->start_time,
                'end_time' => $data->end_time,
                'comment' => $data->comment,
                'date_submitted' => Carbon::parse($data->created_at)->diffForHumans(),
                'reference' => $reference,
                'qr_code' => QrCode::generate($reference)
            ];


            $items = [];

            foreach($data['items'] as $item) {
                $items[$item->questionnaire_id] = $item->response_rating;
            }

            $response['record'] = $items;

            session()->put('response', $response);
            $this->faculty_info($response['faculty']);
            $this->remember_responses();

        }
    }

    public function faculty_info($data) {
        $faculty_id = $data['faculty_id'];
        $evaluate_id = $this->evaluate;

        $faculty = FacultyModel::find($faculty_id);
        $subject = FacultyTemplateModel::with('faculty.templates.curriculum_template.subjects')->where(function($query) {
            $query->where('template_id', $this->template_id);
        })->get()[0];

        $evaluate = SchoolYearModel::find($evaluate_id);

        $this->faculty['name'] = $faculty->firstname . ' ' . $faculty->lastname;
        $this->faculty['subject'] = $subject->faculty->templates[0]->curriculum_template[0]->subjects->name . ' (' .
            $subject->faculty->templates[0]->curriculum_template[0]->subjects->code . ')';
        $this->faculty['schedule'] = to_hour($data['start_time']) . ' - ' . to_hour($data['end_time']);
        $this->faculty['academic_year'] = $evaluate->start_year . ' - ' . $evaluate->end_year;
    }

    public function go_back() {
        $data = session('response');
        if (count($data) > 1 && !$data['faculty']['is_preview']) {
            $this->dispatch('leaving', ['has_saved' => true, 'route' => route('student.subject', ['evaluate' => $this->evaluate, 'semester' => $this->semester])]);
            $this->get_questionnaires();
        } else {
            return redirect()->route('student.subject', ['evaluate' => $this->evaluate, 'semester' => $this->semester]);
        }
    }

    public function download_qr() {
        return response()->streamDownload(function() {
            $reference  = session('response')['faculty']['reference'];
            echo QrCode::size(200)
                ->format('png')
                ->generate($reference);
        }, 'qr_code', [
            'Content-Type' => 'image/png'
        ]);
    }

    public function mount(Request $request) {

        $evaluate = $request->input('evaluate');
        $semester = $request->input('semester');
        $template_id = $request->input('template');

        $this->evaluate = $evaluate;
        $this->semester = $semester;
        $this->template_id = $template_id;

        $this->step = session('response')['step'];

        $input = [
            'id' => $evaluate,
            'semester' => $semester,
            'template_id' => $template_id
        ];

        $rules = [
            'id' => [
                'required',
                'integer',
                Rule::exists('afears_school_year')->where(function($query) use($evaluate, $semester) {
                    $query->where('id', $evaluate)
                        ->where('semester', $semester)
                        ->where('status', '1');
                })
            ],
            'semester' => [
                'required',
                'integer'
            ],
            'template_id' => [
                'required',
                'integer',
                'exists:afears_curriculum_template,id'
            ]
        ];

        $validate = Validator::make($input, $rules);

        if($validate->fails()) {
            return redirect()->route('student.dashboard');
        }


        if(session()->has('response')) {
            $saved = session('response');

            if(array_key_exists('faculty', $saved)) {
                $data = session('response.faculty');
                $this->faculty_id = $data['faculty_id'];
                $this->start_time = $data['start_time'];
                $this->end_time = $data['end_time'];
                $this->comments = $data['comment'];
            }

        }

        if(!empty($this->step) && $this->step != 3) {
            $this->get_questionnaires();
            $this->is_responded(3);
        }

        if($this->step == 2) {
            $this->get_questionnaires();
            $this->remember_responses();
        } else if ($this->step == 3) {
            $this->get_questionnaires();
            $this->remember_responses();
            $this->faculty_info(session('response.faculty'));
        } else if($this->step == 4) {

            if(!$this->is_exists) {
                $data['response'] = [
                    'step' => $this->step
                ];

                session($data);
            }

            $this->is_responded(4);

        }
    }

    public function render() {
        return view('livewire.student.evaluate');
    }
}

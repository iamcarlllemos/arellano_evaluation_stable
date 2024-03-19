<?php

namespace App\Livewire\Admin;

use App\Models\CriteriaModel;
use App\Models\QuestionnaireModel;
use App\Models\QuestionnaireItemModel;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;



class QuestionnaireItem extends Component
{

    public $form;
    public $select;
    public bool $is_update = false;
    public $search;
    public $id;
    public $slug;
    public $questionnaire_id;
    public $questionnaire_item_id;
    public $criteria_id;
    public $item;
    public $criterias;
    public $items;

    public function mount(Request $request) {

        $slug = explode('/', $request->getRequestUri());
        $slug = end($slug);

        $this->slug = $slug;

        $data = QuestionnaireModel::where('slug', $slug)->first();

        $this->id = $data->id ?? '';
        $this->questionnaire_id = $data->id ?? '';

        $this->loadItems();
    }
    public function loadItems() {


        $dirty_data = QuestionnaireModel::with(['questionnaire_item.criteria'])
            ->where('slug', $this->slug)->get();


        $cleaned_data = [];

        foreach ($dirty_data[0]['questionnaire_item'] as $item) {
            $key = $item['criteria']->name;
            
            if(!isset($cleaned_data[$key])) {
                $cleaned_data[$key] = [
                    'criteria' => $key,
                    'questionnaire_item' => []
                ];
            }

            $cleaned_data[$key]['questionnaire_item'][] = [
                'id' => $item->id,
                'items' => $item->item
            ];
        }

        $this->items = array_values($cleaned_data);

    }

    public function placeholder() {
        return view('livewire.placeholder');
    }

    public function save(Request $request) {


        if(empty($this->questionnaire_item_id)) {
            $this->is_update = false;
        }

        if(!$this->is_update) {
            $rules = [
                'questionnaire_id' => 'required|integer|exists:afears_questionnaire,id',
                'criteria_id' => 'required|integer|exists:afears_criteria,id',
                'item' => [
                    'required',
                    'string',
                    'min:8',
                ]
            ];
    
            $this->validate($rules);
    
            $data = [
                'questionnaire_id' =>  $this->questionnaire_id,
                'criteria_id' =>  $this->criteria_id,
                'item' =>  $this->item,
            ];
    
            try {
    
                QuestionnaireItemModel::create($data);
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Questionnaire item added'
                ]);
    
                $this->criteria_id = '';
                $this->item = '';
    
            } catch (\Exception $e) {
    
                session()->flash('flash', [
                    'status' => 'failed',
                    'message' => $e->getMessage()
                ]);
            }       
        } else {

            $rules = [
                'questionnaire_id' => 'required|integer|exists:afears_questionnaire,id',
                'questionnaire_item_id' => 'required|integer|exists:afears_questionnaire_item,id',
                'criteria_id' => 'required|integer|exists:afears_criteria,id',
                'item' => [
                    'required',
                    'string',
                    'min:8',
                ]
            ];

            $this->validate($rules);
            
            $data = [
                'criteria_id' =>  $this->criteria_id,
                'item' =>  $this->item,
            ];

            try {
    
                QuestionnaireItemModel::where('id', $this->questionnaire_item_id)->update($data);
    
                session()->flash('flash', [
                    'status' => 'success',
                    'message' => 'Questionnaire item updated'
                ]);
    
                $this->questionnaire_item_id = '';
                $this->criteria_id = '';
                $this->item = '';
    
            } catch (\Exception $e) {
    
                session()->flash('flash', [
                    'status' => 'failed',
                    'message' => $e->getMessage()
                ]);
            }       

        }
        
    }

    public function update($id) {

        $data = QuestionnaireItemModel::where('id', $id);
        if($data->exists()) {
            $data = $data->first();
            $this->is_update = true;
            $this->questionnaire_id = $data->questionnaire_id;
            $this->questionnaire_item_id = $data->id;
            $this->criteria_id = $data->criteria_id;
            $this->item = $data->item;
        } else {
            return redirect()->route('admin.programs.questionnaire');
        }
       
    }

    public function delete($id) {

        $model = QuestionnaireItemModel::where('id', $id)->first();

        if($model) {
            $model->delete();
            $this->questionnaire_item_id = '';
            $this->criteria_id = '';
            $this->item = '';
            session()->flash('flash', [
                'status' => 'success',
                'message' => 'Questionnaire item deleted successfully'
            ]);
        } else {
            session()->flash('flash', [
                'status' => 'failed',
                'message' => 'No records found for id `'.$id.'`. Unable to delete.'
            ]);
        }
    }
    public function render(Request $request) {
        
        $action = $request->input('action') ?? '';

        if($action == 'open') {
            $view = $request->input('view');
            if(in_array($view, ['courses'])) {
                $id = $request->input('id');
                $this->select = $id;
            }
        }

        $questionnaire = QuestionnaireModel::with(['school_year'])
            ->when(strlen($this->search) >= 1, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->get();
        

        $questionnaire = $questionnaire->isEmpty() ? [] : $questionnaire;

        $data = [
            'questionnaire' => $questionnaire,
            'data' => $this->items
        ];

        return view('livewire.admin.questionnaire-item', compact('data'));
    }
}

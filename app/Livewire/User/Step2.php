<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\QuestionnaireModel;

class Step2 extends Component
{

    public $questionnaire;

    public function move($step) {
        $data = session('response');
        $data['step'] = $step;
        session()->put('response', $data);
    }

    public function mount() {
        $data = QuestionnaireModel::with('school_year', 'questionnaire_item.criteria')->where(function($query) {
            $query->whereHas('school_year', function($subQuery) {
                $subQuery->where('semester', 1);
            });
        })->get()[0];
        
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
    }
    
    public function render()
    {
        return view('livewire.user.step2');
    }
}

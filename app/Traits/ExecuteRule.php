<?php

namespace App\Traits;

use App\Models\CurriculumTemplateModel;

trait ExecuteRule {

    public function rule($data) {
        $array_format = $data->toArray();
        dd($this->search($array_format));
    }

    public function search($data) {
        $filtered_data = [];
    
        foreach ($data as $key => $value) {
            if ($key === 'branch_id' && $value == 4) {
                // Append a copy of the current $data array to $filtered_data
                $filtered_data[] = $data;
            }
            if (is_array($value)) {
                // Recursively search nested arrays
                $nested_filtered_data = $this->search($value);
                if (!empty($nested_filtered_data)) {
                    // Append each item from $nested_filtered_data to $filtered_data
                    foreach ($nested_filtered_data as $nested_data) {
                        $filtered_data[] = $nested_data;
                    }
                }
            }
        }
    
        return $filtered_data;
    }
    
    

}
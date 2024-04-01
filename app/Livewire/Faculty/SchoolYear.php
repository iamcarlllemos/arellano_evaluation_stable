<?php

namespace App\Livewire\Faculty;

use Livewire\Component;
use App\Models\SchoolYearModel;

class SchoolYear extends Component
{

    public $school_year;

    public function mount() {
        $this->school_year = SchoolYearModel::all()[0];
    }

    public function render() {
        return view('livewire.faculty.school-year');
    }
}

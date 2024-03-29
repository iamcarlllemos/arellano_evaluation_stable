<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\SchoolYearModel;

class SchoolYear extends Component
{

    public $school_year;

    public function mount() {
        $this->school_year = SchoolYearModel::all()[0];
    }

    public function render() {
        return view('livewire.student.school-year');
    }
}

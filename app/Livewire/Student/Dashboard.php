<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\StudentModel;

class Dashboard extends Component
{
    public function render() {
        return view('livewire.student.dashboard');
    }
}

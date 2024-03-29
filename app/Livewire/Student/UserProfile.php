<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\StudentModel;

class UserProfile extends Component
{


    public $user;

    public function mount() {
        $id = auth()->guard('students')->user()->id;
        $data = StudentModel::with('courses')->where('id', $id)->first();
        return $this->user = $data;
    }

    public function render()
    {
        return view('livewire.student.user-profile');
    }
}

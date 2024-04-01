<?php

namespace App\Livewire\Faculty;

use Livewire\Component;
use App\Models\FacultyModel;

class UserProfile extends Component
{


    public $user;

    public function mount() {
        $id = auth()->guard('faculty')->user()->id;
        $data = FacultyModel::with('departments.branches')->where('id', $id)->first();
        return $this->user = $data;
    }

    public function render()
    {
        return view('livewire.faculty.user-profile');
    }
}

<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\StudentModel;

class UserProfile extends Component
{


    public $user;

    public function mount() {
        $id = auth()->guard('users')->user()->id;
        $data = StudentModel::with('courses')->first();
        return $this->user = $data;
    }

    public function render()
    {
        return view('livewire.user.user-profile');
    }
}

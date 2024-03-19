<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\StudentModel;

class Dashboard extends Component
{
    public function render() {
        return view('livewire.user.dashboard');
    }
}

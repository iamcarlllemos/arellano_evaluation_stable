<?php

namespace App\Livewire\Faculty;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render() {
        return view('livewire.faculty.dashboard');
    }
}

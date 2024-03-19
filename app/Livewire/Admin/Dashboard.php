<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Carbon;

class Dashboard extends Component
{

    public $data = [];


    public function render()
    {

        $time = Carbon::now();
        $mode = strtolower($time->format('H'));

        $mode_string = '';

        if ($mode >= 5 && $mode < 12) {
            $mode_string = "good morning";
        } elseif ($mode >= 12 && $mode < 18) {
            $mode_string = "good afternoon";
        } elseif ($mode >= 18 && $mode < 22) {
            $mode_string = "good evening";
        }

        $mode_string = ucwords($mode_string);
        $name = auth()->guard('admins')->user()->name;

        $message = $mode_string . '! ' . $name ;

        $this->data = [
            'message' => $message,
        ];

        return view('livewire.admin.dashboard');
    }
}

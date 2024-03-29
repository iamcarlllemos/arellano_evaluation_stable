<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {

        $data = [
            'breadcrumbs' => 'Dashboard,home',
            'livewire' => [
                'component' => 'student.dashboard',
                'data' => []
            ]
        ];

        return view('student.template', compact('data'));
    }
}

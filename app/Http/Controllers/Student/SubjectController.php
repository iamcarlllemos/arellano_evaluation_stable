<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    public function index() {

        $data = [
            'breadcrumbs' => 'Dashboard,evaluate',
            'livewire' => [
                'component' => 'student.subject',
                'data' => [

                ]
            ]
        ];

        $init['response'] = [
            'step' => 1,
        ];

        session($init);

        return view('student.template', compact('data'));
    }
}

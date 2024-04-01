<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    public function index() {

        $data = [
            'breadcrumbs' => 'Dashboard,evaluate',
            'livewire' => [
                'component' => 'faculty.subject',
                'data' => [

                ]
            ]
        ];

        $init['response'] = [
            'step' => 1,
        ];

        session($init);

        return view('faculty.template', compact('data'));
    }
}

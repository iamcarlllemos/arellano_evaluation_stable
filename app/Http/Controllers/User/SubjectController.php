<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    public function index() {

        $data = [
            'breadcrumbs' => 'Dashboard,evaluate',
            'livewire' => [
                'component' => 'user.subject',
                'data' => [

                ]
            ]
        ];

        $init['response'] = [
            'step' => 1,
        ];

        session($init);

        return view('user.template', compact('data'));
    }
}

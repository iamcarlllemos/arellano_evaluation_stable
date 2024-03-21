<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {

        $data = [
            'breadcrumbs' => 'Dashboard, home',
            'livewire' => [
                'component' => 'admin.dashboard',
                'data' => [
                    'lazy' => true,
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}

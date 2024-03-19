<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        
        $data = [
            'breadcrumbs' => 'Dashboard,home',
            'livewire' => [
                'component' => 'user.dashboard',
                'data' => []
            ]
        ];

        return view('user.template', compact('data'));
    }
}

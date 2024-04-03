<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class SmtpController extends Controller
{
    public function index(Request $request) {

        $data = [
            'breadcrumbs' => 'Dashboard,programs,school year',
            'livewire' => [
                'component' => 'admin.smtp',
                'data' => [
                    'lazy' => true,
                    'form' => [
                        'action' => 'update',
                        'update' => [
                            'title' => 'All Students',
                            'subtitle' => 'List of all students created.',
                            'data' => [
                                'servername' => [
                                    'label' => 'Server / Hostname',
                                    'type' => 'text',
                                    'placeholder' => 'ex. smtp@example.com',
                                    'required' => false,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-6',
                                ],
                                'port' => [
                                    'label' => 'Port',
                                    'type' => 'text',
                                    'placeholder' => 'ex. 587',
                                    'required' => false,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-4',
                                ],
                                'username' => [
                                    'label' => 'Username',
                                    'type' => 'text',
                                    'placeholder' => 'ex. smtp_username',
                                    'required' => false,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                                'password' => [
                                    'label' => 'Password',
                                    'type' => 'password',
                                    'placeholder' => '••••••••••••••••',
                                    'required' => false,
                                    'disabled' => false,
                                    'css' => 'col-span-12 md:col-span-12',
                                ],
                            ],
                        ],
                    ],
                ]
            ]
        ];

        return view('template', compact('data'));
    }
}

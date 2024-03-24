<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Livewire\Admin\SchoolYear;
use App\Models\SchoolYearModel;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    public function index(Request $request) {

        $id = $request->input('id');

        $is_exists = SchoolYearModel::where('id', $id)->exists();

        if(!$is_exists) {
            return redirect()->route('admin.programs.school-year');
        }


        $action = $request->input('action');
        $faculty = $request->input('faculty');
        $template = $request->input('template');
        $subject = $request->input('subject');

        $data = [
            'breadcrumbs' => 'Dashboard,programs,school year',
            'livewire' => [
                'component' => 'admin.evaluation-results',
                'data' => [
                    'lazy' => true,
                    'form' => [
                        'id' => $id,
                        'action' => $action,
                        'faculty' => $faculty,
                        'template' => $template,
                        'subject' => $subject,
                        'index' => [
                            'title' => 'Evaluation Results',
                            'subtitle' => 'List of all faculties evaluation results.'
                        ]
                    ],
                ]

            ]
        ];

        return view('template', compact('data'));
    }
}

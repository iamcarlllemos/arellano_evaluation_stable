<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SchoolYearModel;

class EvaluateController extends Controller
{
    public function index(Request $request) {

        $id = $request->input('id');
        $action = $request->input('action');
        $faculty = $request->input('faculty');
        $template = $request->input('template');
        $subject = $request->input('subject');
        $semester = $request->input('semester');

        $is_exists = SchoolYearModel::where('id', $id)->exists();

        if(!$is_exists || $action != 'view') {
            return redirect()->route('faculty.subject', ['evaluate' => $id, 'semester' => $semester]);
        }

        $data = [
            'breadcrumbs' => 'Dashboard,programs,school year',
            'livewire' => [
                'component' => 'faculty.evaluation-results',
                'data' => [
                    'lazy' => true,
                    'form' => [
                        'id' => $id,
                        'action' => $action,
                        'faculty' => $faculty,
                        'template' => $template,
                        'subject' => $subject,
                        'semester' => $semester,
                        'index' => [
                            'title' => 'Evaluation Results',
                            'subtitle' => 'List of all faculties evaluation results.'
                        ]
                    ],
                ]

            ]
        ];

        return view('faculty.template', compact('data'));
    }
}

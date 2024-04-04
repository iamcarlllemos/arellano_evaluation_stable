<?php

namespace App\Http\Controllers;

use PDF;

class PrintResultController extends Controller
{

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function save() {

        $filename = 'test.pdf';

        $html = view()->make('printable.result-view', $this->data)->render();

        $pdf = new PDF;

        $pdf::SetTitle('Result View');
        $pdf::AddPage('L');
        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::Output(public_path('pdf/' . $filename), 'F');


        return $filename;
    }
}

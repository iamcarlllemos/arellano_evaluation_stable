<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintResultController extends Controller
{

    // public $data;

    // public function __construct($data)
    // {
    //     $this->data = $data;
    // }

    public function save() {


        $pdf = PDF::loadView('printable.result-view');

        return $pdf->download('users-lists.pdf');

    }
}

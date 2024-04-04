<?php

namespace App\Http\Controllers;

use App\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function index() {
        try {
            Mail::to('llemoscarl671@gmail.com')
            ->send(new Mailer);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function index() {

        $data = [
            'view' => 'mail.notify',
            'name' => '',
            'student_number' => '',
            'username' => '',
            'password' => '',
        ];

        try {
            Mail::to('iamcarlllemos@gmail.com')
            ->send(new Mailer($data));
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}

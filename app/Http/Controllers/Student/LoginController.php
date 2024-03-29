<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function index() {
        return view('student.login');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        $credentials = $request->only('username', 'password');

        if(Auth::guard('students')->attempt($credentials)) {
            $user = Auth::guard('students')->user();
            if($user) {
                Auth::guard('students')->login($user);
                return redirect()->route('student.dashboard');
            } else {
                return redirect()->back()->with('error', 'User not found');
            }
        }

        return redirect()->back()->with('error', 'username or password is invalid');
    }

    public function logout() {
        Auth::guard('students')->logout();
        return redirect()->route('user.login');
    }

}

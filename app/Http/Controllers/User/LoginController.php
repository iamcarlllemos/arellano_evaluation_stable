<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentModel;
use Auth;

class LoginController extends Controller
{

    public function index() {
        return view('user.login');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        $credentials = $request->only('username', 'password');

        if(Auth::guard('users')->attempt($credentials)) {
            $user = Auth::guard('users')->user();
            if($user) {
                Auth::guard('users')->login($user);
                return redirect()->route('user.dashboard');
            } else {
                return redirect()->back()->with('error', 'User not found');
            }
        } 

        return redirect()->back()->with('error', 'username or password is invalid');
    }

    public function logout() {
        Auth::guard('users')->logout();
        return redirect()->route('user.login');
    }

}

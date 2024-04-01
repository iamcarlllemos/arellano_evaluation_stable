<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function index() {
        return view('faculty.login');
    }

    public function login(Request $request) {

        $rules = [
            'username' => 'required|string',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $credentials = $request->only('username', 'password');

        if(Auth::guard('faculty')->attempt($credentials)) {
            $user = Auth::guard('faculty')->user();
            if($user) {
                Auth::guard('faculty')->login($user);
                return redirect()->route('faculty.dashboard');
            } else {
                return redirect()->back()->with('error', 'User not found');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Username or password is invalid');
    }

    public function logout() {
        Auth::guard('faculty')->logout();
        return redirect()->route('faculty.login');
    }

}

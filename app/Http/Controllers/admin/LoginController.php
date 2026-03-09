<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
public function index() {
        return view('admin.login', [
            'meta_title' => 'ADMIN LOGIN | MIRROR BOOTH EVENT SERVICES L.L.C',
            'meta_description' => 'LOGIN TO THE ADMIN PANEL OF MIRROR BOOTH EVENT SERVICES L.L.C TO MANAGE YOUR EVENTS AND SERVICES.',
            'meta_keywords' => 'ADMIN LOGIN, MIRROR BOOTH, LOGIN, EVENTS, ADMIN PANEL, MIRROR BOOTH EVENT SERVICES L.L.C'
        ]);
    }

    // This method authenticates the admin
    public function authenticate(Request $request) {

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->passes()){
            if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])){
                if(Auth::guard('admin')->user()->role != "admin"){
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error','You are not authorized to access this page.');
                }
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('admin.login')->with('error','Email or password is incorrect.');
            }
        } else {
            return redirect()->route('admin.login')->withInput()->withErrors($validator);
        }
    }

    // This method logs the admin out
    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success','Logged out successfully!');
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function VistaLogin(){
        if (!empty(Cookie::get('puesto'))) {
            return redirect()->route('home');
        } else {
            return view('Login.login');
        }        
    }

    public function LoginVerify(Request $request){
        print_r($request->all());
    }
}
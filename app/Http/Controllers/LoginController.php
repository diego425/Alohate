<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    public function VistaLogin(){
        if (!empty(Cookie::get('puesto')) && !empty(Cookie::get('Id_colaborador'))) {
            return redirect()->route('home');
        } else {
            return view('Login.login');
        }        
    }

    public function LoginVerify(Request $request){
        $request->validate([
            'user' => 'required|max:30',
            'password' => 'required|max:8|min:0',
        ]);

        if (!empty($request->user) && !empty($request->password)) {
            $contra = Crypt::encryptString($request->password);
            $datos = DB::select('SELECT * FROM colaboradores
            LEFT JOIN roles AS rol ON rol.id_rol = colaboradores.id_rol
            WHERE user = ?', [$request->user]);
            $datos = json_decode(json_encode($datos));
            // print_r($datos);
            if (count((array)$datos) == 1) {
                if ($request->password === Crypt::decryptString($datos[0]->password)) {
                    return redirect()->route('login')
                    ->withCookie('Id_colaborador',$datos[0]->Id_colaborador)
                    ->withCookie('puesto',$datos[0]->nameRol)
                    ->withCookie('user',$datos[0]->user);
                }else {
                    return redirect()->back()->with('error','Contraseña incorrecta');
                }
            }else {
                return redirect()->back()->with('error','Contraseña o usuario incorrectos');
            }
        }else {
            return redirect()->back()->with('error','Campos vacios');
        }
    }

    public function cerrarSesion()
    {
        return redirect()->route('login')
        ->withCookie(Cookie::forget('Id_colaborador'))
        ->withCookie(Cookie::forget('puesto'))
        ->withCookie(Cookie::forget('user'));
    }
}
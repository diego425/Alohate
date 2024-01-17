<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = DB::table('colaboradores')
        ->leftJoin('roles','roles.id_rol','=','colaboradores.id_rol')
        ->paginate(10);

        return view('Usuarios.index',['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = DB::table('roles')->get();
        return view('Usuarios.create',['roles'=>$roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $contra = Crypt::encryptString($request->password);
        $datos = DB::select('select * from colaboradores where user = ?',[$request->user]);

        if (count((array)$datos) == 0) {
            $insert = DB::table('colaboradores')->insert(
                $request->except([
                    '_token'
                ])
            );
            $idUsuario = DB::getPdo()->lastInsertId();
    
            if ($insert) {
                $upd = DB::update('UPDATE colaboradores set password = ? where Id_colaborador = ?', [$contra,$idUsuario]);

                /* if (!empty($request->correoElectronico)) {
                    Mail::to("".$request->correoElectronico."")->send(new mailContrasena($request->password, $request->razonSocial));
                } */
            }
    
            if ($insert) {
                return redirect()->route('user.index')->with('message','Registro insertado');
            }else {
                session()->flashInput($request->input());
                return redirect()->back()->with('error','No se pudo insertar el registro');
            }
        }else {
            session()->flashInput($request->input());
            return redirect()->back()->with('error','El usuario ya existe');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = DB::table('colaboradores')
        ->where('Id_colaborador',$id)
        ->get();
        $roles = DB::table('roles')->get();

        return view('Usuarios.edit',['users'=>$users,'roles'=>$roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
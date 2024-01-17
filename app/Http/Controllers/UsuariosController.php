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
        $datos = DB::select('SELECT * FROM colaboradores
        WHERE user = ? AND Id_colaborador != ?',[$request->user,$id]);
        $usuario = DB::select('SELECT * FROM colaboradores
        WHERE Id_colaborador = ?',[$id]);
        if (count((array)$datos) == 0) {
            $update = DB::table('colaboradores')
            ->where('Id_colaborador', $id)
            ->update([
                'user' => $request->user,
                'Nombre' => $request->Nombre,
                'Apellido_pat' => $request->Apellido_pat,
                'Apellido_mat' => $request->Apellido_mat,
                'Numero_cel' => $request->Numero_cel,
                'Calle' => $request->Calle,
                'Numero_casa' => $request->Numero_casa,
                'email' => $request->email,
                'id_rol' => $request->id_rol,
                'Estatus_col' => $request->Estatus_col
            ]);

            if ($usuario[0]->password == $request->password) {
            }else{
                $contra = Crypt::encryptString($request->password);
                $update = DB::update('UPDATE colaboradores 
                SET password = ? WHERE Id_colaborador = ?', [$contra,$id]);
            }
    
            if ($update) {
                return redirect()->route('user.index')->with('message','Registro actualizado');
            }else {
                return redirect()->back()->with('error','No se pudo actualizar el registro');
            }
        }else {
            return redirect()->back()->with('error','El usuario ya lo tiene otro colaborador');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
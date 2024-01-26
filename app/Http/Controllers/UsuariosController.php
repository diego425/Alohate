<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
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
    public function index(Request $request)
    {
        if (!empty(Cookie::get('puesto'))) {
            if (Cookie::get('puesto') == "ADMIN" || Cookie::get('puesto') == "OPERADOR") {
                $users = DB::table('colaboradores')
                ->where('colaboradores.user','LIKE',"%$request->buscar%")
                ->orWhere('colaboradores.Nombre','LIKE',"%$request->buscar%")
                ->orWhere('roles.nameRol','LIKE',"%$request->buscar%")
                ->leftJoin('roles','roles.id_rol','=','colaboradores.id_rol')
                ->paginate(5);
        
                return view('Usuarios.index',['users'=>$users]);
            } else {
                return view('Errores.error403');
            }
        } else {
            return redirect()->route('login');
        }
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
        $request->validate([
            'user' => 'required|max:30',
            'Nombre' => 'required',
            'id_rol' => 'required',
            'Estatus_col' => 'required',
            'password' => 'required|max:8|min:0',
        ]);
        
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
        $user = DB::select('SELECT * FROM colaboradores
        LEFT JOIN roles AS rol ON rol.id_rol = colaboradores.id_rol
        WHERE Id_colaborador  = ?', [$id]);
        if (!empty($user[0])) {
            $user = $user[0];
            return view('Usuarios.show',['user'=>$user]);
        } else {
            return view('Errores.error500');
        }
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
        $request->validate([
            'user' => 'required|max:30',
            'Nombre' => 'required',
            'id_rol' => 'required',
            'Estatus_col' => 'required',
            'password' => 'required|max:8|min:0',
        ]);

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

            if (!empty($request->Fotografia)) {
                $update = DB::table('colaboradores')
                ->where('Id_colaborador', $id)
                ->update([
                    'Fotografia' => $request->Fotografia,
                ]);
            }

            if ($request->password == "password") {
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

    public function gestionLugares(Request $request,string $id) {
        $user = DB::table('colaboradores')
        ->where('Id_colaborador', $id)
        ->get();
        $user = json_decode(json_encode($user));
        $user = $user[0];
        $nombreLocacion = $request->locacion;
        $tipoLugar = $request->tipoLugar;

        $locales = DB::table('local')
        ->selectRaw('("mostrar") AS mostrar,Id_local AS id,Id_locacion,cola.Id_colaborador,(SELECT loc.Nombre_locacion FROM locacion AS loc WHERE loc.Id_locacion = local.Id_locacion LIMIT 1) AS nombreLocacion,
        cola.Id_colaborador, cola.Nombre as NombreCola, cola.Apellido_pat, local.Nombre_local AS Nombre, ("Local") AS tipoLocacion, eo.Nombre_estado')
        ->leftJoin('estado_ocupacion as eo','eo.Id_estado_ocupacion','=','local.Id_estado_ocupacion')
        ->leftJoin('colaboradores as cola','cola.Id_colaborador','=','local.Id_colaborador');
        
        $departamentos = DB::table('departamento')
        ->selectRaw('("mostrar") AS mostrar,Id_departamento AS id,Id_locacion,cola.Id_colaborador,(SELECT loc.Nombre_locacion FROM locacion AS loc WHERE loc.Id_locacion = departamento.Id_locacion LIMIT 1) AS nombreLocacion,
        cola.Id_colaborador, cola.Nombre as NombreCola, cola.Apellido_pat, departamento.Nombre_depa AS Nombre, ("Departamento") AS tipoLocacion, eo.Nombre_estado')
        ->leftJoin('estado_ocupacion as eo','eo.Id_estado_ocupacion','=','departamento.Id_estado_ocupacion')
        ->leftJoin('colaboradores as cola','cola.Id_colaborador','=','departamento.Id_colaborador');

        $habitaciones = DB::table('habitacion')
        ->selectRaw('("mostrar") AS mostrar,Id_habitacion AS id,Id_locacion,cola.Id_colaborador,(SELECT loc.Nombre_locacion FROM locacion AS loc WHERE loc.Id_locacion = habitacion.Id_locacion LIMIT 1) AS nombreLocacion,
        cola.Id_colaborador, cola.Nombre as NombreCola, cola.Apellido_pat, habitacion.Nombre_hab AS Nombre, ("Habitación") AS tipoLocacion, eo.Nombre_estado')
        ->leftJoin('estado_ocupacion as eo','eo.Id_estado_ocupacion','=','habitacion.Id_estado_ocupacion')
        ->leftJoin('colaboradores as cola','cola.Id_colaborador','=','habitacion.Id_colaborador');

        $locaciones = DB::table('locacion')
        ->selectRaw('("mostrar") AS mostrar,Id_locacion AS id,Id_locacion,cola.Id_colaborador,("General") AS nombreLocacion,
        cola.Id_colaborador, cola.Nombre as NombreCola, cola.Apellido_pat, Nombre_locacion AS Nombre, ("Entera") AS tipoLocacion, eo.Nombre_estado')
        ->leftJoin('estado_ocupacion as eo','eo.Id_estado_ocupacion','=','locacion.Id_estado_ocupacion')
        ->leftJoin('colaboradores as cola','cola.Id_colaborador','=','locacion.Id_colaborador')
        ->union($locales)
        ->union($habitaciones)
        ->union($departamentos)
        ->get();

        if (!empty($nombreLocacion)) {
            foreach ($locaciones as $key => $value) {
                if ($value->Id_locacion == $nombreLocacion) {
                }else{
                    $locaciones[$key]->mostrar = "ocultar";
                }
            }
        }
        
        if (!empty($tipoLugar)) {
            foreach ($locaciones as $key => $value) {
                if ($value->tipoLocacion == $tipoLugar) {
                }else{
                    $locaciones[$key]->mostrar = "ocultar";
                }
            }
        }

        $tipos = array(
            array(
                "tipoLocacion" => "Entera",
                "total" => 0
            ),array(
                "tipoLocacion" => "Local",
                "total" => 0
            ),array(
                "tipoLocacion" => "Departamento",
                "total" => 0
            ),array(
                "tipoLocacion" => "Habitación",
                "total" => 0
            )
        );
        $tipos = json_decode(json_encode($tipos));

        foreach ($locaciones as $key => $item) {
            foreach ($tipos as $key2 => $tipo) {
                if ($item->tipoLocacion == $tipo->tipoLocacion) {
                    $tipos[$key2]->total++;
                }
            }
        }

        session()->flashInput($request->input());
        return view('Usuarios.gestionLugares',["locaciones" => $locaciones, "tipos" => $tipos, "user" => $user]);
    }

    public function asignarLugar(Request $request) {
        if (!empty($request->id) && !empty($request->tipo) && !empty($request->Id_colaborador)) {
            $user = DB::table('colaboradores')
            ->where('Id_colaborador', $request->id)
            ->get();
            $user = json_decode(json_encode($user));
            $user = $user[0];

            $regreso = array();
            if ($request->check == "true") {
                if ($request->tipo === "Entera") {
                    $affected = DB::table('locacion')
                    ->where('Id_locacion', $request->id)
                    ->update(['Id_colaborador' => (int)$request->Id_colaborador]);
                    if ($affected) {
                        UsuariosController::historial_log(Cookie::get('Id_colaborador'),
                        "Asigno todos los sub-lugares de $request->lugar a $user->Nombre $user->Apellido_pat");

                        array_push($regreso, array(
                            "mensaje" => "Se relaciono en todos los sub-lugares"
                        ));
                        
                        $affected1 = DB::table('local')
                        ->where('Id_locacion', $request->id)
                        ->update(['Id_colaborador' => (int)$request->Id_colaborador]);
                        if ($affected1) {
                            array_push($regreso, array(
                                "mensaje" => "Se relaciono el local"
                            ));
                        }
                        
                        $affected2 = DB::table('departamento')
                        ->where('Id_locacion', $request->id)
                        ->update(['Id_colaborador' => (int)$request->Id_colaborador]);
                        if ($affected2) {
                            array_push($regreso, array(
                                "mensaje" => "Se relaciono el departamento"
                            ));
                        }
                        
                        $affected3 = DB::table('habitacion')
                        ->where('Id_locacion', $request->id)
                        ->update(['Id_colaborador' => (int)$request->Id_colaborador]);
                        if ($affected3) {
                            array_push($regreso, array(
                                "mensaje" => "Se relaciono el habitacion"
                            ));
                        }                        
                    }
                }elseif ($request->tipo === "Local") {
                    $affected = DB::table('local')
                    ->where('id_local', $request->id)
                    ->update(['Id_colaborador' => (int)$request->Id_colaborador]);
                    if ($affected) {
                        UsuariosController::historial_log(Cookie::get('Id_colaborador'),
                        "Se asigno el local $request->lugar de $request->locacion a $user->Nombre $user->Apellido_pat");
                        array_push($regreso, array(
                            "mensaje" => "Se relaciono el local"
                        ));
                    }
                }elseif ($request->tipo === "Departamento") {
                    $affected2 = DB::table('departamento')
                    ->where('Id_departamento', $request->id)
                    ->update(['Id_colaborador' => (int)$request->Id_colaborador]);
                    if ($affected2) {
                        UsuariosController::historial_log(Cookie::get('Id_colaborador'),
                        "Se asigno el departamento $request->lugar de $request->locacion a $user->Nombre $user->Apellido_pat");
                        array_push($regreso, array(
                            "mensaje" => "Se relaciono el departamento"
                        ));
                    }
                }elseif ($request->tipo === "Habitación") {
                    $affected3 = DB::table('habitacion')
                    ->where('Id_habitacion', $request->id)
                    ->update(['Id_colaborador' => (int)$request->Id_colaborador]);
                    if ($affected3) {
                        UsuariosController::historial_log(Cookie::get('Id_colaborador'),
                        "Se asigno la habitacion $request->lugar de $request->locacion a $user->Nombre $user->Apellido_pat");
                        array_push($regreso, array(
                            "mensaje" => "Se relaciono la habitacion"
                        ));
                    }
                }
            } else {
                if ($request->tipo === "Entera") {
                    $affected = DB::table('locacion')
                    ->where('Id_locacion', $request->id)
                    ->where('Id_colaborador', (int)$request->Id_colaborador)
                    ->update(['Id_colaborador' => null]);
                    if ($affected) {
                        UsuariosController::historial_log(Cookie::get('Id_colaborador'),
                        "Quito todos los sub-lugares de $request->lugar a $user->Nombre $user->Apellido_pat");
                        array_push($regreso, array(
                            "mensaje" => "Se quito en todos los sub-lugares"
                        ));

                        $affected1 = DB::table('local')
                        ->where('Id_locacion', $request->id)
                        ->where('Id_colaborador', (int)$request->Id_colaborador)
                        ->update(['Id_colaborador' => null]);
                        if ($affected1) {
                            array_push($regreso, array(
                                "mensaje" => "Se relaciono el local"
                            ));
                        }
                        
                        $affected2 = DB::table('departamento')
                        ->where('Id_locacion', $request->id)
                        ->where('Id_colaborador', (int)$request->Id_colaborador)
                        ->update(['Id_colaborador' => null]);
                        if ($affected2) {
                            array_push($regreso, array(
                                "mensaje" => "Se relaciono el departamento"
                            ));
                        }
                        
                        $affected3 = DB::table('habitacion')
                        ->where('Id_locacion', $request->id)
                        ->where('Id_colaborador', (int)$request->Id_colaborador)
                        ->update(['Id_colaborador' => null]);
                        if ($affected3) {
                            array_push($regreso, array(
                                "mensaje" => "Se relaciono el habitacion"
                            ));
                        }
                    }
                }elseif ($request->tipo === "Local") {
                    $affected = DB::table('local')
                    ->where('id_local', $request->id)
                    ->update(['Id_colaborador' => null]);
                    if ($affected) {
                        UsuariosController::historial_log(Cookie::get('Id_colaborador'),
                        "Se quito el local $request->lugar de $request->locacion a $user->Nombre $user->Apellido_pat");
                        array_push($regreso, array(
                            "mensaje" => "Se quito el local"
                        ));
                    }
                }elseif ($request->tipo === "Departamento") {
                    $affected2 = DB::table('departamento')
                    ->where('Id_departamento', $request->id)
                    ->update(['Id_colaborador' => null]);
                    if ($affected2) {
                        UsuariosController::historial_log(Cookie::get('Id_colaborador'),
                        "Se quito el departamento $request->lugar de $request->locacion a $user->Nombre $user->Apellido_pat");
                        array_push($regreso, array(
                            "mensaje" => "Se relaciono el departamento"
                        ));
                    }
                }elseif ($request->tipo === "Habitación") {
                    $affected3 = DB::table('habitacion')
                    ->where('Id_habitacion', $request->id)
                    ->update(['Id_colaborador' => null]);
                    if ($affected3) {
                        UsuariosController::historial_log(Cookie::get('Id_colaborador'),
                        "Se quito la habitacion $request->lugar de $request->locacion a $user->Nombre $user->Apellido_pat");
                        array_push($regreso, array(
                            "mensaje" => "Se relaciono el habitacion"
                        ));
                    }
                }
            }
            
            return $regreso;
        }
        // print_r($request->all());
    }

    public static function historial_log(string $Id_colaborador = null, string $Descripcion_actividad = null) {
        if (!empty($Id_colaborador) && !empty($Descripcion_actividad)) {
            DB::table('historial_log')->insert([
                'Id_colaborador' => $Id_colaborador,
                'Fecha_hora_actividad' => date("Y-m-d h:i:s"),
                'Descripcion_actividad' => strtoupper($Descripcion_actividad)
            ]);
        }
    }
}
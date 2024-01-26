<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class LimpiezaMantenimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!empty(Cookie::get('puesto'))) {
            if (Cookie::get('puesto') == "ADMIN" || Cookie::get('puesto') == "OPERADOR" || Cookie::get('puesto') == "AUXILIAR") {
                $reportes = DB::table('reportes_m_l')
                ->paginate(5);
        
                return view('LimpiezaMantenimiento.index',['reportes'=>$reportes]);
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
    public function create(Request $request)
    {
        $lugar = array();
        $tipoLocacion = "";
        $id = "";
        $Id_locacion = "";
        $nombreLugar = "";
        if (!empty($request->tipoLocacion) && !empty($request->id) && !empty($request->Id_locacion)) {
            $tipoLocacion = $request->tipoLocacion;
            $id = $request->id;
            $Id_locacion = $request->Id_locacion;

            $lugar = DB::select('call seleccionarLugar(?,?,?);',[$id,$tipoLocacion,$Id_locacion]);
            $lugar = json_decode(json_encode($lugar));

            if (!empty($lugar[0]->Id_locacion)) {
                if ($tipoLocacion == "Entera") {
                    $nombreLugar = "$tipoLocacion: ".$lugar[0]->Nombre;
                } else {
                    $nombreLugar = $lugar[0]->Nombre_locacion.", $tipoLocacion: ".$lugar[0]->Nombre;
                }
            }else{
                $tipoLocacion = "";
                $id = "";
                $Id_locacion = "";
                $nombreLugar = "";
            }
        }

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
        $roles = DB::table('roles')->get();
        return view('LimpiezaMantenimiento.create',['lugar'=>$lugar,'roles' => $roles,
        'locaciones' => $locaciones,
        'Id_locacion' => $Id_locacion, 'id' => $id, 'tipoLocacion' => $tipoLocacion,
        'nombreLugar' => $nombreLugar]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idLugar' => 'required',
            'tipoLocacion' => 'required',
            'Id_locacion' => 'required',
            'Descripcion_Reporte' => 'required',
            'Tipo_reporte' => 'required',
        ]);
        
        $insert = 0;

        if ($request->tipoLocacion == "Entera") {
            $insert = DB::table('reportes_m_l')->insert([
                'Id_locacion' => $request->Id_locacion,
                'Id_colaborador' => Cookie::get('Id_colaborador'),
                'Descripcion_Reporte' => $request->Descripcion_Reporte,
                'Fecha_del_reporte' => date("Y-m-d"),
                'Estatus' => "Pendiente",
                'Categoria_mtto' => $request->Categoria_mtto,
                'Tipo_reporte' => $request->Tipo_reporte,
                'tipoLocacion' => $request->tipoLocacion,
            ]);
        }elseif ($request->tipoLocacion === "Local") {
            $insert = DB::table('reportes_m_l')->insert([
                'Id_locacion' => $request->Id_locacion,
                'Id_local' => $request->Id_local,
                'Id_colaborador' => Cookie::get('Id_colaborador'),
                'Descripcion_Reporte' => $request->Descripcion_Reporte,
                'Fecha_del_reporte' => date("Y-m-d"),
                'Estatus' => "Pendiente",
                'Categoria_mtto' => $request->Categoria_mtto,
                'Tipo_reporte' => $request->Tipo_reporte,
                'tipoLocacion' => $request->tipoLocacion,
            ]);
        }elseif ($request->tipoLocacion === "Departamento") {
            $insert = DB::table('reportes_m_l')->insert([
                'Id_locacion' => $request->Id_locacion,
                'Id_departamento' => $request->Id_departamento,
                'Id_colaborador' => Cookie::get('Id_colaborador'),
                'Descripcion_Reporte' => $request->Descripcion_Reporte,
                'Fecha_del_reporte' => date("Y-m-d"),
                'Estatus' => "Pendiente",
                'Categoria_mtto' => $request->Categoria_mtto,
                'Tipo_reporte' => $request->Tipo_reporte,
                'tipoLocacion' => $request->tipoLocacion,
            ]);
        }elseif ($request->tipoLocacion === "Habitación") {
            $insert = DB::table('reportes_m_l')->insert([
                'Id_locacion' => $request->Id_locacion,
                'Id_habitacion' => $request->Id_habitacion,
                'Id_colaborador' => Cookie::get('Id_colaborador'),
                'Descripcion_Reporte' => $request->Descripcion_Reporte,
                'Fecha_del_reporte' => date("Y-m-d"),
                'Estatus' => "Pendiente",
                'Categoria_mtto' => $request->Categoria_mtto,
                'Tipo_reporte' => $request->Tipo_reporte,
                'tipoLocacion' => $request->tipoLocacion,
            ]);
        }

        if ($insert) {
            return redirect()->route('limpieza.index')->with('message','Registro insertado');
        } else {
            session()->flashInput($request->input());
            return redirect()->back()->with('error','No se pudo insertar el registro');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $Id_reporte_ml)
    {
        $tipos = array(
            "Id_habitacion",
            "Id_departamento",
            "Id_local",
            "Id_locacion"
        );

        $reporte = DB::table('reportes_m_l AS rml')
        ->selectRaw('rml.*,loc.*,TIMEDIFF(rml.Fecha_termino,rml.Fecha_inicio) AS tiempoTotal')
        ->leftJoin('locacion as loc','loc.Id_locacion','=','rml.Id_locacion')
        ->where('rml.Id_reporte_ml',$Id_reporte_ml)
        ->get();
        $reporte = json_decode(json_encode($reporte),true);
        $reporte = $reporte[0];

        $id = null;
        $lugar = array();
        for ($i=0; $i < 4; $i++) {
            if (!empty($reporte[$tipos[$i]])) {
                $id = $reporte[$tipos[$i]];
                break;
            }
        }

        $lugar = DB::select('call seleccionarLugar(?,?,?);',[$id,$reporte["tipoLocacion"],$reporte["Id_locacion"]]);
        $lugar = json_decode(json_encode($lugar));
        $reporte = json_decode(json_encode($reporte));

        $fotos = DB::select('SELECT * FROM `fotos_tarea_limpieza` WHERE Id_reporte_ml = ?;',[$Id_reporte_ml]);
        $fotos = json_decode(json_encode($fotos),true);
        
        // print_r($fotos);
        return view('LimpiezaMantenimiento.show',['reporte' => $reporte, 'lugar' => $lugar, 'fotos' => $fotos]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

    function realizar(string $id) {
        $tipos = array(
            "Id_habitacion",
            "Id_departamento",
            "Id_local",
            "Id_locacion"
        );

        $reporte = DB::table('reportes_m_l AS rml')
        ->selectRaw('rml.*,loc.*,TIMEDIFF(rml.Fecha_termino,rml.Fecha_inicio) AS tiempoTotal')
        ->leftJoin('locacion as loc','loc.Id_locacion','=','rml.Id_locacion')
        ->where('Id_reporte_ml',$id)
        ->get();
        $reporte = json_decode(json_encode($reporte),true);
        $reporte = $reporte[0];

        $id = null;
        $lugar = array();
        for ($i=0; $i < 4; $i++) {
            if (!empty($reporte[$tipos[$i]])) {
                $id = $reporte[$tipos[$i]];
                break;
            }
        }

        $lugar = DB::select('call seleccionarLugar(?,?,?);',[$id,$reporte["tipoLocacion"],$reporte["Id_locacion"]]);
        $lugar = json_decode(json_encode($lugar));
        $reporte = json_decode(json_encode($reporte));
        
        if ($reporte->Estatus == 'Terminado') {
            return redirect()->route('limpieza.index')->with('message','Esta tarea ya esta terminada');
        } else {
            return view('LimpiezaMantenimiento.realizarTarea',['reporte' => $reporte, 'lugar' => $lugar]);
        }
        
    }

    function guardarTarea(Request $request) {
        $request->validate([
            'foto1' => 'required',
            'Tipo' => 'required',
        ]);

        // print_r($request->all());

        $Estatus = "Iniciada";
        if ($request->Tipo == "Antes") {
            $Estatus = "Iniciado";
        } elseif ($request->Tipo == "Despues") {
            $Estatus = "Terminado";
        }
        
        $insert = DB::table('fotos_tarea_limpieza')->insert([
            'Id_reporte_ml' => $request->id,
            'Tipo' => $request->Tipo,
            'foto1' => $request->foto1,
            'foto2' => $request->foto2,
            'foto3' => $request->foto3
        ]);

        if ($insert) {
            if ($request->Tipo == "Antes") {
                $affected = DB::table('reportes_m_l')
                ->where('Id_reporte_ml', $request->id)
                ->update([
                    'Estatus' => $Estatus,
                    'Fecha_inicio' => date("Y-m-d h:i:s")
                ]);
            } elseif ($request->Tipo == "Despues") {
                $affected = DB::table('reportes_m_l')
                ->where('Id_reporte_ml', $request->id)
                ->update([
                    'Estatus' => $Estatus,
                    'Fecha_termino' => date("Y-m-d h:i:s")
                ]);
            }

            return redirect()->route('limpieza.index')->with('message','Registro insertado');
        }else {
            session()->flashInput($request->input());
            return redirect()->back()->with('error','No se pudo insertar el registro');
        }
    }
}

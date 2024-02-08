<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class PagosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tipos = array(
            "Id_habitacion",
            "Id_departamento",
            "Id_local",
            "Id_locacion"
        );

        $cobrosSin = DB::table('cobro_renta AS cr')
        ->selectRaw('cr.Id_cobro_renta,cr.Id_reservacion, cr.Cobro_persona_extra, cr.Periodo_total, cr.Estatus_cobro,
        cr.Tiempo_rebasado, cr.Deposito_garantia, cr.Monto_total, cr.Saldo, res.Start_date, res.End_date,
        res.Monto_pagado_anticipo, res.Title,lr.Id_habitacion, lr.Id_locacion, lr.Id_local, lr.Id_departamento, eo.Nombre_estado
        , ("") AS lugarEspecifico, ("") AS tipoLocacion, eso.Nombre_estado AS Estado, ("Si") AS mostrar')
            ->leftJoin('reservacion AS res', 'res.Id_reservacion', '=', 'cr.Id_reservacion')
            ->leftJoin('lugares_reservados AS lr', 'cr.Id_lugares_reservados', '=', 'lr.Id_lugares_reservados')
            ->leftJoin('estado_ocupacion AS eo', 'eo.Id_estado_ocupacion', '=', 'lr.Id_estado_ocupacion')
            ->leftJoin('estado_ocupacion AS eso', 'eso.Id_estado_ocupacion', '=', 'cr.Estatus_cobro')
        ->whereRaw('cr.Id_locacion IS NULL')
        ->groupByRaw('cr.Id_cobro_renta')
        ->orderByDesc('cr.Id_cobro_renta')
        ->get();

        foreach ($cobrosSin as $key => $reporte) {
            $reporte = json_decode(json_encode($reporte), true);
            $id = null;
            $lugar = array();
            $tipoLocacion = '';
            for ($i = 0; $i < 4; $i++) {
                if (!empty($reporte[$tipos[$i]])) {
                    if ($tipos[$i] == 'Id_locacion') {
                        $tipoLocacion = "Entera";
                    } elseif ($tipos[$i] == 'Id_local') {
                        $tipoLocacion = "Local";
                    } elseif ($tipos[$i] == 'Id_departamento') {
                        $tipoLocacion = "Departamento";
                    } elseif ($tipos[$i] == 'Id_habitacion') {
                        $tipoLocacion = "Habitación";
                    }

                    $id = $reporte[$tipos[$i]];
                    break;
                }
            }
            $lugar = DB::select('call seleccionarLugar(?,?,?);', [$id, $tipoLocacion, $reporte["Id_locacion"]]);
            $lugar = json_decode(json_encode($lugar), true);

            if (!empty($lugar[0]['Id_locacion'])) {
                $affected = DB::table('cobro_renta')
                ->where('Id_cobro_renta', $cobrosSin[$key]->Id_cobro_renta)
                ->update(['Id_locacion' => $lugar[0]['Id_locacion']]);
            }

            $cobrosSin[$key]->lugarEspecifico = $lugar;
            $cobrosSin[$key]->tipoLocacion = $tipoLocacion;
        }

        if (!empty(Cookie::get('puesto'))) {
            if (Cookie::get('puesto') == "ADMIN" || Cookie::get('puesto') == "OPERADOR" || Cookie::get('puesto') == "AUXILIAR") {
                $buscar = $request->buscar;
                $Id_locacion = $request->Id_locacion;
                $statusCobro = $request->statusCobro;

                $locaciones = DB::table('locacion')
                ->get();

                $cobros = DB::table('cobro_renta AS cr')
                    ->selectRaw('cr.Id_cobro_renta,cr.Id_reservacion, cr.Cobro_persona_extra, cr.Periodo_total, cr.Estatus_cobro,
                cr.Tiempo_rebasado, cr.Deposito_garantia, cr.Monto_total, cr.Saldo, res.Start_date, res.End_date,
                res.Monto_pagado_anticipo, res.Title,lr.Id_habitacion, lr.Id_locacion, lr.Id_local, lr.Id_departamento, eo.Nombre_estado
                , ("") AS lugarEspecifico, ("") AS tipoLocacion, eso.Nombre_estado AS Estado, ("Si") AS mostrar')
                    ->leftJoin('reservacion AS res', 'res.Id_reservacion', '=', 'cr.Id_reservacion')
                    ->leftJoin('lugares_reservados AS lr', 'cr.Id_lugares_reservados', '=', 'lr.Id_lugares_reservados')
                    ->leftJoin('estado_ocupacion AS eo', 'eo.Id_estado_ocupacion', '=', 'lr.Id_estado_ocupacion')
                    ->leftJoin('estado_ocupacion AS eso', 'eso.Id_estado_ocupacion', '=', 'cr.Estatus_cobro')
                    ->leftJoin('pago_renta AS pr', 'pr.Id_cobro_renta', '=', 'cr.Id_cobro_renta')
                    ->groupByRaw('cr.Id_cobro_renta')
                ->where(function($query) use($buscar){
                    if($buscar)
                        return  $query->where('cr.Id_cobro_renta', $buscar)
                        ->orWhere('res.Title','LIKE',"%$buscar%")
                        ->orWhere('eo.Nombre_estado','LIKE',"%$buscar%");
                })
                ->where(function($query) use($Id_locacion){
                    if($Id_locacion)
                        return  $query->orWhere('cr.Id_locacion', $Id_locacion);
                })
                ->where(function($query) use($statusCobro){
                    if($statusCobro == '7' || $statusCobro == '8'){
                        return  $query->orWhere('cr.Estatus_cobro', $statusCobro);
                    }elseif ($statusCobro == 'porConfirmar') {
                        return  $query->where('pr.Estatus_pago', '=', "Pendiente");
                    }
                })
                ->orderByDesc('cr.Id_cobro_renta')
                ->paginate(5);

                $tipos = array(
                    "Id_habitacion",
                    "Id_departamento",
                    "Id_local",
                    "Id_locacion"
                );

                foreach ($cobros as $key => $reporte) {
                    $reporte = json_decode(json_encode($reporte), true);
                    $id = null;
                    $lugar = array();
                    $tipoLocacion = '';
                    for ($i = 0; $i < 4; $i++) {
                        if (!empty($reporte[$tipos[$i]])) {
                            if ($tipos[$i] == 'Id_locacion') {
                                $tipoLocacion = "Entera";
                            } elseif ($tipos[$i] == 'Id_local') {
                                $tipoLocacion = "Local";
                            } elseif ($tipos[$i] == 'Id_departamento') {
                                $tipoLocacion = "Departamento";
                            } elseif ($tipos[$i] == 'Id_habitacion') {
                                $tipoLocacion = "Habitación";
                            }

                            $id = $reporte[$tipos[$i]];
                            break;
                        }
                    }
                    $lugar = DB::select('call seleccionarLugar(?,?,?);', [$id, $tipoLocacion, $reporte["Id_locacion"]]);
                    $lugar = json_decode(json_encode($lugar), true);

                    if (!empty($lugar[0]['Id_locacion'])) {
                        if ($lugar[0]['Id_locacion'] != $cobros[$key]->Id_locacion) {
                            $affected = DB::table('cobro_renta')
                            ->where('Id_cobro_renta', $cobros[$key]->Id_cobro_renta)
                            ->update(['Id_locacion' => $lugar[0]['Id_locacion']]);
                        }
                    }

                    $cobros[$key]->lugarEspecifico = $lugar;
                    $cobros[$key]->tipoLocacion = $tipoLocacion;
                }

                /* if (!empty($Id_locacion)) {
                    foreach ($cobros as $key => $value) {
                        if ($value->Id_locacion == $Id_locacion) {
                        }else{
                            $cobros[$key]->mostrar = "ocultar";
                        }
                    }
                } */

                session()->flashInput($request->input());
                return view('Pagos.index', ['cobros' => $cobros, 'locaciones' => $locaciones]);
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
    public function create(Request $request, string $id)
    {
        $cobros = DB::table('cobro_renta AS cr')
            ->selectRaw('cr.Id_cobro_renta,cr.Id_reservacion, cr.Cobro_persona_extra, cr.Periodo_total, cr.Estatus_cobro,
                cr.Tiempo_rebasado, cr.Deposito_garantia, cr.Monto_total, cr.Saldo, res.Start_date, res.End_date,
                res.Monto_pagado_anticipo, res.Title,lr.Id_habitacion, lr.Id_locacion, lr.Id_local, lr.Id_departamento, eo.Nombre_estado
                , ("") AS lugarEspecifico, ("") AS tipoLocacion, eso.Nombre_estado AS Estado')
            ->leftJoin('reservacion AS res', 'res.Id_reservacion', '=', 'cr.Id_reservacion')
            ->leftJoin('lugares_reservados AS lr', 'cr.Id_lugares_reservados', '=', 'lr.Id_lugares_reservados')
            ->leftJoin('estado_ocupacion AS eo', 'eo.Id_estado_ocupacion', '=', 'lr.Id_estado_ocupacion')
            ->leftJoin('estado_ocupacion AS eso', 'eso.Id_estado_ocupacion', '=', 'cr.Estatus_cobro')
            ->where('cr.Id_cobro_renta', $id)
            ->groupByRaw('cr.Id_cobro_renta')
            ->get();
        
        $formaPagos = DB::table('forma_pago')
        ->orderBy('c_FormaPago','ASC')
        ->get();

        $tipos = array(
            "Id_habitacion",
            "Id_departamento",
            "Id_local",
            "Id_locacion"
        );

        foreach ($cobros as $key => $reporte) {
            $reporte = json_decode(json_encode($reporte), true);
            $id = null;
            $lugar = array();
            $tipoLocacion = '';
            for ($i = 0; $i < 4; $i++) {
                if (!empty($reporte[$tipos[$i]])) {
                    if ($tipos[$i] == 'Id_locacion') {
                        $tipoLocacion = "Entera";
                    } elseif ($tipos[$i] == 'Id_local') {
                        $tipoLocacion = "Local";
                    } elseif ($tipos[$i] == 'Id_departamento') {
                        $tipoLocacion = "Departamento";
                    } elseif ($tipos[$i] == 'Id_habitacion') {
                        $tipoLocacion = "Habitación";
                    }

                    $id = $reporte[$tipos[$i]];
                    break;
                }
            }
            $lugar = DB::select('call seleccionarLugar(?,?,?);', [$id, $tipoLocacion, $reporte["Id_locacion"]]);
            $lugar = json_decode(json_encode($lugar), true);

            $cobros[$key]->Title = json_decode($cobros[$key]->Title);
            $cobros[$key]->lugarEspecifico = $lugar;
            $cobros[$key]->tipoLocacion = $tipoLocacion;
        }

        session()->flashInput($request->input());
        return view('Pagos.create', ['cobros' => $cobros, 'formaPagos' => $formaPagos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id)
    {
        $data = DB::table('cobro_renta AS cr')
        ->selectRaw('cr.Id_cobro_renta, cr.Id_lugares_reservados,cr.Id_reservacion, cr.Cobro_persona_extra, cr.Periodo_total, cr.Estatus_cobro,
            cr.Tiempo_rebasado, cr.Deposito_garantia, cr.Monto_total, cr.Saldo, res.Start_date, res.End_date,
            res.Monto_pagado_anticipo, res.Title,lr.Id_habitacion, lr.Id_locacion, lr.Id_local, lr.Id_departamento, eo.Nombre_estado
            , ("") AS lugarEspecifico, ("") AS tipoLocacion, eso.Nombre_estado AS Estado')
        ->leftJoin('reservacion AS res', 'res.Id_reservacion', '=', 'cr.Id_reservacion')
        ->leftJoin('lugares_reservados AS lr', 'cr.Id_lugares_reservados', '=', 'lr.Id_lugares_reservados')
        ->leftJoin('estado_ocupacion AS eo', 'eo.Id_estado_ocupacion', '=', 'lr.Id_estado_ocupacion')
        ->leftJoin('estado_ocupacion AS eso', 'eso.Id_estado_ocupacion', '=', 'cr.Estatus_cobro')
        ->where('cr.Id_cobro_renta', $id)
        ->groupByRaw('cr.Id_cobro_renta')
        ->get();
        $data = json_decode(json_encode($data));

        if (!empty($data[0]->Id_lugares_reservados)) {
            $insert = DB::table('pago_renta')->insert([
                'Id_cobro_renta' => $id,
                'Id_lugares_reservados' => $data[0]->Id_lugares_reservados,
                'Id_colaborador' => Cookie::get('Id_colaborador'),
                'Monto_pago' => $request->Monto_pago,
                'Fecha_pago' => date("Y-m-d"),
                'Metodo_pago' => $request->Metodo_pago,
                'c_FormaPago' => $request->c_FormaPago,
                'Concepto_pago_renta' => $request->Concepto_pago_renta,
                'Estatus_pago' => "Pendiente",
            ]);
            
            if ($insert) {
                $idPago = DB::getPdo()->lastInsertId();
                if (!empty($request->fotoComprobante)) {
                    $base64Img = $request->fotoComprobante;
                    $base_to_php = explode(',', $base64Img);
                    $extension = explode('/', mime_content_type($base64Img))[1];
                    $nombreImagen = $idPago . '_' . rand() . '.' . $extension;
                    $data2 = base64_decode($base_to_php[1]);
                    $filepath = 'C:/xampp/htdocs/alohate/public/uploads/pagos/' . $nombreImagen;
                    $guardarImagen = file_put_contents($filepath, $data2);
    
                    if ($guardarImagen !== false) {
                        $affected = DB::table('pago_renta')
                            ->where('Id_pago_renta', $idPago)
                        ->update(['Foto_comprobante_pago' => $nombreImagen]);
                    }
                }

                
                UsuariosController::historial_log(Cookie::get('Id_colaborador'),
                        "Se registro un pago por la cantidad de ".$request->Monto_pago. " para el cobro ".$id);
                return redirect()->route('pagos.index')->with('message', 'Pago registrado');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idCobro)
    {
        $cobros = DB::table('cobro_renta AS cr')
            ->selectRaw('cr.Id_cobro_renta,cr.Id_reservacion, cr.Cobro_persona_extra, cr.Periodo_total, cr.Estatus_cobro,
                cr.Tiempo_rebasado, cr.Deposito_garantia, cr.Monto_total, cr.Saldo, res.Start_date, res.End_date,
                res.Monto_pagado_anticipo, res.Title,lr.Id_habitacion, lr.Id_locacion, lr.Id_local, lr.Id_departamento, eo.Nombre_estado
                , ("") AS lugarEspecifico, ("") AS tipoLocacion, eso.Nombre_estado AS Estado')
            ->leftJoin('reservacion AS res', 'res.Id_reservacion', '=', 'cr.Id_reservacion')
            ->leftJoin('lugares_reservados AS lr', 'cr.Id_lugares_reservados', '=', 'lr.Id_lugares_reservados')
            ->leftJoin('estado_ocupacion AS eo', 'eo.Id_estado_ocupacion', '=', 'lr.Id_estado_ocupacion')
            ->leftJoin('estado_ocupacion AS eso', 'eso.Id_estado_ocupacion', '=', 'cr.Estatus_cobro')
            ->where('cr.Id_cobro_renta', $idCobro)
            ->groupByRaw('cr.Id_cobro_renta')
        ->get();

        $tipos = array(
            "Id_habitacion",
            "Id_departamento",
            "Id_local",
            "Id_locacion"
        );

        foreach ($cobros as $key => $reporte) {
            $reporte = json_decode(json_encode($reporte), true);
            $id = null;
            $lugar = array();
            $tipoLocacion = '';
            for ($i = 0; $i < 4; $i++) {
                if (!empty($reporte[$tipos[$i]])) {
                    if ($tipos[$i] == 'Id_locacion') {
                        $tipoLocacion = "Entera";
                    } elseif ($tipos[$i] == 'Id_local') {
                        $tipoLocacion = "Local";
                    } elseif ($tipos[$i] == 'Id_departamento') {
                        $tipoLocacion = "Departamento";
                    } elseif ($tipos[$i] == 'Id_habitacion') {
                        $tipoLocacion = "Habitación";
                    }

                    $cobros[$key]->Title = json_decode($cobros[$key]->Title);
                    $id = $reporte[$tipos[$i]];
                    break;
                }
            }
            $lugar = DB::select('call seleccionarLugar(?,?,?);', [$id, $tipoLocacion, $reporte["Id_locacion"]]);
            $lugar = json_decode(json_encode($lugar), true);

            $cobros[$key]->lugarEspecifico = $lugar;
            $cobros[$key]->tipoLocacion = $tipoLocacion;
        }

        $pagos = DB::table('pago_renta')
        ->where('Id_cobro_renta','=',$idCobro)
        ->get();

        return view('Pagos.show', ['pagos' => $pagos,'cobros' => $cobros, 'idCobro' => $idCobro]);
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

    public function confirmarPago(string $idCobro)
    {
        $cobros = DB::table('cobro_renta AS cr')
            ->selectRaw('cr.Id_cobro_renta,cr.Id_reservacion, cr.Cobro_persona_extra, cr.Periodo_total, cr.Estatus_cobro,
                cr.Tiempo_rebasado, cr.Deposito_garantia, cr.Monto_total, cr.Saldo, res.Start_date, res.End_date,
                res.Monto_pagado_anticipo, res.Title,lr.Id_habitacion, lr.Id_locacion, lr.Id_local, lr.Id_departamento, eo.Nombre_estado
                , ("") AS lugarEspecifico, ("") AS tipoLocacion, eso.Nombre_estado AS Estado')
            ->leftJoin('reservacion AS res', 'res.Id_reservacion', '=', 'cr.Id_reservacion')
            ->leftJoin('lugares_reservados AS lr', 'cr.Id_lugares_reservados', '=', 'lr.Id_lugares_reservados')
            ->leftJoin('estado_ocupacion AS eo', 'eo.Id_estado_ocupacion', '=', 'lr.Id_estado_ocupacion')
            ->leftJoin('estado_ocupacion AS eso', 'eso.Id_estado_ocupacion', '=', 'cr.Estatus_cobro')
            ->where('cr.Id_cobro_renta', $idCobro)
            ->groupByRaw('cr.Id_cobro_renta')
        ->get();

        $tipos = array(
            "Id_habitacion",
            "Id_departamento",
            "Id_local",
            "Id_locacion"
        );

        foreach ($cobros as $key => $reporte) {
            $reporte = json_decode(json_encode($reporte), true);
            $id = null;
            $lugar = array();
            $tipoLocacion = '';
            for ($i = 0; $i < 4; $i++) {
                if (!empty($reporte[$tipos[$i]])) {
                    if ($tipos[$i] == 'Id_locacion') {
                        $tipoLocacion = "Entera";
                    } elseif ($tipos[$i] == 'Id_local') {
                        $tipoLocacion = "Local";
                    } elseif ($tipos[$i] == 'Id_departamento') {
                        $tipoLocacion = "Departamento";
                    } elseif ($tipos[$i] == 'Id_habitacion') {
                        $tipoLocacion = "Habitación";
                    }

                    $cobros[$key]->Title = json_decode($cobros[$key]->Title);
                    $id = $reporte[$tipos[$i]];
                    break;
                }
            }
            $lugar = DB::select('call seleccionarLugar(?,?,?);', [$id, $tipoLocacion, $reporte["Id_locacion"]]);
            $lugar = json_decode(json_encode($lugar), true);

            $cobros[$key]->lugarEspecifico = $lugar;
            $cobros[$key]->tipoLocacion = $tipoLocacion;
        }

        $pagos = DB::table('pago_renta')
        ->leftJoin('forma_pago AS fp', 'fp.c_FormaPago', '=', 'pago_renta.c_FormaPago')
        ->where('Id_cobro_renta','=',$idCobro)
        ->get();

        return view('Pagos.confirmarPago', ['pagos' => $pagos,'cobros' => $cobros, 'idCobro' => $idCobro]);
    }

    function updatepago(Request $request, int $idPago, int $idCobro, string $referencia) {
        $pago = DB::select('SELECT * FROM `pago_renta` WHERE Estatus_pago = "Pendiente" AND Id_pago_renta = ?',[$idPago]);
        $cobro = DB::select('SELECT * FROM `cobro_renta` WHERE Id_cobro_renta = ?',[$idCobro]);

        print_r($request->all());
        if (!empty($pago[0]->Metodo_pago) && !empty($request->id) && !empty($request->tipoLocacion)) {
            switch ($referencia) {
                case 'Confirmar':
                    $saldo = 0;
                    if (!empty($cobro[0]->Saldo)) {
                        $saldo = ((float)$cobro[0]->Saldo + (float)$pago[0]->Monto_pago);
                    } else {
                        $saldo = (float)$pago[0]->Monto_pago;
                    }

                    $updatePago = DB::table('pago_renta')
                        ->where('Id_pago_renta', $idPago)
                    ->update(['Estatus_pago' => "Confirmado"]);
    
                    //todo el codigo para saldo
                    if($updatePago){
                        $affected = DB::table('cobro_renta')
                            ->where('Id_cobro_renta', $idCobro)
                        ->update(['Saldo' => $saldo]);

                        UsuariosController::historial_log(Cookie::get('Id_colaborador'), 
                        "Confirmo un pago de ".$pago[0]->Monto_pago. " para el cobro ".$cobro[0]->Id_cobro_renta);

                        if ($saldo >= (float)$cobro[0]->Monto_total) {                            
                            $affected = DB::table('cobro_renta')
                            ->where('Id_cobro_renta', $idCobro)
                            ->update(['Estatus_cobro' => "8"]);
                            
                            if ($affected) {
                                //Pago completo cambia los estados
                                if ($request->tipoLocacion == "Entera") {
                                    $datosEntera = DB::table('locacion')
                                    ->where('Id_locacion',$request->id)
                                    ->get();
                                    $datosEntera = json_decode(json_encode($datosEntera));
    
                                    if (!empty($datosEntera[0]->Id_locacion)) {
                                        $updateLocacion = DB::table('locacion')
                                            ->where('Id_locacion', $datosEntera[0]->Id_locacion)
                                        ->update(['Id_estado_ocupacion' => 4]);

                                        if ($updateLocacion) {
                                            $updatelocal = DB::table('local')
                                            ->where('Id_locacion', $datosEntera[0]->Id_locacion)
                                            ->update(['Id_estado_ocupacion' => 4]);
                                            $updatedepartamento = DB::table('departamento')
                                            ->where('Id_locacion', $datosEntera[0]->Id_locacion)
                                            ->update(['Id_estado_ocupacion' => 4]);
                                            $updatehabitacion = DB::table('habitacion')
                                            ->where('Id_locacion', $datosEntera[0]->Id_locacion)
                                            ->update(['Id_estado_ocupacion' => 4]);

                                            return redirect()->route('pagos.index')->with('message', 'Se actualizo la locación entera y todos sus lugares a rentados');
                                        }else {
                                            $updatePago = DB::table('pago_renta')
                                            ->where('Id_pago_renta', $idPago)
                                            ->update(['Estatus_pago' => "Pendiente"]);

                                            $affected = DB::table('cobro_renta')
                                            ->where('Id_cobro_renta', $idCobro)
                                            ->update(['Saldo' => $cobro[0]->Saldo,'Estatus_cobro' => '7']);
                                            return redirect()->route('pagos.index')->with('error', 'No se pudo actualizar la locación a rentada');
                                        }
                                    }else {
                                        $updatePago = DB::table('pago_renta')
                                        ->where('Id_pago_renta', $idPago)
                                        ->update(['Estatus_pago' => "Pendiente"]);

                                        $affected = DB::table('cobro_renta')
                                        ->where('Id_cobro_renta', $idCobro)
                                        ->update(['Saldo' => $cobro[0]->Saldo,'Estatus_cobro' => '7']);
                                        return redirect()->route('pagos.index')->with('error', 'No se pudo actualizar la locación a rentada, pago aún disponible');
                                    }
                                }elseif ($request->tipoLocacion == "Local") {
                                    $datosLocal = DB::table('local')
                                    ->where('Id_local',$request->id)
                                    ->get();

                                    if (!empty($datosLocal[0]->Id_local)) {
                                        $updatelocal = DB::table('local')
                                        ->where('Id_local', $datosLocal[0]->Id_local)
                                        ->update(['Id_estado_ocupacion' => 4]);

                                        return redirect()->route('pagos.index')->with('message', 'Registro de local actualizado, pago aplicado');
                                    } else {
                                        $updatePago = DB::table('pago_renta')
                                        ->where('Id_pago_renta', $idPago)
                                        ->update(['Estatus_pago' => "Pendiente"]);

                                        $affected = DB::table('cobro_renta')
                                        ->where('Id_cobro_renta', $idCobro)
                                        ->update(['Saldo' => $cobro[0]->Saldo,'Estatus_cobro' => '7']);
                                        return redirect()->route('pagos.index')->with('error', 'No se pudo actualizar el local a rentado, pago aún disponible');
                                    }
                                }elseif ($request->tipoLocacion == "Departamento") {
                                    $datosDepa = DB::table('departamento')
                                    ->where('Id_departamento',$request->id)
                                    ->get();

                                    if (!empty($datosDepa[0]->Id_departamento)) {
                                        $updatelocal = DB::table('departamento')
                                        ->where('Id_departamento', $datosDepa[0]->Id_departamento)
                                        ->update(['Id_estado_ocupacion' => 4]);

                                        return redirect()->route('pagos.index')->with('message', 'Registro de local actualizado, pago aplicado');
                                    } else {
                                        $updatePago = DB::table('pago_renta')
                                        ->where('Id_pago_renta', $idPago)
                                        ->update(['Estatus_pago' => "Pendiente"]);

                                        $affected = DB::table('cobro_renta')
                                        ->where('Id_cobro_renta', $idCobro)
                                        ->update(['Saldo' => $cobro[0]->Saldo,'Estatus_cobro' => '7']);
                                        return redirect()->route('pagos.index')->with('error', 'No se pudo actualizar el local a rentado, pago aún disponible');
                                    }
                                }elseif ($request->tipoLocacion == "Habitación") {
                                    $datosHabitacion = DB::table('habitacion')
                                    ->where('Id_habitacion',$request->id)
                                    ->get();

                                    if (!empty($datosHabitacion[0]->Id_habitacion)) {
                                        $updatelocal = DB::table('habitacion')
                                        ->where('Id_habitacion', $datosHabitacion[0]->Id_habitacion)
                                        ->update(['Id_estado_ocupacion' => 4]);

                                        return redirect()->route('pagos.index')->with('message', 'Registro de local actualizado, pago aplicado');
                                    } else {
                                        $updatePago = DB::table('pago_renta')
                                        ->where('Id_pago_renta', $idPago)
                                        ->update(['Estatus_pago' => "Pendiente"]);

                                        $affected = DB::table('cobro_renta')
                                        ->where('Id_cobro_renta', $idCobro)
                                        ->update(['Saldo' => $cobro[0]->Saldo,'Estatus_cobro' => '7']);
                                        return redirect()->route('pagos.index')->with('error', 'No se pudo actualizar el local a rentado, pago aún disponible');
                                    }
                                }else {
                                    return redirect()->route('pagos.index')->with('error', 'El pago se tomó, pero no actualizo ningún lugar.');
                                }
                            }else{
                                //No se pudo actualizar la renta a pago completo
                                $updatePago = DB::table('pago_renta')
                                ->where('Id_pago_renta', $idPago)
                                ->update(['Estatus_pago' => "Pendiente"]);

                                $affected = DB::table('cobro_renta')
                                ->where('Id_cobro_renta', $idCobro)
                                ->update(['Saldo' => $cobro[0]->Saldo,'Estatus_cobro' => '7']);

                                return redirect()->back()->with('error', 'Registros no actualizados verifique el saldo de la renta');
                            }
                        }else{
                            return redirect()->back()->with('message', 'Pago confirmado');
                        }
                    }else{
                        return redirect()->back()->with('error', 'No se pudo insertar el registro');
                    }

                    break;
                case 'Cancelar':
                    $affected = DB::table('pago_renta')
                        ->where('Id_pago_renta', $idPago)
                    ->update(['Estatus_pago' => "Cancelado"]);

                    UsuariosController::historial_log(Cookie::get('Id_colaborador'), 
                        "Se cancelo un pago de ".$pago[0]->Monto_pago. " para el cobro ".$cobro[0]->Id_cobro_renta);
                    
                    //todo el codigo para saldo
                    break;
                default:
                break;
            }
        } else {
            return redirect()->back()->with('error', 'No se puede localizar el tipo de lugar');
        }
    }
}

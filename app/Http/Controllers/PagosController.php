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
        DB::statement("SET SQL_MODE=''");
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
        DB::statement("SET SQL_MODE=''");
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
                
                if (!empty(Cookie::get('puesto'))) {
                    UsuariosController::historial_log(Cookie::get('Id_colaborador'),
                            "Se registro un pago por la cantidad de ".$request->Monto_pago. " para el cobro ".$id);
                    return redirect()->route('pagos.index')->with('message', 'Pago registrado');
                } else {
                    return redirect()->back()->with('message','El pago se registró y tendrá un proceso de verificación.');
                }
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
                                        $updateDepartamento = DB::table('departamento')
                                        ->where('Id_departamento', $datosDepa[0]->Id_departamento)
                                        ->update(['Id_estado_ocupacion' => 4]);

                                        return redirect()->route('pagos.index')->with('message', 'Registro del departamento actualizado, pago aplicado');
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

                    if ($affected) {
                        UsuariosController::historial_log(Cookie::get('Id_colaborador'), 
                            "Se cancelo un pago de ".$pago[0]->Monto_pago. " para el cobro ".$cobro[0]->Id_cobro_renta);
                        return redirect()->back()->with('message', 'Pago cancelado');
                    }                    
                    //todo el codigo para saldo
                    break;
                default:
                break;
            }
        } else {
            return redirect()->back()->with('error', 'No se puede localizar el tipo de lugar');
        }
    }

    public function pagoClienteDirecto(Request $request, string $tipoLugar, string $Id_locacion, string $Id_lugar) {
        if (!empty($tipoLugar) && !empty($Id_locacion) && !empty($Id_lugar)) {
            $keyMP = MercadoPagoController::traerKey();
            $keyMP = $keyMP[0]->value;
            $tokenMP = MercadoPagoController::traerToken();
            $tokenMP = $tokenMP[0]->value;
            
            $totalapagar = 0;
            $cuentas = DB::table("cuentas_bancarias")
            ->leftJoin('bancos','bancos.Id_Banco','=','cuentas_bancarias.Id_Banco')
            ->where('tipo_cuenta','!=','Cancelada')
            ->get();
            $cobros = DB::table('cobro_renta AS cr')
            ->selectRaw('cr.preference_mp,cr.Id_cobro_renta,cr.Id_reservacion, cr.Cobro_persona_extra, cr.Periodo_total, cr.Estatus_cobro,
                cr.Tiempo_rebasado, cr.Deposito_garantia, cr.Monto_total, cr.Saldo, res.Start_date, res.End_date,
                res.Monto_pagado_anticipo, res.Title,lr.Id_habitacion, lr.Id_locacion, lr.Id_local, lr.Id_departamento, eo.Nombre_estado
                , ("") AS lugarEspecifico, ("") AS tipoLocacion, eso.Nombre_estado AS Estado,lr.Id_lugares_reservados')
            ->leftJoin('reservacion AS res', 'res.Id_reservacion', '=', 'cr.Id_reservacion')
            ->leftJoin('lugares_reservados AS lr', 'cr.Id_lugares_reservados', '=', 'lr.Id_lugares_reservados')
            ->leftJoin('estado_ocupacion AS eo', 'eo.Id_estado_ocupacion', '=', 'lr.Id_estado_ocupacion')
            ->leftJoin('estado_ocupacion AS eso', 'eso.Id_estado_ocupacion', '=', 'cr.Estatus_cobro')
            ->where(function ($query) use ($tipoLugar,$Id_lugar) {
                if (!empty($tipoLugar)) {
                    if ($tipoLugar == "entera" || $tipoLugar == "Entera") {
                        return $query->where('lr.Id_locacion', '=', "$Id_lugar");
                    }elseif ($tipoLugar == "local" || $tipoLugar == "Local") {
                        return $query->where('lr.Id_local', '=', "$Id_lugar");
                    }elseif ($tipoLugar == "departamento" || $tipoLugar == "Departamento") {
                        return $query->where('lr.Id_departamento', '=', "$Id_lugar");
                    }elseif ($tipoLugar == "habitación" || $tipoLugar == "habitacion" || $tipoLugar == "Habitación") {
                        return $query->where('lr.Id_habitacion', '=', "$Id_lugar");
                    }else{
                        return $query->whereRaw('cr.Id_cobro_renta IS NULL');
                    }
                }
            })
            ->where('cr.Estatus_cobro','=','7')
            ->groupByRaw('cr.Id_cobro_renta')
            ->get();

            $datosLugar = DB::select('call seleccionarLugar(?,?,?);', [$Id_lugar, $tipoLugar, $Id_locacion]);

            if (!empty($cobros[0]->Id_cobro_renta)) {
                $idcobro = $cobros[0]->Id_cobro_renta;
            }

            $tipos = array(
                "Id_habitacion",
                "Id_departamento",
                "Id_local",
                "Id_locacion"
            );
    
            foreach ($cobros as $key => $reporte) {
                $saldoP = 0;
                if (!empty($reporte->Saldo)) {
                    $saldoP = ((float)$reporte->Monto_total - (float)$reporte->Saldo);
                } else {
                    $saldoP = (float)$reporte->Monto_total;
                }
                $totalapagar += $saldoP;

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
                        $Id_lugar = $id;
                        break;
                    }
                }
                $lugar = DB::select('call seleccionarLugar(?,?,?);', [$id, $tipoLocacion, $reporte["Id_locacion"]]);
                $lugar = json_decode(json_encode($lugar), true);
    
                $cobros[$key]->lugarEspecifico = $lugar;
                $cobros[$key]->tipoLocacion = $tipoLocacion;
            }

            $formaPagos = DB::table('forma_pago')
            ->orderBy('c_FormaPago','ASC')
            ->get();

            if ($totalapagar <= 0) {
                return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP])->with('message', 'No tienes pagos pendientes.');
            } else {
                if (!empty($cobros[0]->preference_mp) && $cobros[0]->Estatus_cobro != "8") {
                    // CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/search?sort=date_approved&criteria=desc&range=date_created&begin_date=NOW-3MONTHS&end_date=NOW&external_reference=renta-'.$idcobro.'',
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/search?sort=date_approved&criteria=desc&range=date_created&begin_date=NOW-3MONTHS&end_date=NOW&external_reference=renta-'.$idcobro.'',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: Bearer '.$tokenMP
                        ),
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $respuesta = json_decode($response);
    
                    if (!empty($respuesta->results[0]->transaction_details->total_paid_amount)) {
                        $totalPago = 0;
                        $idPagoMercado = 0;
                        
                        foreach ($respuesta->results as $key => $value) {
                            if (!empty($value->transaction_details->total_paid_amount)) {
                                if ($value->status_detail == "accredited" && $value->live_mode == true) {
                                    $totalPago += $value->transaction_details->total_paid_amount;
                                    $idPagoMercado = $value->id;
                                    $updateIdpa = DB::table('cobro_renta')
                                        ->where('Id_cobro_renta', $idcobro)
                                    ->update(['id_pago_mp' => $value->id]);
                                }
                            }
                        }
    
                        if (!empty($totalPago)) {
                            $insertPago = DB::table('pago_renta')->insert([
                                'Id_cobro_renta' => $idcobro,
                                'Id_lugares_reservados' => $cobros[0]->Id_lugares_reservados,
                                'Monto_pago' => $totalPago,
                                'Fecha_pago' => date("Y-m-d"),
                                'Metodo_pago' => "PUE",
                                'c_FormaPago' => "3",
                                'Concepto_pago_renta' => "Mercado Pago ($idPagoMercado)",
                                'Estatus_pago' => "Pendiente",
                            ]);
                            
                            if ($insertPago) {
                                $idPago = DB::getPdo()->lastInsertId();
                                
                                $saldo = 0;
                                if (!empty($cobros[0]->Saldo)) {
                                    $saldo = ((float)$cobros[0]->Saldo + (float)$totalPago);
                                } else {
                                    $saldo = (float)$totalPago;
                                }
        
                                $updatePago = DB::table('pago_renta')
                                    ->where('Id_pago_renta', $idPago)
                                ->update(['Estatus_pago' => "Confirmado"]);
                
                                //todo el codigo para saldo
                                if($updatePago){
                                    $idCobro = $idcobro;
                                    $affected = DB::table('cobro_renta')
                                        ->where('Id_cobro_renta', $idCobro)
                                    ->update(['Saldo' => $saldo]);
        
                                    if ($saldo >= (float)$cobros[0]->Monto_total) {                            
                                        $affected = DB::table('cobro_renta')
                                        ->where('Id_cobro_renta', $idCobro)
                                        ->update(['Estatus_cobro' => "8"]);
                                        
                                        if ($affected) {
                                            //Pago completo cambia los estados
                                            if ($cobros[0]->tipoLocacion == "Entera") {
                                                $datosEntera = DB::table('locacion')
                                                ->where('Id_locacion',$Id_lugar)
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
                                                        
                                                        // echo "ref1";
                                                        // return redirect()->route('pagos.index')->with('message', 'Se actualizo la locación entera y todos sus lugares a rentados');
                                                    }else {
                                                        // $updatePago = DB::table('pago_renta')
                                                        // ->where('Id_pago_renta', $idPago)
                                                        // ->update(['Estatus_pago' => "Pendiente"]);
        
                                                        // $affected = DB::table('cobro_renta')
                                                        // ->where('Id_cobro_renta', $idCobro)
                                                        // ->update(['Saldo' => $cobros[0]->Saldo,'Estatus_cobro' => '7']);
                                                        // echo "ref2";
                                                        // return redirect()->route('pagos.index')->with('error', 'No se pudo actualizar la locación a rentada');
                                                    }
                                                }else {
                                                    // $updatePago = DB::table('pago_renta')
                                                    // ->where('Id_pago_renta', $idPago)
                                                    // ->update(['Estatus_pago' => "Pendiente"]);
        
                                                    // $affected = DB::table('cobro_renta')
                                                    // ->where('Id_cobro_renta', $idCobro)
                                                    // ->update(['Saldo' => $cobros[0]->Saldo,'Estatus_cobro' => '7']);
                                                    // echo "ref3";
                                                    // return redirect()->route('pagos.index')->with('error', 'No se pudo actualizar la locación a rentada, pago aún disponible');
                                                }
                                            }elseif ($cobros[0]->tipoLocacion == "Local") {
                                                $datosLocal = DB::table('local')
                                                ->where('Id_local',$Id_lugar)
                                                ->get();
        
                                                if (!empty($datosLocal[0]->Id_local)) {
                                                    $updatelocal = DB::table('local')
                                                    ->where('Id_local', $datosLocal[0]->Id_local)
                                                    ->update(['Id_estado_ocupacion' => 4]);
        
                                                    // echo "ref4";
                                                    // return redirect()->route('pagos.index')->with('message', 'Registro de local actualizado, pago aplicado');
                                                } else {
                                                    // $updatePago = DB::table('pago_renta')
                                                    // ->where('Id_pago_renta', $idPago)
                                                    // ->update(['Estatus_pago' => "Pendiente"]);
        
                                                    // $affected = DB::table('cobro_renta')
                                                    // ->where('Id_cobro_renta', $idCobro)
                                                    // ->update(['Saldo' => $cobros[0]->Saldo,'Estatus_cobro' => '7']);
    
                                                    // echo "ref5";
                                                    // return redirect()->route('pagos.index')->with('error', 'No se pudo actualizar el local a rentado, pago aún disponible');
                                                }
                                            }elseif ($cobros[0]->tipoLocacion == "Departamento") {
                                                $datosDepa = DB::table('departamento')
                                                ->where('Id_departamento',$Id_lugar)
                                                ->get();
        
                                                if (!empty($datosDepa[0]->Id_departamento)) {
                                                    $updateDepartamento = DB::table('departamento')
                                                    ->where('Id_departamento', $datosDepa[0]->Id_departamento)
                                                    ->update(['Id_estado_ocupacion' => 4]);
        
                                                    // echo "ref6";
                                                    // return redirect()->route('pagos.index')->with('message', 'Registro del departamento actualizado, pago aplicado');
                                                } else {
                                                    // $updatePago = DB::table('pago_renta')
                                                    // ->where('Id_pago_renta', $idPago)
                                                    // ->update(['Estatus_pago' => "Pendiente"]);
        
                                                    // $affected = DB::table('cobro_renta')
                                                    // ->where('Id_cobro_renta', $idCobro)
                                                    // ->update(['Saldo' => $cobros[0]->Saldo,'Estatus_cobro' => '7']);
    
                                                    // echo "ref7";
                                                    // return redirect()->route('pagos.index')->with('error', 'No se pudo actualizar el local a rentado, pago aún disponible');
                                                }
                                            }elseif ($cobros[0]->tipoLocacion == "Habitación") {
                                                $datosHabitacion = DB::table('habitacion')
                                                ->where('Id_habitacion',$Id_lugar)
                                                ->get();
        
                                                if (!empty($datosHabitacion[0]->Id_habitacion)) {
                                                    $updatelocal = DB::table('habitacion')
                                                    ->where('Id_habitacion', $datosHabitacion[0]->Id_habitacion)
                                                    ->update(['Id_estado_ocupacion' => 4]);
        
                                                    // echo "ref8";
                                                    // return redirect()->route('pagos.index')->with('message', 'Registro de local actualizado, pago aplicado');
                                                } else {
                                                    // $updatePago = DB::table('pago_renta')
                                                    // ->where('Id_pago_renta', $idPago)
                                                    // ->update(['Estatus_pago' => "Pendiente"]);
        
                                                    // $affected = DB::table('cobro_renta')
                                                    // ->where('Id_cobro_renta', $idCobro)
                                                    // ->update(['Saldo' => $cobros[0]->Saldo,'Estatus_cobro' => '7']);
    
                                                    // echo "ref9";
                                                    // return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP])->with('error', 'No se pudo actualizar el local a rentado, pago aún disponible');
                                                }
                                            }else {
                                                return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP])->with('error', 'El pago se tomó, pero no actualizo ningún lugar.');
                                            }
                                        }else{
                                            //No se pudo actualizar la renta a pago completo
                                            //   $updatePago = DB::table('pago_renta')
                                            // ->where('Id_pago_renta', $idPago)
                                            // ->update(['Estatus_pago' => "Pendiente"]);
        
                                            // $affected = DB::table('cobro_renta')
                                            // ->where('Id_cobro_renta', $idCobro)
                                            // ->update(['Saldo' => $cobros[0]->Saldo,'Estatus_cobro' => '7']);
        
                                            return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP])->with('error', 'Registros no actualizados verifique el saldo de la renta');
                                        }
                                    }else{
                                        return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP])->with('message', 'Pago confirmado');
                                    }
                                }else{
                                    return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP]);
                                }
                            }

                            return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP]);
                        }else{
                            return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP]);
                        }
                    }else{
                        return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP]);
                    }
                }else{
                    if (!empty($cobros[0]->preference_mp)) {
                        return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP])->with('message', 'Pago pendiente en MercadoPago');
                    } else {
                        return view('Pagos.pagoCliente',["cuentas" => $cuentas,"cobros" => $cobros, "formaPagos" => $formaPagos, "totalapagar" => $totalapagar, "datosLugar" => $datosLugar, "keyMP" => $keyMP]);
                    }
                }
            }
        } else {
            return view('Errores.error500');
        }
    }

    public function generarLinkPago(Request $request){
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
        
        return view('pagos.generarLinkPago', ['locaciones' => $locaciones]);
    }

    function verificarTelefono(Request $request) {
        if (!empty($request->telefono) && !empty($request->idCobro)) {
            $cobros = DB::table('cobro_renta AS cr')
                ->selectRaw('cr.Id_cobro_renta,cr.Id_reservacion, cr.Cobro_persona_extra, cr.Periodo_total, cr.Estatus_cobro,
                    cr.Tiempo_rebasado, cr.Deposito_garantia, cr.Monto_total, cr.Saldo, res.Start_date, res.End_date,
                    res.Monto_pagado_anticipo, res.Title,lr.Id_habitacion, lr.Id_locacion, lr.Id_local, lr.Id_departamento, eo.Nombre_estado
                    , ("") AS lugarEspecifico, ("") AS tipoLocacion, eso.Nombre_estado AS Estado')
                ->leftJoin('reservacion AS res', 'res.Id_reservacion', '=', 'cr.Id_reservacion')
                ->leftJoin('lugares_reservados AS lr', 'cr.Id_lugares_reservados', '=', 'lr.Id_lugares_reservados')
                ->leftJoin('estado_ocupacion AS eo', 'eo.Id_estado_ocupacion', '=', 'lr.Id_estado_ocupacion')
                ->leftJoin('estado_ocupacion AS eso', 'eso.Id_estado_ocupacion', '=', 'cr.Estatus_cobro')
                ->where('cr.Id_cobro_renta', $request->idCobro)
                ->groupByRaw('cr.Id_cobro_renta')
            ->get();

            foreach ($cobros as $key => $value) {
                $cobros[$key]->Title = json_decode($cobros[$key]->Title);
            }

            return $cobros;
        }
    }
}

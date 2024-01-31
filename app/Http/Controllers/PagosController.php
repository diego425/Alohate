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
        if (!empty(Cookie::get('puesto'))) {
            if (Cookie::get('puesto') == "ADMIN" || Cookie::get('puesto') == "OPERADOR" || Cookie::get('puesto') == "AUXILIAR") {
                $cobros = DB::table('cobro_renta AS cr')
                    ->selectRaw('cr.Id_cobro_renta,cr.Id_reservacion, cr.Cobro_persona_extra, cr.Periodo_total, cr.Estatus_cobro,
                cr.Tiempo_rebasado, cr.Deposito_garantia, cr.Monto_total, cr.Saldo, res.Start_date, res.End_date,
                res.Monto_pagado_anticipo, res.Title,lr.Id_habitacion, lr.Id_locacion, lr.Id_local, lr.Id_departamento, eo.Nombre_estado
                , ("") AS lugarEspecifico, ("") AS tipoLocacion, eso.Nombre_estado AS Estado')
                    ->leftJoin('reservacion AS res', 'res.Id_reservacion', '=', 'cr.Id_reservacion')
                    ->leftJoin('lugares_reservados AS lr', 'cr.Id_lugares_reservados', '=', 'lr.Id_lugares_reservados')
                    ->leftJoin('estado_ocupacion AS eo', 'eo.Id_estado_ocupacion', '=', 'lr.Id_estado_ocupacion')
                    ->leftJoin('estado_ocupacion AS eso', 'eso.Id_estado_ocupacion', '=', 'cr.Estatus_cobro')
                    ->groupByRaw('cr.Id_cobro_renta')
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

                    $cobros[$key]->lugarEspecifico = $lugar;
                    $cobros[$key]->tipoLocacion = $tipoLocacion;
                }

                session()->flashInput($request->input());
                return view('Pagos.index', ['cobros' => $cobros]);
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

            $cobros[$key]->lugarEspecifico = $lugar;
            $cobros[$key]->tipoLocacion = $tipoLocacion;
        }

        session()->flashInput($request->input());
        return view('Pagos.create', ['cobros' => $cobros]);
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

        return view('Pagos.confirmarPago', ['pagos' => $pagos,'cobros' => $cobros, 'idCobro' => $idCobro]);
    }

    function updatepago(Request $request, int $idPago, int $idCobro, string $referencia) {
        $pago = DB::select('SELECT * FROM `pago_renta` WHERE Estatus_pago = "Pendiente" AND Id_pago_renta = ?',[$idPago]);
        $cobro = DB::select('SELECT * FROM `cobro_renta` WHERE Id_cobro_renta = ?',[$idCobro]);

        // print_r($cobro);
        if (!empty($pago[0]->Metodo_pago)) {
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
                            return redirect()->route('pagos.index')->with('message', 'Renta pagada');
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
                    
                    //todo el codigo para saldo
                    // $affected = DB::table('cobro_renta')
                    //     ->where('Id_cobro_renta', $idCobro)
                    // ->update(['Saldo' => 0]);
    
                    break;
                default:            
                break;
            }
        } else {
        }
    }
}

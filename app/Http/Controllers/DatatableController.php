<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DatatableController extends Controller
{
    function cobros() {
        $cobros = DB::table('cobro_renta AS cr')
        ->selectRaw('cr.Id_cobro_renta, cr.Cobro_persona_extra, cr.Periodo_total, cr.Estatus_cobro, 
        cr.Tiempo_rebasado, cr.Deposito_garantia, cr.Monto_total, cr.Saldo, res.Start_date, res.End_date, 
        res.Monto_pagado_anticipo, res.Title')
        ->leftJoin('reservacion AS res', 'res.Id_reservacion','=', 'cr.Id_reservacion')
        ->get();
        
        return datatables()
        ->of($cobros)
        ->toJson();
    }
}

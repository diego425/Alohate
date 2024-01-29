<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DatatableController extends Controller
{
    function cobros() {
        $cobros = DB::table('cobro_renta')
        ->get();
        
        return datatables()
        ->of($cobros)
        ->toJson();
    }
}

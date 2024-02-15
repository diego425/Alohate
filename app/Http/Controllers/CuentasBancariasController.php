<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Bancos;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CuentasBancariasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $cuentas = DB::table("cuentas_bancarias")
            ->leftJoin('bancos', 'bancos.Id_Banco', '=', 'cuentas_bancarias.Id_Banco')
            ->paginate(5);

        $bancos = Bancos::all();
        return view('CuentasBancarias.create', ["bancos" => $bancos, "cuentas" => $cuentas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!empty(Cookie::get('puesto'))) {
            if (Cookie::get('puesto') == "ADMIN") {
                $request->validate([
                    'Id_Banco' => 'required',
                    'tipo_cuenta' => 'required',
                    'Nombre' => 'required',
                    'CLABE' => 'required|integer',
                    'cuenta' => 'required',
                    'tarjeta' => 'required',
                ]);
        
                $insert = DB::table('cuentas_bancarias')->insert(
                    $request->except([
                        '_token'
                    ])
                );
        
                if ($insert) {
                    return redirect()->route('cuentas.create')->with('message', 'Cuenta agregada');
                } else {
                    return redirect()->route('cuentas.create')->with('error', 'No se pudo agregar la cuenta');
                }
            } else {
                return view('Errores.error403');
            }
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
        $deleted = DB::table('cuentas_bancarias')
            ->where('id_cuenta', $id)
            ->update(['tipo_cuenta' => "Cancelada"]);

        if ($deleted) {
            return redirect()->route('cuentas.create')->with('message', 'Cuenta cancelada');
        } else {
            return redirect()->route('cuentas.create')->with('error', 'No se pudede cancelar la cuenta');
        }
    }
}

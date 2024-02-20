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

class CajasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cajas = DB::table('cajas AS cj')
        ->selectRaw('cj.*,col.Nombre, col.Apellido_pat, col.Apellido_mat')
        ->leftJoin('colaboradores AS col','col.Id_colaborador','=','cj.Id_colaborador')
        ->paginate(5);

        session()->flashInput($request->input());
        return view('Cajas.index', ['cajas' => $cajas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!empty(Cookie::get('puesto'))) {
            $colaboradores = DB::table('colaboradores')
            ->get();
            $locaciones = DB::table('locacion')
            ->get();
    
            return view('Cajas.create', ['colaboradores' => $colaboradores, 'locaciones' => $locaciones]);
        } else {
            return redirect()->route('login');
        }
    }
    
    public function createMovimiento(Request $request, $idCajas)
    {
        if (!empty(Cookie::get('puesto'))) {
            $caja = DB::table('cajas')
            ->where('idCajas','=',$idCajas)
            ->get();
            $path = "C:/xampp/htdocs/alohate/public/uploads/comprobantes_caja/";

            if (!empty($caja[0]->idCajas)) {
                $colaboradores = DB::table('colaboradores')
                ->where('Id_colaborador','=',$caja[0]->Id_colaborador)
                ->get();
                $locaciones = DB::table('locacion')
                ->where('Id_locacion','=',$caja[0]->Id_locacion)
                ->get();                
        
                return view('Cajas.createMovimiento', ['caja' => $caja,'colaboradores' => $colaboradores, 'locaciones' => $locaciones]);
            }else{
                return view('Errores.error403');
            }
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Id_colaborador' => 'required'
        ]);
        if (!empty(Cookie::get('puesto'))) {
            $insert = DB::table('cajas')->insert([
                'Id_locacion' => $request->Id_locacion,
                'Id_colaborador' => (int)$request->Id_colaborador
            ]);

            if ($insert) {
                $id = DB::getPdo()->lastInsertId();
                return redirect()->route('caja.createMovimiento',$id)->with('message', 'Ingrese un movimiento de saldo inicial');
            } else {
                return redirect()->back()->with('error','Registro incompleto.');
            }
        } else {
            return redirect()->route('login');
        }
    }
    
    public function storeMovimiento(Request $request)
    {   
        $request->validate([
            'idCajas' => 'required',
            'Operacion' => 'required',
            'Monto' => 'required',
            'TipoMovimiento' => 'required',
        ]);

        print_r(json_encode($request->all()));

        if (!empty(Cookie::get('puesto'))) {
            $caja = DB::table('cajas')
            ->where('idCajas','=',$request->idCajas)
            ->get();

            if (!empty($caja[0]->idCajas)) {
                if (!empty($caja[0]->Saldo)) {
                    $saldo = $caja[0]->Saldo;

                    if ($request->TipoMovimiento == "Ingreso") {
                        $saldo = ((float)$saldo+(float)$request->Monto);
                    }elseif($request->TipoMovimiento == "Egreso"){
                        $saldo = ((float)$saldo-(float)$request->Monto);
                    }
                }else{
                    $saldo = $request->Monto;
                }

                if ($saldo < 0) {
                    return redirect()->route('caja.index')->with('error', 'El saldo no puede ser negativo.');
                }else{
                    $insert = DB::table('movimientoscajas')->insert([
                        'idCajas' => $request->idCajas,
                        'Id_colaborador' => Cookie::get('Id_colaborador'),
                        'Operacion' => $request->Operacion,
                        'Serie' => $request->Serie,
                        'Folio' => $request->Folio,
                        'TipoMovimiento' => $request->TipoMovimiento,
                        'Comprobante' => $request->Comprobante,
                        'Observacion' => $request->Observacion,
                        'Monto' => $request->Monto,
                        'SaldoActual' => $saldo,
                        'dateInsert' => date("Y-m-d h:i:s")
                    ]);
        
                    if ($insert) {
                        $id = DB::getPdo()->lastInsertId();
                        $updateCaja = DB::table('cajas')
                        ->where('idCajas', $caja[0]->idCajas)
                        ->update(['Saldo' => $saldo]);

                        if (!empty($request->fotoComprobante)) {
                            $base64Img = $request->fotoComprobante;
                            $base_to_php = explode(',', $base64Img);
                            $extension = explode('/', mime_content_type($base64Img))[1];
                            $nombreImagen = $id . '_' . rand() . '.' . $extension;
                            $data2 = base64_decode($base_to_php[1]);
                            $filepath = 'C:/xampp/htdocs/alohate/public/uploads/comprobantes_caja/' . $nombreImagen;
                            $guardarImagen = file_put_contents($filepath, $data2);
            
                            if ($guardarImagen !== false) {
                                $affected = DB::table('movimientoscajas')
                                    ->where('idmovimientosCajas', $id)
                                ->update(['Comprobante' => $nombreImagen]);
                            }
                        }

                        return redirect()->route('caja.index')->with('message', 'Movimiento registrado');
    
                    } else {
                        $updateCaja = DB::table('cajas')
                        ->where('idCajas', $caja[0]->idCajas)
                        ->update(['Saldo' => $caja[0]->Saldo]);
                        return redirect()->back()->with('error','Registro incompleto.');
                    }
                }                
            }else{
                return redirect()->route('caja.index')->with('error', 'Caja no encontrada');
            }
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $idCajas)
    {
        $fecha = $request->fecha;
        $caja = DB::table('cajas')
        ->where('idCajas','=',$idCajas)
        ->get();
        $path = "/uploads/comprobantes_caja/";

        $colaboradores = DB::table('colaboradores')
        ->where('Id_colaborador','=',$caja[0]->Id_colaborador)
        ->get();

        $movimientos = DB::table('movimientoscajas AS mc')
        ->leftJoin('colaboradores AS cl', 'cl.Id_colaborador', '=', 'mc.Id_colaborador')
        ->where('mc.idCajas','=',$idCajas)
        ->where(function ($query) use ($fecha) {
            if (!empty($fecha)) {
                return $query->whereRaw("date(dateInsert) = '$fecha'");
            }else {
                return $query->whereRaw("date(dateInsert) =  '".date("Y-m-d")."'");
            }
        })
        ->get();

        session()->flashInput($request->input());
        return view('Cajas.show',['path' => $path, "caja" => $caja, "colaboradores" => $colaboradores, "movimientos" => $movimientos]);
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
}

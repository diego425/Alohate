<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Query\Builder;

use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Locacion;
use App\Models\Reservacion;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Cobro_renta;
use App\Models\Fiador;
use App\Models\Plantas_pisos;
use App\Models\Estado_ocupacion;
use App\Models\Servicio_bano;
use App\Models\Servicio_cama;
use App\Models\Habitacion;
use App\Models\Local;
use App\Models\Departamento;
use App\Models\Servicios_estancia;
use App\Models\Fotos_lugares;
use App\Models\Lugares_reservados;
use App\Models\Relacion_servicios;
use Illuminate\Http\Response;
use function Laravel\Prompts\alert;
use Exception;
use GuzzleHttp\Client;

class ClienteController extends Controller
{
public function ViewClientes(){

    $clientes = DB::table('cliente')
    ->select('Id_cliente','Id_colaborador','Nombre',
    'Apellido_paterno','Apellido_materno','Email', 
    'Numero_celular','Ciudad','Estado',
    'Pais','Ref1_nombre','Ref2_nombre',
    'Ref1_celular','Ref2_celular','Ref1_parentesco',
    'Ref2_parentesco','Motivo_visita', 'Lugar_motivo_visita', 
    'Foto_cliente', 'INE_frente', 'INE_reverso')
    ->paginate(4);


    return view('Clientes.clientes', compact('clientes'));
}
}

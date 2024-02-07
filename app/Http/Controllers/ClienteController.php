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
    ->select(
    'Id_cliente','Id_colaborador','Nombre',
    'Apellido_paterno','Apellido_materno','Email', 
    'Numero_celular','Ciudad','Estado',
    'Pais','Ref1_nombre','Ref2_nombre',
    'Ref1_celular','Ref2_celular','Ref1_parentesco',
    'Ref2_parentesco','Motivo_visita', 'Lugar_motivo_visita', 
    'Foto_cliente', 'INE_frente', 'INE_reverso', 'Estatus_cliente')
    ->paginate(4);

    return view('Clientes.clientes', compact('clientes'));
}


public function DetalleCliente($Id_cliente){

    $cliente = DB::table('cliente')
    ->select(
    'Id_cliente','Id_colaborador','Nombre',
    'Apellido_paterno','Apellido_materno','Email', 
    'Numero_celular','Ciudad','Estado',
    'Pais','Ref1_nombre','Ref2_nombre',
    'Ref1_celular','Ref2_celular','Ref1_parentesco',
    'Ref2_parentesco','Motivo_visita', 'Lugar_motivo_visita', 
    'Foto_cliente', 'INE_frente', 'INE_reverso', 'Estatus_cliente')
    ->where('Id_cliente', '=', $Id_cliente)
    ->get();
    
    return view('Clientes.detalles_cliente', compact('cliente'));

}

public function ViewDesactCliente($Id_cliente){

    $cliente = DB::table('cliente')
    ->select(
    'Id_cliente','Id_colaborador','Nombre',
    'Apellido_paterno','Apellido_materno','Email', 
    'Numero_celular','Ciudad','Estado',
    'Pais','Ref1_nombre','Ref2_nombre',
    'Ref1_celular','Ref2_celular','Ref1_parentesco',
    'Ref2_parentesco','Motivo_visita', 'Lugar_motivo_visita', 
    'Foto_cliente', 'INE_frente', 'INE_reverso', 'Estatus_cliente')
    ->where('Id_cliente', '=', $Id_cliente)
    ->get();
    
    return view('Clientes.desactivar_cliente', compact('cliente'));
}

public function DesactivarCliente($Id_cliente){
try{

    $verificarcliente = DB::table('lugares_reservados')
    ->select('Id_lugares_reservados', 'Id_reservacion','Id_habitacion',
    'Id_locacion','Id_local', 'Id_departamento','Id_cliente', 'Id_estado_ocupacion')
    ->where('Id_cliente', '=', $Id_cliente)
    ->get();
   
    if(count($verificarcliente) == 0){

        $affected = DB::table('cliente')
        ->where('Id_cliente', '=', $Id_cliente)
        ->update(['Estatus_cliente' => "Bloqueado"]);
    
        Alert::success('Exito', 'Se ha bloqueado al cliente con exito');
        return redirect()->back();

    }else{
     
        Alert::warning('Cuidado', 'Este cliente se encuentra hospedado. no puedes bloquearlo');
        return redirect()->back();

    }

}catch(Exception $ex){
    Alert::error('Error', 'No se pudo bloquear este cliente');
    return redirect()->back();
}
}


public function ActivarCliente($Id_cliente){
try{
    $affected = DB::table('cliente')
    ->where('Id_cliente', '=', $Id_cliente)
    ->update(['Estatus_cliente' => "Activado"]);

    Alert::success('Exito', 'Se ha activado al cliente con exito');
    return redirect()->back();
}catch(Exception $ex){
    Alert::error('Error', 'No se pudo activar este cliente');
    return redirect()->back();
}
}

public function ViewEditCliente($Id_cliente){

// $habitaciones = Habitacion::findOrFail($Id_habitacion);

    $cliente = DB::table('cliente')
    ->select(
    'Id_cliente','Id_colaborador','Nombre',
    'Apellido_paterno','Apellido_materno','Email', 
    'Numero_celular','Ciudad','Estado',
    'Pais','Ref1_nombre','Ref2_nombre',
    'Ref1_celular','Ref2_celular','Ref1_parentesco',
    'Ref2_parentesco','Motivo_visita', 'Lugar_motivo_visita', 
    'Foto_cliente', 'INE_frente', 'INE_reverso', 'Estatus_cliente')
    ->where('Id_cliente', '=', $Id_cliente)
    ->get();

    return view('Clientes.editar_cliente', compact('cliente'));

}

public function UpdateCliente(Request $request, Cliente $cliente){
try{
    //actualizacion de los datos del cliente
    $cliente->Nombre = $request->nombre_c;
    $cliente->Apellido_paterno = $request->apellido_pat;
    $cliente->Apellido_materno = $request->apellido_mat;
    $cliente->Numero_celular = $request->celular_c;
    $cliente->Email = $request->email_c;
    $cliente->Ciudad = $request->ciudad;
    $cliente->Estado = $request->estado;
    $cliente->Pais = $request->pais;
    $cliente->Ref1_nombre = $request->nombre_p_e1;
    $cliente->Ref2_nombre = $request->nombre_p_e2;
    $cliente->Ref1_celular = $request->numero_p_e1;
    $cliente->Ref2_celular = $request->numero_p_e2;
    $cliente->Ref1_parentesco = $request->parentesco1;
    $cliente->Ref2_parentesco = $request->parentesco2;
    $cliente->Motivo_visita = $request->motivo_v;
    $cliente->Lugar_motivo_visita = $request->lugar_v;
    $cliente->save();


//array que guarda la foto de la ine de frente del cliente
    $this->validate($request, array(
    'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
    ));
    $image = $request->file('img3');
  
    if($image != ''){
        $nombreImagen = 'INE'.'_'.$cliente->Apellido_paterno.'_'.$cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
        $base64Img = $request->nuevaImagen3;
        $base_to_php = explode(',',$base64Img);
        $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
        $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
        $guardarImagen = file_put_contents($filepath, $data);
  
        if ($guardarImagen !== false) {
            DB::table('cliente')
            ->where('Id_cliente', '=', $cliente->Id_cliente)
            ->update(['INE_frente' => $nombreImagen]);
    }}


    //array que guarda la foto de la ine de frente del cliente
    $this->validate($request, array(
    'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
    ));
    $image = $request->file('img4');
  
    if($image != ''){
        $nombreImagen = 'INE'.'_'.$cliente->Apellido_paterno.'_'.$cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
        $base64Img = $request->nuevaImagen4;
        $base_to_php = explode(',',$base64Img);
        $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
        $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
        $guardarImagen = file_put_contents($filepath, $data);
  
        if ($guardarImagen !== false) {
            DB::table('cliente')
            ->where('Id_cliente', '=', $cliente->Id_cliente)
            ->update(['INE_reverso' => $nombreImagen]);

    }}

    Alert::success('Exito', 'Se ha actualizado al cliente con exito');
    return redirect()->back();

}catch(Exception $ex){
    Alert::error('Error', 'No se pudo editar el cliente, verifica que todo este en orden');
    return redirect()->back();
}
}


public function ViewNuevoCliente(){
    return view('Clientes.agregar_cliente');
}

public function ClienteStore(Request $request){

try{

    $agregarcliente = new Cliente();
    $agregarcliente -> Nombre = $request->get('nombre_c');
    $agregarcliente -> Apellido_paterno = $request->get('apellido_pat');
    $agregarcliente -> Apellido_materno = $request->get('apellido_mat');
    $agregarcliente -> Email = $request->get('email_c');
    $agregarcliente -> Numero_celular = $request->get('celular_c');
    $agregarcliente -> Ciudad = $request->get('ciudad');
    $agregarcliente -> Estado = $request->get('estado');
    $agregarcliente -> Pais = $request->get('pais');
    $agregarcliente -> Ref1_nombre = $request->get('nombre_p_e1');
    $agregarcliente -> Ref2_nombre = $request->get('nombre_p_e2');
    $agregarcliente -> Ref1_celular = $request->get('numero_p_e1');
    $agregarcliente -> Ref2_celular = $request->get('numero_p_e2');
    $agregarcliente -> Ref1_parentesco = $request->get('parentesco1');
    $agregarcliente -> Ref2_parentesco = $request->get('parentesco2');
    $agregarcliente -> Motivo_visita = $request->get('motivo_v');
    $agregarcliente -> Lugar_motivo_visita = $request->get('lugar_v');
    $agregarcliente -> Estatus_cliente = "Activado";
    $agregarcliente->save();

    $idclient =DB::getPdo()->lastInsertId();

    $cliente = DB::table('cliente')
    ->select(
    'Id_cliente','Id_colaborador','Nombre',
    'Apellido_paterno','Apellido_materno','Email', 
    'Numero_celular','Ciudad','Estado',
    'Pais','Ref1_nombre','Ref2_nombre',
    'Ref1_celular','Ref2_celular','Ref1_parentesco',
    'Ref2_parentesco','Motivo_visita', 'Lugar_motivo_visita', 
    'Foto_cliente', 'INE_frente', 'INE_reverso', 'Estatus_cliente')
    ->where('Id_cliente', '=', $idclient)
    ->get();

    //array que guarda la foto de la ine de frente del cliente
    $this->validate($request, array(
        'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img3');
      
        if($image != ''){
            $nombreImagen = 'INE'.'_'.$cliente->Apellido_paterno.'_'.$cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
            $base64Img = $request->nuevaImagen3;
            $base_to_php = explode(',',$base64Img);
            $data = base64_decode($base_to_php[1]);
    //aviso         
    //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
            $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
            $guardarImagen = file_put_contents($filepath, $data);
      
            if ($guardarImagen !== false) {
                DB::table('cliente')
                ->where('Id_cliente', '=', $cliente->Id_cliente)
                ->update(['INE_frente' => $nombreImagen]);
        }}
    
    
        //array que guarda la foto de la ine de frente del cliente
        $this->validate($request, array(
        'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img4');
      
        if($image != ''){
            $nombreImagen = 'INE'.'_'.$cliente->Apellido_paterno.'_'.$cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
            $base64Img = $request->nuevaImagen4;
            $base_to_php = explode(',',$base64Img);
            $data = base64_decode($base_to_php[1]);
    //aviso         
    //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
            $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
            $guardarImagen = file_put_contents($filepath, $data);
      
            if ($guardarImagen !== false) {
                DB::table('cliente')
                ->where('Id_cliente', '=', $cliente->Id_cliente)
                ->update(['INE_reverso' => $nombreImagen]);
    
        }}

}catch(Exception $ex){
    Alert::error('Error', 'No se pudo agregar al cliente, verifica que todo este en orden');
    return redirect()->back();
}

}

}

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

class ReservacionRentasController extends Controller
{
//funcion que me hace una consulta a la base de datos y me trae los registros para mostrarlos en la vista de reservaciones y rentas    
public function ViewReservacionRentas(){

//variable que hace consulta a la tabla y me trae registros
    $reservarentas=DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
        'lugares_reservados.Id_local', 'lugares_reservados.Id_departamento', 'lugares_reservados.Id_cliente',
        'est.Nombre_estado',
        'reserva.Id_reservacion','reserva.Id_colaborador',
        'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
        'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
        'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
        'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
        'reserva.Nota_pago_anticipo',
        'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email',
        'cliente.Numero_celular',
        'hab.Nombre_hab','hab.Id_habitacion',
        'loc.Nombre_locacion', 'loc.Id_locacion',
        'depa.Nombre_depa', 'depa.Id_departamento')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "lugares_reservados.Id_locacion")
    ->leftJoin("departamento as depa", "depa.Id_departamento", "=", "lugares_reservados.Id_departamento")
    ->paginate(4);
    
    return view('Reservaciones y rentas.reservaciones_rentas', compact('reservarentas'));

}



















public function DetalleReservaHab($Id_reservacion, $Id_habitacion, $Id_lugares_reservados){
//consulta a la bd para traer los datos de los detalles de una reservacion 
        $detallereserva = DB::table('lugares_reservados')
        ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
            'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
            'est.Nombre_estado',
            'reserva.Id_reservacion','reserva.Id_colaborador',
            'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
            'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
            'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
            'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
            'reserva.Tipo_de_cobro','reserva.Nota_pago_anticipo',
            'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email',
            'cliente.Numero_celular',
            'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
            'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
            'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
            'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
            'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
            'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
            'loc.Nombre_locacion')
        ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
        ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
        ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
        ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
        ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
        ->where('reserva.Id_reservacion', '=', $Id_reservacion)
        ->where('hab.Id_habitacion', '=', $Id_habitacion)
        ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
        ->get();

//funcion para calcular dias entre 2 fechas
        $fecha1 =   $detallereserva[0]->Start_date;
        $fecha2 =   $detallereserva[0]->End_date;
//aqui saco los segundos de las fechas
        $segfecha1 = strtotime($fecha1);
        $segfecha2 = strtotime($fecha2);
//segundos de diferencia entre las 2 fechas
        $segtranscurridos = $segfecha2 - $segfecha1;
//minutos transcurridos entre las 2 fechas
        $mintranscurridos = $segtranscurridos/60;
//horas transcurridas entre las 2 fechas
        $horastranscurridas = $mintranscurridos/60;
//dias transcurridos entre las 2 fechas
        $diastranscurridos = $horastranscurridas/24;
//redondeando los dias para que esten completos
        $diasredondeados = floor($diastranscurridos);

    switch ($detallereserva[0]->Tipo_de_cobro) {
        case 'Noche':
//variable que trae los dias
            $diasredondeados;
//variable que me saca el costo de los dias de estancia
            $monto_por_dias = $diasredondeados * $detallereserva[0]->Precio_noche;
//variable que me trae el costo por personas extra ya no se cobraran las personas extras por noche
            //$monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_noche_h * $detallereserva[0]->Numero_personas_extras;
//variable que me hace la suma total            
            $suma_monto = $monto_por_dias  + $detallereserva[0]->Deposito_garantia_hab + $detallereserva[0]->Monto_uso_cochera;

            return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservahab', compact('detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto'));
            //return "son $diasredondeados noches";
            break;
        
        case 'Semana':

            $calculosemana = $diasredondeados/7;
            $redondeosema = floor($calculosemana);
//variable que me saca el costo de los dias de estancia
            $monto_por_dias = $redondeosema * $detallereserva[0]->Precio_semana;
//variable que me trae el costo por personas extra
            $monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_catorcena_h * $detallereserva[0]->Numero_personas_extras;
//variable que me hace la suma total y me resta el monto de anticipo       
            $suma_monto = $monto_por_dias + $monto_por_p_extras + $detallereserva[0]->Deposito_garantia_hab + $detallereserva[0]->Monto_uso_cochera - $detallereserva[0]->Cobro_anticipo_catorcena_h;

            return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservahab', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
            //return "son $redondeosema semanas";
            break;
         
        case 'Catorcena':

            $calculocatorcena = $diasredondeados/14;
            $redondeocatorcena = floor($calculocatorcena);
//variable que me saca el costo de los dias de estancia
            $monto_por_dias = $redondeocatorcena * $detallereserva[0]->Precio_catorcedias;
//variable que me trae el costo por personas extra
            $monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_catorcena_h * $detallereserva[0]->Numero_personas_extras;
//variable que me hace la suma total y me resta el monto de anticipo       
            $suma_monto = $monto_por_dias + $monto_por_p_extras + $detallereserva[0]->Deposito_garantia_hab + $detallereserva[0]->Monto_uso_cochera - $detallereserva[0]->Cobro_anticipo_catorcena_h;
            
            return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservahab', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
            //return "son $redondeocatorcena catorcenas";
            break;

        case 'Mes':

            $calculomes = $diasredondeados/28;
            $redondeomes = floor($calculomes);
//variable que me saca el costo de los dias de estancia
            $monto_por_dias = $redondeomes * $detallereserva[0]->Precio_mes;
//variable que me trae el costo por personas extra
            $monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_mes_h * $detallereserva[0]->Numero_personas_extras;
//variable que me hace la suma total y me resta el monto de anticipo       
            $suma_monto = $monto_por_dias + $monto_por_p_extras + $detallereserva[0]->Deposito_garantia_hab + $detallereserva[0]->Monto_uso_cochera - $detallereserva[0]->Cobro_anticipo_mes_h;
            
            return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservahab', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
            //return "es $redondeomes mes";
            break;
    }

}


//funcion que ayuda al autobuscador de los clientes en el form de reservar una hab
public function ShowClientesHab(Request $request){
    $data = trim($request->valor);
    $result = DB::table('cliente')
    ->where('Numero_celular','like','%'.$data.'%')
    ->limit(3)
    ->get();

    return response()->json([
        "estado" =>1,
        "result" => $result
    ]);

}

//funcion que muestra el formulario para añadir una reservacion con cliente nuevo en una hab
public function ViewReservaHabNC($Id_locacion, $Id_habitacion){

    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $habitacion = DB::table('habitacion')
    ->where('Id_habitacion', '=', $Id_habitacion)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.agregarreserva_nc_hab',compact('Id_habitacion', 'totalcocheras', 'habitacion','Id_locacion', 'result_resta'));
}

//funcion que guarda el registro de una reservacion para una hab para un cliente nuevo
public function StoreReservaHabNC(Request $request, $Id_locacion, $Id_habitacion){

try{

//consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
    $fecha_bd = DB::table('reservacion')
    ->select('hab.Id_habitacion', 'Start_date', 'End_date' )
    ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugar_res.Id_habitacion")
    ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
    ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->get();
//consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
    $personastotal = DB::table('habitacion')
    ->select('Id_habitacion', 'Capacidad_personas')
    ->where('Id_habitacion', '=', $Id_habitacion)
    ->get();
    
//con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
    if(count($fecha_bd) == 0){   
        if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){
            
        $cliente = new Cliente();
        $cliente->Numero_celular = $request->get('cel');
        $cliente->Nombre = $request->get('nombre');
        $cliente->Apellido_paterno = $request->get('a_paterno');
        $cliente->Apellido_materno = $request->get('a_materno');
        $cliente->Email = $request->get('email');
        $cliente->save();
        $lastcliente =DB::getPdo()->lastInsertId();
        
        $nombrecliente = DB::table('cliente')
        ->select('Nombre', 'Numero_celular')
        ->where('Id_cliente', '=', $lastcliente)
        ->get();
        
        $reservacion = new Reservacion();
        $reservacion->Title = $nombrecliente;
        $reservacion->Fecha_reservacion = date('y-m-d');
        $reservacion->Start_date = $request->get('f_entrada');
        $reservacion->End_date = $request->get('f_salida');
        $reservacion->Numero_personas_extras = $request->get('extras');
        $reservacion->Total_de_personas = $request->get('p_total');
        $reservacion->Monto_uso_cochera = $request->get('num_cochera');
        $reservacion->Espacios_cochera = $request->get('uso_cochera');
        $reservacion->Tipo_de_cobro = $request->get('tipo_renta');
        $reservacion->Monto_pagado_anticipo = $request->get('monto_anticipo');
        $reservacion->Metodo_pago_anticipo = $request->get('metodo_pago');
        $reservacion->Fecha_pago_anticipo = $request->get('fecha_pago');
        $reservacion->save();
        $lastreservacion =DB::getPdo()->lastInsertId();
        
        $lugar_reserva = new Lugares_reservados();
        $lugar_reserva-> Id_reservacion  = $lastreservacion;
        $lugar_reserva-> Id_habitacion = $Id_habitacion;
        $lugar_reserva-> Id_cliente = $lastcliente;
        $lugar_reserva-> Id_estado_ocupacion  = "6";
        $lugar_reserva->save();
        
        $affected = DB::table('habitacion')
        ->where('Id_habitacion', '=', $Id_habitacion)
        ->update(['Id_estado_ocupacion' => "6"]);

        $consulta_cocheras = DB::table('locacion')
        ->select('Uso_cocheras')
        ->where('Id_locacion', '=', $Id_locacion)
        ->get();

        $result_suma_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras + (int)$request->get('uso_cochera');

        $locacion_update = DB::table('locacion')
        ->where('Id_locacion', '=', $Id_locacion)
        ->update(['Uso_cocheras' => $result_suma_cocheras]);

//array que guarda la foto 2
      $this->validate($request, array(
      'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img2');

      if($image != ''){
         $nombreImagen = $nombrecliente[0]->Numero_celular.'_'.$request->get('fecha_pago').'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen2;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/comprobantes_pago_anticipo/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('reservacion')
            ->where('Id_reservacion', '=', $lastreservacion)
            ->update(['Foto_comprobante_anticipo' => $nombreImagen]);
      }}
      
        Alert::success('Exito', 'Se ha registrado la reservacion con exito');
        return redirect()->back();

        }else{
            Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
            return redirect()->back();
        }
    }else{
        Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
        return redirect()->back();
    }
}catch(Exception $ex){
        Alert::error('Error', 'La reservacion no se pudo registrar revisa que todo este en orden');
        return redirect()->back();
}
    
}
    
    

//funcion que muestra el formulario para añadir una reservacion con cliente existente en una hab
public function ViewReservaHabOC($Id_locacion, $Id_habitacion){

    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $habitacion = DB::table('habitacion')
    ->where('Id_habitacion', '=', $Id_habitacion)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.agregarreserva_oc_hab',compact('Id_locacion', 'Id_habitacion', 'totalcocheras', 'habitacion', 'result_resta'));

}
//funcion que guarda el registro para añadir una reservacion con cliente existente en una hab
public function StoreReservaHabOC(Request $request, $Id_locacion, $Id_habitacion){

try{
//consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
    $fecha_bd = DB::table('reservacion')
    ->select('hab.Id_habitacion', 'Start_date', 'End_date' )
    ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugar_res.Id_habitacion")
    ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
    ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->get();
//consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
    $personastotal = DB::table('habitacion')
    ->select('Id_habitacion', 'Capacidad_personas')
    ->where('Id_habitacion', '=', $Id_habitacion)
    ->get();

//con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
    if(count($fecha_bd) == 0){   
      if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){
        
        $nombrecliente = DB::table('cliente')
        ->select('Nombre', 'Numero_celular')
        ->where('Id_cliente', '=', $request->get('selector_cliente'))
        ->get();
        
        $reservacion = new Reservacion();
        $reservacion->Title = $nombrecliente;
        $reservacion->Fecha_reservacion = date('y-m-d');
        $reservacion->Start_date = $request->get('f_entrada');
        $reservacion->End_date = $request->get('f_salida');
        $reservacion->Total_de_personas = $request->get('p_total');
        $reservacion->Numero_personas_extras = $request->get('extras');
        $reservacion->Monto_uso_cochera = $request->get('num_cochera');
        $reservacion->Espacios_cochera = $request->get('uso_cochera');
        $reservacion->Tipo_de_cobro = $request->get('tipo_renta');
        $reservacion->Monto_pagado_anticipo = $request->get('monto_anticipo');
        $reservacion->Metodo_pago_anticipo = $request->get('metodo_pago');
        $reservacion->Fecha_pago_anticipo = $request->get('fecha_pago');
        $reservacion->save();
        $lastreservacion =DB::getPdo()->lastInsertId();

        $lugar_reserva = new Lugares_reservados();
        $lugar_reserva-> Id_reservacion  = $lastreservacion;
        $lugar_reserva-> Id_habitacion = $Id_habitacion;
        $lugar_reserva-> Id_cliente = $request->get('selector_cliente');
        $lugar_reserva-> Id_estado_ocupacion  = "6";
        $lugar_reserva->save();

        $affected = DB::table('habitacion')
        ->where('Id_habitacion', '=', $Id_habitacion)
        ->update(['Id_estado_ocupacion' => "6"]);

        $consulta_cocheras = DB::table('locacion')
        ->select('Uso_cocheras')
        ->where('Id_locacion', '=', $Id_locacion)
        ->get();

        $result_suma_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras + (int)$request->get('uso_cochera');

        $locacion_update = DB::table('locacion')
        ->where('Id_locacion', '=', $Id_locacion)
        ->update(['Uso_cocheras' => $result_suma_cocheras]);

        //array que guarda la foto 2
      $this->validate($request, array(
        'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img2');
  
        if($image != ''){
           $nombreImagen = $nombrecliente[0]->Numero_celular.'_'.$request->get('fecha_pago').'_'.rand(). '.' . $image->getClientOriginalExtension();
           $base64Img = $request->nuevaImagen2;
           $base_to_php = explode(',',$base64Img);
           $data = base64_decode($base_to_php[1]);
  //aviso         
  //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
           $filepath = 'C:/xampp/htdocs/alohate/public/uploads/comprobantes_pago_anticipo/'.$nombreImagen;
           $guardarImagen = file_put_contents($filepath, $data);
  
           if ($guardarImagen !== false) {
              DB::table('reservacion')
              ->where('Id_reservacion', '=', $lastreservacion)
              ->update(['Foto_comprobante_anticipo' => $nombreImagen]);
        }}
        

        Alert::success('Exito', 'Se ha registrado la reservacion con exito');
        return redirect()->back();
        }else{
            Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
            return redirect()->back();
        }
    }else{
        Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
        return redirect()->back();
    }
}catch(Exception $ex){
    Alert::error('Error', 'La reservacion no se pudo registrar revisa que todo este en orden');
    return redirect()->back();
}

}

//funcion para la vista de editar una reservacion de una habitacion
public function EditarReservaHab($Id_reservacion, $Id_locacion, $Id_habitacion, $Id_lugares_reservados){

    $reservacion = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
        'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
        'est.Nombre_estado',
        'reserva.Id_reservacion', 'reserva.Id_colaborador',  
        'reserva.Start_date','reserva.End_date', 'reserva.Title',
        'reserva.Fecha_reservacion','reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 
        'reserva.Fecha_pago_anticipo','reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento',
        'reserva.Monto_uso_cochera', 'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera',
        'reserva.Monto_pagado_anticipo','reserva.Total_de_personas','reserva.Tipo_de_cobro','reserva.Nota_pago_anticipo',
        'cliente.Id_cliente','cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email',
        'cliente.Numero_celular','cliente.Pais',
        'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
        'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
        'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
        'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
        'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
        'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
        'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    
    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.editarreserva_oc_hab',compact('Id_reservacion', 'Id_lugares_reservados', 'Id_locacion', 'Id_habitacion', 'totalcocheras', 'result_resta', 'reservacion'));


}

public function UpdateReservaHab(Request $request, Reservacion $reservacion, Lugares_reservados $lugar_reservado, $Id_locacion, $Id_habitacion, ){


try{
//consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
    $fecha_bd = DB::table('reservacion')
    ->select('hab.Id_habitacion', 'Start_date', 'End_date' )
    ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugar_res.Id_habitacion")
    ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
    ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->get();
//consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
    $personastotal = DB::table('habitacion')
    ->select('Id_habitacion', 'Capacidad_personas')
    ->where('Id_habitacion', '=', $Id_habitacion)
    ->get();
//consulta para checar las cocheras que esta usando la reservacion antes de la actualizacion
    $consulta_old_cochera_r = DB::table('reservacion')
    ->select('Espacios_cochera', 'Id_reservacion')
    ->where('Id_reservacion', '=', $reservacion->Id_reservacion)
    ->get(); 

    

//con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
    if(count($fecha_bd) == 0){   
      if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){
        
        $nombrecliente = DB::table('cliente')
        ->select('Nombre', 'Numero_celular')
        ->where('Id_cliente', '=', $request->selector_cliente)
        ->get();
        
//reservacion
        $reservacion->Title = $nombrecliente;
        $reservacion->Start_date = $request->f_entrada;
        $reservacion->End_date = $request->f_salida;
        $reservacion->Total_de_personas = $request->p_total;
        $reservacion->Numero_personas_extras = $request->extras;
        $reservacion->Monto_uso_cochera = $request->num_cochera;
        $reservacion->Espacios_cochera = $request->uso_cochera;
        $reservacion->Tipo_de_cobro = $request->tipo_renta;
        $reservacion->save();

//lugar
        $lugar_reservado-> Id_lugares_reservados = $lugar_reservado->Id_lugares_reservados;
        $lugar_reservado-> Id_reservacion  = $reservacion->Id_reservacion;
        $lugar_reservado-> Id_habitacion = $Id_habitacion;
        $lugar_reservado-> Id_cliente = $request->selector_cliente;
        $lugar_reservado-> Id_estado_ocupacion  = "6";
        $lugar_reservado->save();

//consulta para la actualizacion de las cocheras de la casa        
        $consulta_cocheras = DB::table('locacion')
        ->select('Uso_cocheras')
        ->where('Id_locacion', '=', $Id_locacion)
        ->get();
//consulta para checar las cocheras que esta usando la reservacion despues de la actualizacion
        $consulta_new_cochera_r = DB::table('reservacion')
        ->select('Espacios_cochera', 'Id_reservacion')
        ->where('Id_reservacion', '=', $reservacion->Id_reservacion)
        ->get(); 
//aqui hago un if para ver si se esta aumentando o se estan quitando cocheras de la reservacion
//si el nuevo numero de cocheras es menor que el viejo. significa que le esta quitando y se le debe de restar 
        if($consulta_new_cochera_r[0]->Espacios_cochera < $consulta_old_cochera_r[0]->Espacios_cochera){
//si el numero obtenido es igual a cero le resta 1 
            if($request->get('uso_cochera') == "0"){

            $result_resta_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
            $locacion_update = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_resta_cocheras]);

            }else{


            $result_rest_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
            $locacion_up = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_rest_cocheras]);


            $consulta_cochera_n = DB::table('locacion')
            ->select('Uso_cocheras')
            ->where('Id_locacion', '=', $Id_locacion)
            ->get();

            $result_suma_cochera = (int)$consulta_cochera_n[0]->Uso_cocheras + (int)$request->get('uso_cochera');
            $locacion_update = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_suma_cochera]);

            }

        }elseif($consulta_new_cochera_r[0]->Espacios_cochera > $consulta_old_cochera_r[0]->Espacios_cochera){

            $result_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
            $locacion_update = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_cocheras]);


            $consulta_cocheras_n = DB::table('locacion')
            ->select('Uso_cocheras')
            ->where('Id_locacion', '=', $Id_locacion)
            ->get();

            $result_suma_cocheras = (int)$consulta_cocheras_n[0]->Uso_cocheras + (int)$request->get('uso_cochera');
            $locacion_update = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_suma_cocheras]);

           
        }


        Alert::success('Exito', 'Se ha actualizado la reservacion con exito');
        return redirect()->back();
        }else{
            Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
            return redirect()->back();
        }
    }else{
        Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
        return redirect()->back();
    }
}catch(Exception $ex){
    Alert::error('Error', 'La reservacion no se pudo actualizar revisa que todo este en orden');
    return redirect()->back();
}
}

public function ShowClientesEditHab(Request $request){

    $data = trim($request->valor);
    $result = DB::table('cliente')
    ->select('Id_cliente', 'Nombre','Apellido_paterno',
    'Apellido_materno','Email', 'Numero_celular','Ciudad',
    'Estado','Pais', 'Ref1_nombre','Ref2_nombre',
    'Ref1_celular','Ref2_celular','Ref1_parentesco',
    'Ref2_parentesco','Motivo_visita', 'Lugar_motivo_visita', 
    'Foto_cliente', 'INE_frente', 'INE_reverso')
    ->where('Numero_celular','like','%'.$data.'%')
    ->limit(3)
    ->get();

    return response()->json([
        "estado" =>1,
        "result" => $result
    ]);
}




//funcion para la vista de editar una reservacion de una habitacion
public function EditarReservaHabNC($Id_reservacion, $Id_lugares_reservados, $Id_locacion, $Id_habitacion ){

    $reservacion = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
        'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
        'est.Nombre_estado',
        'reserva.Id_reservacion', 'reserva.Id_colaborador',  
        'reserva.Start_date','reserva.End_date', 'reserva.Title',
        'reserva.Fecha_reservacion','reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 
        'reserva.Fecha_pago_anticipo','reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento',
        'reserva.Monto_uso_cochera', 'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera',
        'reserva.Monto_pagado_anticipo','reserva.Total_de_personas','reserva.Tipo_de_cobro',
        'cliente.Id_cliente','cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email',
        'cliente.Numero_celular','cliente.Pais',
        'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
        'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
        'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
        'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
        'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
        'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
        'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    
    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.editarreserva_nc_hab',compact('Id_reservacion', 'Id_lugares_reservados', 'Id_locacion', 'Id_habitacion', 'totalcocheras', 'result_resta', 'reservacion'));


}

public function UpdateReservaHabNC(Request $request, Reservacion $reservacion, Lugares_reservados $lugar_reservado, $Id_locacion, $Id_habitacion, ){


try{
//consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
    $fecha_bd = DB::table('reservacion')
    ->select('hab.Id_habitacion', 'Start_date', 'End_date' )
    ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugar_res.Id_habitacion")
    ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
    ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->get();
//consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
    $personastotal = DB::table('habitacion')
    ->select('Id_habitacion', 'Capacidad_personas')
    ->where('Id_habitacion', '=', $Id_habitacion)
    ->get();
//consulta para checar las cocheras que esta usando la reservacion antes de la actualizacion
    $consulta_old_cochera_r = DB::table('reservacion')
    ->select('Espacios_cochera', 'Id_reservacion')
    ->where('Id_reservacion', '=', $reservacion->Id_reservacion)
    ->get(); 

    

//con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
    if(count($fecha_bd) == 0){   
      if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){

        $cliente = new Cliente();
        $cliente->Numero_celular = $request->get('cel');
        $cliente->Nombre = $request->get('nombre');
        $cliente->Apellido_paterno = $request->get('a_paterno');
        $cliente->Apellido_materno = $request->get('a_materno');
        $cliente->Email = $request->get('email');
        $cliente->save();
        $lastcliente =DB::getPdo()->lastInsertId();

        $nombrecliente = DB::table('cliente')
        ->select('Nombre', 'Numero_celular')
        ->where('Id_cliente', '=', $lastcliente)
        ->get();

        
//reservacion
        $reservacion->Title = $nombrecliente;
        $reservacion->Start_date = $request->f_entrada;
        $reservacion->End_date = $request->f_salida;
        $reservacion->Total_de_personas = $request->p_total;
        $reservacion->Numero_personas_extras = $request->extras;
        $reservacion->Monto_uso_cochera = $request->num_cochera;
        $reservacion->Espacios_cochera = $request->uso_cochera;
        $reservacion->Tipo_de_cobro = $request->tipo_renta;
        $reservacion->save();

//lugar
        $lugar_reservado-> Id_lugares_reservados = $lugar_reservado->Id_lugares_reservados;
        $lugar_reservado-> Id_reservacion  = $reservacion->Id_reservacion;
        $lugar_reservado-> Id_habitacion = $Id_habitacion;
        $lugar_reservado-> Id_cliente = $lastcliente;
        $lugar_reservado-> Id_estado_ocupacion  = "6";
        $lugar_reservado->save();

//consulta para la actualizacion de las cocheras de la casa        
        $consulta_cocheras = DB::table('locacion')
        ->select('Uso_cocheras')
        ->where('Id_locacion', '=', $Id_locacion)
        ->get();
//consulta para checar las cocheras que esta usando la reservacion despues de la actualizacion
        $consulta_new_cochera_r = DB::table('reservacion')
        ->select('Espacios_cochera', 'Id_reservacion')
        ->where('Id_reservacion', '=', $reservacion->Id_reservacion)
        ->get(); 
//aqui hago un if para ver si se esta aumentando o se estan quitando cocheras de la reservacion
//si el nuevo numero de cocheras es menor que el viejo. significa que le esta quitando y se le debe de restar 
        if($consulta_new_cochera_r[0]->Espacios_cochera < $consulta_old_cochera_r[0]->Espacios_cochera){
//si el numero obtenido es igual a cero le resta 1 
            if($request->get('uso_cochera') == "0"){

            $result_resta_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
            $locacion_update = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_resta_cocheras]);

            }else{


            $result_rest_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
            $locacion_up = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_rest_cocheras]);


            $consulta_cochera_n = DB::table('locacion')
            ->select('Uso_cocheras')
            ->where('Id_locacion', '=', $Id_locacion)
            ->get();

            $result_suma_cochera = (int)$consulta_cochera_n[0]->Uso_cocheras + (int)$request->get('uso_cochera');
            $locacion_update = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_suma_cochera]);

            }

        }elseif($consulta_new_cochera_r[0]->Espacios_cochera > $consulta_old_cochera_r[0]->Espacios_cochera){

            $result_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
            $locacion_update = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_cocheras]);


            $consulta_cocheras_n = DB::table('locacion')
            ->select('Uso_cocheras')
            ->where('Id_locacion', '=', $Id_locacion)
            ->get();

            $result_suma_cocheras = (int)$consulta_cocheras_n[0]->Uso_cocheras + (int)$request->get('uso_cochera');
            $locacion_update = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_suma_cocheras]);

           
        }


        Alert::success('Exito', 'Se ha actualizado la reservacion con exito');
        return redirect()->back();
        }else{
            Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
            return redirect()->back();
        }
    }else{
        Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
        return redirect()->back();
    }
}catch(Exception $ex){
    Alert::error('Error', 'La reservacion no se pudo actualizar revisa que todo este en orden');
    return redirect()->back();
}
}






public function ViewDeleteReservaHab(){

 return view('Reservaciones y rentas.Eliminar_reservas_rentas.eliminarreservahab');


}




//funcion que me muestra el form para recopilar los datos de el cliente con el que se hizo la reservacion 
public function ViewCliente1Hab($Id_reservacion, $Id_habitacion, $Id_lugares_reservados){

    $renta = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
     'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
     'est.Nombre_estado',
     'reserva.Id_reservacion','reserva.Id_colaborador',
     'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
     'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
     'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
     'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
     'reserva.Tipo_de_cobro','reserva.Nota_pago_anticipo',
     'cliente.Id_cliente','cliente.Id_colaborador','cliente.Nombre',
     'cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email', 
     'cliente.Numero_celular','cliente.Ciudad','cliente.Estado',
     'cliente.Pais', 'cliente.Ref1_nombre','cliente.Ref2_nombre',
     'cliente.Ref1_celular','cliente.Ref2_celular','cliente.Ref1_parentesco',
     'cliente.Ref2_parentesco','cliente.Motivo_visita', 'cliente.Lugar_motivo_visita', 
     'cliente.Foto_cliente', 'cliente.INE_frente', 'cliente.INE_reverso',
     'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
     'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
     'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
     'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
     'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
     'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
     'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    return view('Reservaciones y rentas.Rentar.form_dato_c1_hab', compact('renta'));
}


//funcion que me guarda/actualiza el registro del cliente 
public function StoreRentarHabC(Request $request, Cliente $cliente ,$Id_reservacion, $Id_habitacion, $Id_lugares_reservados){

try{

    //consulta a la bd para que me traiga el cliente con quien se hizo la reserva
$cliente_reserva = DB::table('lugares_reservados')
->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
'est.Nombre_estado',
'cliente.Id_cliente','cliente.Id_colaborador','cliente.Nombre',
'cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email', 
'cliente.Numero_celular','cliente.Ciudad','cliente.Estado',
'cliente.Pais', 'cliente.Ref1_nombre','cliente.Ref2_nombre',
'cliente.Ref1_celular','cliente.Ref2_celular','cliente.Ref1_parentesco',
'cliente.Ref2_parentesco','cliente.Motivo_visita', 'cliente.Lugar_motivo_visita', 
'cliente.Foto_cliente', 'cliente.INE_frente', 'cliente.INE_reverso')
->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
->get();

//este if ayuda a saber si se esta llenando el form con los datos de de la persona que hizo la reserva o esta escogiendo a otro cliente que ya esta registrado en la bd
if($request->get('idcliente') == $cliente_reserva[0]->Id_cliente){
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

//array que guarda la foto del cliente
    $this->validate($request, array(
    'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
    ));
    $image = $request->file('img2');
  
    if($image != ''){
        $nombreImagen = $cliente->Nombre.'_'.$cliente->Apellido_paterno.'_'.$cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
        $base64Img = $request->nuevaImagen2;
        $base_to_php = explode(',',$base64Img);
        $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
        $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
        $guardarImagen = file_put_contents($filepath, $data);
  
        if ($guardarImagen !== false) {
            DB::table('cliente')
            ->where('Id_cliente', '=', $cliente->Id_cliente)
            ->update(['Foto_cliente' => $nombreImagen]);
    }}
    

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

    $personas_reserva = DB::table('reservacion')
    ->select('Id_reservacion','Registro_personas')
    ->where('Id_reservacion', '=', $Id_reservacion)
    ->get();
//despues de que se guarde el registro se hara aumentara 1 al campo de registro_personas y se hara una actualizacion a la tabla de la reserva
    $aumentador = $personas_reserva[0]->Registro_personas + 1;

    $affected = DB::table('reservacion')
    ->where('Id_reservacion', '=', $Id_reservacion)
    ->update(['Registro_personas' => $aumentador]);

    $num_personas_reserva = DB::table('reservacion')
    ->select('Id_reservacion','Registro_personas')
    ->where('Id_reservacion', '=', $Id_reservacion)
    ->get();

   $total_personas = DB::table('reservacion')
   ->select('Id_reservacion','Total_de_personas')
   ->where('Id_reservacion', '=', $Id_reservacion)
   ->get();

   $renta = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
     'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
     'est.Nombre_estado',
     'reserva.Id_reservacion','reserva.Id_colaborador',
     'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
     'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
     'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
     'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
     'reserva.Tipo_de_cobro','reserva.Nota_pago_anticipo',
     'cliente.Id_cliente','cliente.Id_colaborador','cliente.Nombre',
     'cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email', 
     'cliente.Numero_celular','cliente.Ciudad','cliente.Estado',
     'cliente.Pais', 'cliente.Ref1_nombre','cliente.Ref2_nombre',
     'cliente.Ref1_celular','cliente.Ref2_celular','cliente.Ref1_parentesco',
     'cliente.Ref2_parentesco','cliente.Motivo_visita', 'cliente.Lugar_motivo_visita', 
     'cliente.Foto_cliente', 'cliente.INE_frente', 'cliente.INE_reverso',
     'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
     'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
     'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
     'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
     'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
     'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
     'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    if($num_personas_reserva[0]->Registro_personas < $total_personas[0]->Total_de_personas){
            return view('Reservaciones y rentas.Rentar.intro_maspersonas', compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));
    }else{
        if($num_personas_reserva[0]->Registro_personas == $total_personas[0]->Total_de_personas){
            Alert::success('Exito', 'Haz terminado de registrar a los clientes que se hospedaran en este lugar, ahora termina el registro del lugar');
            return view('Reservaciones y rentas.Rentar.form_rentar_hab', compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));
        }
    }
    


}else{

//consulta para buscar el registro de la persona seleccionada    
    $cambio_cliente = Cliente::findOrFail($request->get('idcliente'));
//actualizacion de los datos del cliente
    $cambio_cliente->Nombre = $request->nombre_c;
    $cambio_cliente->Apellido_paterno = $request->apellido_pat;
    $cambio_cliente->Apellido_materno = $request->apellido_mat;
    $cambio_cliente->Numero_celular = $request->celular_c;
    $cambio_cliente->Email = $request->email_c;
    $cambio_cliente->Ciudad = $request->ciudad;
    $cambio_cliente->Estado = $request->estado;
    $cambio_cliente->Pais = $request->pais;
    $cambio_cliente->Ref1_nombre = $request->nombre_p_e1;
    $cambio_cliente->Ref2_nombre = $request->nombre_p_e2;
    $cambio_cliente->Ref1_celular = $request->numero_p_e1;
    $cambio_cliente->Ref2_celular = $request->numero_p_e2;
    $cambio_cliente->Ref1_parentesco = $request->parentesco1;
    $cambio_cliente->Ref2_parentesco = $request->parentesco2;
    $cambio_cliente->Motivo_visita = $request->motivo_v;
    $cambio_cliente->Lugar_motivo_visita = $request->lugar_v;
    $cambio_cliente->save();

//array que guarda la foto del cliente
    $this->validate($request, array(
    'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
    ));
    $image = $request->file('img2');
  
    if($image != ''){
        $nombreImagen = $cambio_cliente->Nombre.'_'.$cambio_cliente->Apellido_paterno.'_'.$cambio_cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
        $base64Img = $request->nuevaImagen2;
        $base_to_php = explode(',',$base64Img);
        $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
        $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
        $guardarImagen = file_put_contents($filepath, $data);
  
        if ($guardarImagen !== false) {
            DB::table('cliente')
            ->where('Id_cliente', '=', $cambio_cliente->Id_cliente)
            ->update(['Foto_cliente' => $nombreImagen]);
    }}
    

//array que guarda la foto de la ine de frente del cliente
    $this->validate($request, array(
    'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
    ));
    $image = $request->file('img3');
  
    if($image != ''){
        $nombreImagen = 'INE'.'_'.$cambio_cliente->Apellido_paterno.'_'.$cambio_cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
        $base64Img = $request->nuevaImagen3;
        $base_to_php = explode(',',$base64Img);
        $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
        $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
        $guardarImagen = file_put_contents($filepath, $data);
  
        if ($guardarImagen !== false) {
            DB::table('cliente')
            ->where('Id_cliente', '=', $cambio_cliente->Id_cliente)
            ->update(['INE_frente' => $nombreImagen]);
    }}


//array que guarda la foto de la ine de atras del cliente
    $this->validate($request, array(
    'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
    ));
    $image = $request->file('img4');
  
    if($image != ''){
        $nombreImagen = 'INE'.'_'.$cambio_cliente->Apellido_paterno.'_'.$cambio_cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
        $base64Img = $request->nuevaImagen4;
        $base_to_php = explode(',',$base64Img);
        $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
        $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
        $guardarImagen = file_put_contents($filepath, $data);
  
        if ($guardarImagen !== false) {
            DB::table('cliente')
            ->where('Id_cliente', '=', $cambio_cliente->Id_cliente)
            ->update(['INE_reverso' => $nombreImagen]);
    }}

//actualizacion del lugar de la reserva para el cliente
    $affectedes = DB::table('lugares_reservados')
    ->where('Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->update(['Id_cliente' => $cambio_cliente->Id_cliente]);

    $nombrecliente = DB::table('cliente')
    ->select('Nombre', 'Numero_celular')
    ->where('Id_cliente', '=', $cambio_cliente->Id_cliente)
    ->get();

//actualizacion de la reservacion para el cliente
    $affectedid = DB::table('reservacion')
    ->where('Id_reservacion', '=', $Id_reservacion)
    ->update(['Title' => $nombrecliente]);

//despues de que se guarde el registro se hara aumentara 1 al campo de registro_personas y se hara una actualizacion a la tabla de la reserva
    $personas_reserva = DB::table('reservacion')
    ->select('Id_reservacion','Registro_personas')
    ->where('Id_reservacion', '=', $Id_reservacion)
    ->get();
//despues de que se guarde el registro se hara aumentara 1 al campo de registro_personas y se hara una actualizacion a la tabla de la reserva
    $aumentador = $personas_reserva[0]->Registro_personas + 1;
//
    $affectedod = DB::table('reservacion')
    ->where('Id_reservacion', '=', $Id_reservacion)
    ->update(['Registro_personas' => $aumentador]);

    $num_personas_reserva = DB::table('reservacion')
    ->select('Id_reservacion','Registro_personas')
    ->where('Id_reservacion', '=', $Id_reservacion)
    ->get();

    $total_personas = DB::table('reservacion')
    ->select('Id_reservacion','Total_de_personas')
    ->where('Id_reservacion', '=', $Id_reservacion)
    ->get();

    $renta = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
     'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
     'est.Nombre_estado',
     'reserva.Id_reservacion','reserva.Id_colaborador',
     'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
     'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
     'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
     'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
     'reserva.Tipo_de_cobro','reserva.Nota_pago_anticipo',
     'cliente.Id_cliente','cliente.Id_colaborador','cliente.Nombre',
     'cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email', 
     'cliente.Numero_celular','cliente.Ciudad','cliente.Estado',
     'cliente.Pais', 'cliente.Ref1_nombre','cliente.Ref2_nombre',
     'cliente.Ref1_celular','cliente.Ref2_celular','cliente.Ref1_parentesco',
     'cliente.Ref2_parentesco','cliente.Motivo_visita', 'cliente.Lugar_motivo_visita', 
     'cliente.Foto_cliente', 'cliente.INE_frente', 'cliente.INE_reverso',
     'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
     'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
     'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
     'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
     'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
     'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
     'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    if($num_personas_reserva[0]->Registro_personas < $total_personas[0]->Total_de_personas){
            return view('Reservaciones y rentas.Rentar.intro_maspersonas', compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));
    }else{
        if($num_personas_reserva[0]->Registro_personas == $total_personas[0]->Total_de_personas){
            Alert::success('Exito', 'Haz terminado de registrar a los clientes que se hospedaran en este lugar, ahora termina el registro del lugar');
            return view('Reservaciones y rentas.Rentar.form_rentar_hab', compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));
        }
    }

}

}catch(Exception $ex){
    Alert::error('Error', 'los datos que ingresaste no son correctos, revisa que todo este en orden');
    return redirect()->back();
}
}


public function ViewIntroHabC2($Id_reservacion, $Id_habitacion, $Id_lugares_reservados){

    $renta = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
     'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
     'est.Nombre_estado',
     'reserva.Id_reservacion','reserva.Id_colaborador',
     'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
     'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
     'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
     'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
     'reserva.Tipo_de_cobro','reserva.Nota_pago_anticipo',
     'cliente.Id_cliente','cliente.Id_colaborador','cliente.Nombre',
     'cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email', 
     'cliente.Numero_celular','cliente.Ciudad','cliente.Estado',
     'cliente.Pais', 'cliente.Ref1_nombre','cliente.Ref2_nombre',
     'cliente.Ref1_celular','cliente.Ref2_celular','cliente.Ref1_parentesco',
     'cliente.Ref2_parentesco','cliente.Motivo_visita', 'cliente.Lugar_motivo_visita', 
     'cliente.Foto_cliente', 'cliente.INE_frente', 'cliente.INE_reverso',
     'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
     'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
     'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
     'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
     'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
     'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
     'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    return view('Reservaciones y rentas.Rentar.form_dato_c2_hab', compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion')); 
}

public function StoreRentarHabC2(Request $request, $Id_reservacion, $Id_habitacion, $Id_lugares_reservados){

try{

if($request->get('idcliente') == ""){
    
    
//datos del cliente
$agregarcamahab = new Cliente();
$agregarcamahab-> Nombre = $request->get('nombre_c');
$agregarcamahab-> Apellido_paterno = $request->get('apellido_pat');
$agregarcamahab-> Apellido_materno = $request->get('apellido_mat');
$agregarcamahab-> Numero_celular = $request->get('celular_c');
$agregarcamahab-> Email = $request->get('email_c');
$agregarcamahab-> Ciudad = $request->get('ciudad');
$agregarcamahab-> Estado = $request->get('estado');
$agregarcamahab-> Pais = $request->get('pais');
$agregarcamahab-> Ref1_nombre = $request->get('nombre_p_e1');
$agregarcamahab-> Ref2_nombre = $request->get('nombre_p_e2');
$agregarcamahab-> Ref1_celular = $request->get('numero_p_e1');
$agregarcamahab-> Ref2_celular = $request->get('numero_p_e2');
$agregarcamahab-> Ref1_parentesco = $request->get('parentesco1');
$agregarcamahab-> Ref2_parentesco = $request->get('parentesco2');
$agregarcamahab-> Motivo_visita = $request->get('motivo_v');
$agregarcamahab-> Lugar_motivo_visita = $request->get('lugar_v');
$agregarcamahab->save();

$idclient =DB::getPdo()->lastInsertId();

$nombreclient = DB::table('cliente')
->select( 'Id_cliente','Id_colaborador','Nombre',
'Apellido_paterno','Apellido_materno','Email', 
'Numero_celular','Ciudad','Estado',
'Pais', 'Ref1_nombre','Ref2_nombre',
'Ref1_celular','Ref2_celular','Ref1_parentesco',
'Ref2_parentesco','Motivo_visita', 'Lugar_motivo_visita', 
'Foto_cliente', 'INE_frente', 'INE_reverso')
->where('Id_cliente', '=', $idclient)
->get();


//array que guarda la foto del cliente
$this->validate($request, array(
'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
));
$image = $request->file('img2');

if($image != ''){
    $nombreImagen = $nombreclient[0]->Nombre.'_'.$nombreclient[0]->Apellido_paterno.'_'.$nombreclient[0]->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
    $base64Img = $request->nuevaImagen2;
    $base_to_php = explode(',',$base64Img);
    $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
    $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
    $guardarImagen = file_put_contents($filepath, $data);

    if ($guardarImagen !== false) {
        DB::table('cliente')
        ->where('Id_cliente', '=', $nombreclient[0]->Id_cliente)
        ->update(['Foto_cliente' => $nombreImagen]);
}}


//array que guarda la foto de la ine de frente del cliente
$this->validate($request, array(
'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
));
$image = $request->file('img3');

if($image != ''){
    $nombreImagen = 'INE'.'_'.$nombreclient[0]->Apellido_paterno.'_'.$nombreclient[0]->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
    $base64Img = $request->nuevaImagen3;
    $base_to_php = explode(',',$base64Img);
    $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
    $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
    $guardarImagen = file_put_contents($filepath, $data);

    if ($guardarImagen !== false) {
        DB::table('cliente')
        ->where('Id_cliente', '=', $nombreclient[0]->Id_cliente)
        ->update(['INE_frente' => $nombreImagen]);
}}


//array que guarda la foto de la ine de atras del cliente
$this->validate($request, array(
'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
));
$image = $request->file('img4');

if($image != ''){
    $nombreImagen = 'INE'.'_'.$nombreclient[0]->Apellido_paterno.'_'.$nombreclient[0]->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
    $base64Img = $request->nuevaImagen4;
    $base_to_php = explode(',',$base64Img);
    $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
    $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
    $guardarImagen = file_put_contents($filepath, $data);

    if ($guardarImagen !== false) {
        DB::table('cliente')
        ->where('Id_cliente', '=', $nombreclient[0]->Id_cliente)
        ->update(['INE_reverso' => $nombreImagen]);
}}

//consulta a la bd para sacar los datos del lugar de la reserva
$reserva = DB::table('lugares_reservados')
->select('Id_lugares_reservados','Id_reservacion','Id_habitacion',
'Id_cliente', 'Id_estado_ocupacion')
->where('Id_lugares_reservados', '=', $Id_lugares_reservados)
->get();

//guardo los datos dellugar e reserva con el nuevo cliente
$lugar_reserva = new Lugares_reservados();
$lugar_reserva-> Id_reservacion  = $Id_reservacion;
$lugar_reserva-> Id_habitacion = $Id_habitacion;
$lugar_reserva-> Id_cliente = $nombreclient[0]->Id_cliente;
$lugar_reserva-> Id_estado_ocupacion  = $reserva[0]->Id_estado_ocupacion;
$lugar_reserva->save();

//despues de que se guarde el registro se hara aumentara 1 al campo de registro_personas y se hara una actualizacion a la tabla de la reserva
$personas_reserva = DB::table('reservacion')
->select('Id_reservacion','Registro_personas')
->where('Id_reservacion', '=', $Id_reservacion)
->get();
//despues de que se guarde el registro se hara aumentara 1 al campo de registro_personas y se hara una actualizacion a la tabla de la reserva
$aumentador = $personas_reserva[0]->Registro_personas + 1;
//
$affectedod = DB::table('reservacion')
->where('Id_reservacion', '=', $Id_reservacion)
->update(['Registro_personas' => $aumentador]);

$num_personas_reserva = DB::table('reservacion')
->select('Id_reservacion','Registro_personas')
->where('Id_reservacion', '=', $Id_reservacion)
->get();

$total_personas = DB::table('reservacion')
->select('Id_reservacion','Total_de_personas')
->where('Id_reservacion', '=', $Id_reservacion)
->get();

$renta = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
     'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
     'est.Nombre_estado',
     'reserva.Id_reservacion','reserva.Id_colaborador',
     'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
     'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
     'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
     'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
     'reserva.Tipo_de_cobro','reserva.Nota_pago_anticipo',
     'cliente.Id_cliente','cliente.Id_colaborador','cliente.Nombre',
     'cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email', 
     'cliente.Numero_celular','cliente.Ciudad','cliente.Estado',
     'cliente.Pais', 'cliente.Ref1_nombre','cliente.Ref2_nombre',
     'cliente.Ref1_celular','cliente.Ref2_celular','cliente.Ref1_parentesco',
     'cliente.Ref2_parentesco','cliente.Motivo_visita', 'cliente.Lugar_motivo_visita', 
     'cliente.Foto_cliente', 'cliente.INE_frente', 'cliente.INE_reverso',
     'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
     'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
     'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
     'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
     'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
     'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
     'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();


if($num_personas_reserva[0]->Registro_personas < $total_personas[0]->Total_de_personas){
    Alert::success('Exito', 'Se ha registrado al cliente con exito pero aun quedan clientes por registrar para usar este lugar');
    return redirect()->back()->with(compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));
    //return view('Reservaciones y rentas.Rentar.form_dato_c2_hab', compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));
}else{
if($num_personas_reserva[0]->Registro_personas == $total_personas[0]->Total_de_personas){
        Alert::success('Exito', 'Haz terminado de registrar a los clientes que se hospedaran en este lugar, ahora termina el registro del lugar');
        return view('Reservaciones y rentas.Rentar.form_rentar_hab', compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));
}
}

}else{
    
    
//consulta para buscar el registro de la persona seleccionada    
$cambio_cliente = Cliente::findOrFail($request->get('idcliente'));
//actualizacion de los datos del cliente
    $cambio_cliente->Nombre = $request->nombre_c;
    $cambio_cliente->Apellido_paterno = $request->apellido_pat;
    $cambio_cliente->Apellido_materno = $request->apellido_mat;
    $cambio_cliente->Numero_celular = $request->celular_c;
    $cambio_cliente->Email = $request->email_c;
    $cambio_cliente->Ciudad = $request->ciudad;
    $cambio_cliente->Estado = $request->estado;
    $cambio_cliente->Pais = $request->pais;
    $cambio_cliente->Ref1_nombre = $request->nombre_p_e1;
    $cambio_cliente->Ref2_nombre = $request->nombre_p_e2;
    $cambio_cliente->Ref1_celular = $request->numero_p_e1;
    $cambio_cliente->Ref2_celular = $request->numero_p_e2;
    $cambio_cliente->Ref1_parentesco = $request->parentesco1;
    $cambio_cliente->Ref2_parentesco = $request->parentesco2;
    $cambio_cliente->Motivo_visita = $request->motivo_v;
    $cambio_cliente->Lugar_motivo_visita = $request->lugar_v;
    $cambio_cliente->save();

//array que guarda la foto del cliente
    $this->validate($request, array(
    'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
    ));
    $image = $request->file('img2');
  
    if($image != ''){
        $nombreImagen = $cambio_cliente->Nombre.'_'.$cambio_cliente->Apellido_paterno.'_'.$cambio_cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
        $base64Img = $request->nuevaImagen2;
        $base_to_php = explode(',',$base64Img);
        $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
        $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
        $guardarImagen = file_put_contents($filepath, $data);
  
        if ($guardarImagen !== false) {
            DB::table('cliente')
            ->where('Id_cliente', '=', $cambio_cliente->Id_cliente)
            ->update(['Foto_cliente' => $nombreImagen]);
    }}
    

//array que guarda la foto de la ine de frente del cliente
    $this->validate($request, array(
    'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
    ));
    $image = $request->file('img3');
  
    if($image != ''){
        $nombreImagen = 'INE'.'_'.$cambio_cliente->Apellido_paterno.'_'.$cambio_cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
        $base64Img = $request->nuevaImagen3;
        $base_to_php = explode(',',$base64Img);
        $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
        $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
        $guardarImagen = file_put_contents($filepath, $data);
  
        if ($guardarImagen !== false) {
            DB::table('cliente')
            ->where('Id_cliente', '=', $cambio_cliente->Id_cliente)
            ->update(['INE_frente' => $nombreImagen]);
    }}


//array que guarda la foto de la ine de atras del cliente
    $this->validate($request, array(
    'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
    ));
    $image = $request->file('img4');
  
    if($image != ''){
        $nombreImagen = 'INE'.'_'.$cambio_cliente->Apellido_paterno.'_'.$cambio_cliente->Apellido_materno.'_'.rand(). '.' . $image->getClientOriginalExtension();
        $base64Img = $request->nuevaImagen4;
        $base_to_php = explode(',',$base64Img);
        $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
        $filepath = 'C:/xampp/htdocs/alohate/public/uploads/clientes/'.$nombreImagen;
        $guardarImagen = file_put_contents($filepath, $data);
  
        if ($guardarImagen !== false) {
            DB::table('cliente')
            ->where('Id_cliente', '=', $cambio_cliente->Id_cliente)
            ->update(['INE_reverso' => $nombreImagen]);
    }}


//consulta a la bd para sacar los datos del lugar de la reserva
$reserva = DB::table('lugares_reservados')
->select('Id_lugares_reservados','Id_reservacion','Id_habitacion',
'Id_cliente', 'Id_estado_ocupacion')
->where('Id_lugares_reservados', '=', $Id_lugares_reservados)
->get();

//guardo los datos dellugar e reserva con el nuevo cliente
$lugar_reserva = new Lugares_reservados();
$lugar_reserva-> Id_reservacion  = $Id_reservacion;
$lugar_reserva-> Id_habitacion = $Id_habitacion;
$lugar_reserva-> Id_cliente = $cambio_cliente->Id_cliente;
$lugar_reserva-> Id_estado_ocupacion  = $reserva[0]->Id_estado_ocupacion;
$lugar_reserva->save();

//despues de que se guarde el registro se hara aumentara 1 al campo de registro_personas y se hara una actualizacion a la tabla de la reserva
$personas_reserva = DB::table('reservacion')
->select('Id_reservacion','Registro_personas')
->where('Id_reservacion', '=', $Id_reservacion)
->get();
//despues de que se guarde el registro se hara aumentara 1 al campo de registro_personas y se hara una actualizacion a la tabla de la reserva
$aumentador = $personas_reserva[0]->Registro_personas + 1;
//
$affectedod = DB::table('reservacion')
->where('Id_reservacion', '=', $Id_reservacion)
->update(['Registro_personas' => $aumentador]);

$num_personas_reserva = DB::table('reservacion')
->select('Id_reservacion','Registro_personas')
->where('Id_reservacion', '=', $Id_reservacion)
->get();

$total_personas = DB::table('reservacion')
->select('Id_reservacion','Total_de_personas')
->where('Id_reservacion', '=', $Id_reservacion)
->get();

$renta = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
     'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
     'est.Nombre_estado',
     'reserva.Id_reservacion','reserva.Id_colaborador',
     'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
     'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
     'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
     'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
     'reserva.Tipo_de_cobro','reserva.Nota_pago_anticipo',
     'cliente.Id_cliente','cliente.Id_colaborador','cliente.Nombre',
     'cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email', 
     'cliente.Numero_celular','cliente.Ciudad','cliente.Estado',
     'cliente.Pais', 'cliente.Ref1_nombre','cliente.Ref2_nombre',
     'cliente.Ref1_celular','cliente.Ref2_celular','cliente.Ref1_parentesco',
     'cliente.Ref2_parentesco','cliente.Motivo_visita', 'cliente.Lugar_motivo_visita', 
     'cliente.Foto_cliente', 'cliente.INE_frente', 'cliente.INE_reverso',
     'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
     'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
     'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
     'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
     'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
     'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
     'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

if($num_personas_reserva[0]->Registro_personas < $total_personas[0]->Total_de_personas){
    Alert::success('Exito', 'Se ha registrado al cliente con exito pero aun quedan clientes por registrar para usar este lugar');
    return redirect()->back()->with(compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));
    
    //return view('Reservaciones y rentas.Rentar.form_dato_c2_hab', compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));
}else{
if($num_personas_reserva[0]->Registro_personas == $total_personas[0]->Total_de_personas){
        Alert::success('Exito', 'Haz terminado de registrar a los clientes que se hospedaran en este lugar, ahora termina el registro del lugar');
        return view('Reservaciones y rentas.Rentar.form_rentar_hab', compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));
}}
}
}catch(Exception $ex){
     Alert::error('Error', 'los datos que ingresaste no son correctos, revisa que todo este en orden');
     return redirect()->back();
}
}



//ruta para el form de rentar una hab
public function ViewRentarHab($Id_reservacion, $Id_habitacion, $Id_lugares_reservados){

    $renta = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion','lugares_reservados.Id_locacion', 
     'lugares_reservados.Id_local', 'lugares_reservados.Id_cliente',
     'est.Nombre_estado',
     'reserva.Id_reservacion','reserva.Id_colaborador',
     'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
     'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
     'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
     'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
     'reserva.Tipo_de_cobro','reserva.Nota_pago_anticipo',
     'cliente.Id_cliente','cliente.Id_colaborador','cliente.Nombre',
     'cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email', 
     'cliente.Numero_celular','cliente.Ciudad','cliente.Estado',
     'cliente.Pais', 'cliente.Ref1_nombre','cliente.Ref2_nombre',
     'cliente.Ref1_celular','cliente.Ref2_celular','cliente.Ref1_parentesco',
     'cliente.Ref2_parentesco','cliente.Motivo_visita', 'cliente.Lugar_motivo_visita', 
     'cliente.Foto_cliente', 'cliente.INE_frente', 'cliente.INE_reverso',
     'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
     'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
     'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
     'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
     'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
     'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
     'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('hab.Id_habitacion', '=', $Id_habitacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    return view('Reservaciones y rentas.Rentar.form_rentar_hab', compact('Id_reservacion', 'Id_lugares_reservados', 'renta', 'Id_habitacion'));

}






























//funcion que ayuda al autobuscador de los clientes en el form de reservar desde los depas
public function ShowClientesDep(Request $request){
    $data = trim($request->valor);
    $result = DB::table('cliente')
    ->where('Numero_celular','like','%'.$data.'%')
    ->limit(3)
    ->get();

    return response()->json([
        "estado" =>1,
        "result" => $result
    ]);
    
}

//funcion que muestra el formulario para añadir una reservacion con cliente existente desde un depa
public function ViewReservaDepOC($Id_locacion, $Id_departamento){
    
    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $departamento = DB::table('departamento')
    ->where('Id_departamento', '=', $Id_departamento)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.agregarreserva_oc_depa',compact('Id_locacion','Id_departamento', 'totalcocheras', 'departamento', 'result_resta'));

}

//funcion que guarda el registro de una reservacion de un cliente existete en un depa
public function StoreReservaDepOC(Request $request, $Id_locacion ,$Id_departamento){

 try{
//consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
        $fecha_bd = DB::table('reservacion')
        ->select('depa.Id_departamento', 'Start_date', 'End_date' )
        ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
        ->leftJoin("departamento as depa", "depa.Id_departamento", "=", "lugar_res.Id_departamento")
        ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
        ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
        ->where('depa.Id_departamento', '=', $Id_departamento)
        ->get();

//consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
        $personastotal = DB::table('departamento')
        ->select('Id_departamento', 'Capacidad_personas')
        ->where('Id_departamento', '=', $Id_departamento)
        ->get();

//con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
        if(count($fecha_bd) == 0){
            if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){

            $nombrecliente = DB::table('cliente')
            ->select('Nombre', 'Numero_celular')
            ->where('Id_cliente', '=', $request->get('selector_cliente'))
            ->get();
            
            $reservacion = new Reservacion();
            $reservacion->Numero_personas_extras = $request->get('extras');
            $reservacion->Title = $nombrecliente;
            $reservacion->Fecha_reservacion = date('y-m-d');
            $reservacion->Start_date = $request->get('f_entrada');
            $reservacion->End_date = $request->get('f_salida');
            $reservacion->Total_de_personas = $request->get('p_total');
            $reservacion->Monto_uso_cochera = $request->get('num_cochera');
            $reservacion->Espacios_cochera = $request->get('uso_cochera');
            $reservacion->Tipo_de_cobro = $request->get('tipo_renta');
            $reservacion->Monto_pagado_anticipo = $request->get('monto_anticipo');
            $reservacion->Metodo_pago_anticipo = $request->get('metodo_pago');
            $reservacion->Fecha_pago_anticipo = $request->get('fecha_pago');
            $reservacion->save();
            $lastreservacion =DB::getPdo()->lastInsertId();
        
            $lugar_reserva = new Lugares_reservados();
            $lugar_reserva-> Id_reservacion  = $lastreservacion;
            $lugar_reserva-> Id_departamento = $Id_departamento;
            $lugar_reserva-> Id_cliente = $request->get('selector_cliente');
            $lugar_reserva-> Id_estado_ocupacion  = "6";
            $lugar_reserva->save();
        
            $affected = DB::table('departamento')
            ->where('Id_departamento', '=', $Id_departamento)
            ->update(['Id_estado_ocupacion' => "6"]);

            $consulta_cocheras = DB::table('locacion')
            ->select('Uso_cocheras')
            ->where('Id_locacion', '=', $Id_locacion)
            ->get();

            $result_suma_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras + (int)$request->get('uso_cochera');

            $locacion_update = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Uso_cocheras' => $result_suma_cocheras]);


        //array que guarda la foto 2
            $this->validate($request, array(
            'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
            ));
            $image = $request->file('img2');

            if($image != ''){
                $nombreImagen = $nombrecliente[0]->Numero_celular.'_'.$request->get('fecha_pago').'_'.rand(). '.' . $image->getClientOriginalExtension();
                $base64Img = $request->nuevaImagen2;
                $base_to_php = explode(',',$base64Img);
                $data = base64_decode($base_to_php[1]);
        //aviso         
        //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
                $filepath = 'C:/xampp/htdocs/alohate/public/uploads/comprobantes_pago_anticipo/'.$nombreImagen;
                $guardarImagen = file_put_contents($filepath, $data);

                if ($guardarImagen !== false) {
                    DB::table('reservacion')
                    ->where('Id_reservacion', '=', $lastreservacion)
                    ->update(['Foto_comprobante_anticipo' => $nombreImagen]);
            }}

            Alert::success('Exito', 'Se ha registrado la reservacion con exito');
            return redirect()->back();

            }else{
                Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
                return redirect()->back();
            }
        }else{
            Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
            return redirect()->back();
        }

}catch(Exception $ex){
        Alert::error('Error', 'La reservacion no se pudo registrar revisa que todo este en orden');
        return redirect()->back();
}
    
}
    

   
//funcion que muestra el formulario para añadir una reservacion con cliente nuevo en un depa
public function ViewReservaDepNC($Id_locacion, $Id_departamento){

    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $departamento = DB::table('departamento')
    ->where('Id_departamento', '=', $Id_departamento)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.agregarreserva_nc_depa',compact('Id_locacion','Id_departamento', 'totalcocheras', 'departamento', 'result_resta'));
}

//funcion que guarda el registro de una reservacion para un nuevo cliente en un depa
public function StoreReservaDepNC(Request $request, $Id_locacion, $Id_departamento){

try{
    //consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
    $fecha_bd = DB::table('reservacion')
    ->select('depa.Id_departamento', 'Start_date', 'End_date' )
    ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
    ->leftJoin("departamento as depa", "depa.Id_departamento", "=", "lugar_res.Id_departamento")
    ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
    ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
    ->where('depa.Id_departamento', '=', $Id_departamento)
    ->get();
    
//consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
    $personastotal = DB::table('departamento')
    ->select('Id_departamento', 'Capacidad_personas')
    ->where('Id_departamento', '=', $Id_departamento)
    ->get();

//con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
    if(count($fecha_bd) == 0){
        if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){
        
        $cliente = new Cliente();
        $cliente->Numero_celular = $request->get('cel');
        $cliente->Nombre = $request->get('nombre');
        $cliente->Apellido_paterno = $request->get('a_paterno');
        $cliente->Apellido_materno = $request->get('a_materno');
        $cliente->Email = $request->get('email');
        $cliente->save();
        $lastcliente =DB::getPdo()->lastInsertId();
    
        $nombrecliente = DB::table('cliente')
        ->select('Nombre', 'Numero_celular')
        ->where('Id_cliente', '=', $lastcliente)
        ->get();
        
        $reservacion = new Reservacion();
        $reservacion->Numero_personas_extras = $request->get('extras');
        $reservacion->Title = $nombrecliente;
        $reservacion->Fecha_reservacion = date('y-m-d');
        $reservacion->Start_date = $request->get('f_entrada');
        $reservacion->End_date= $request->get('f_salida');
        $reservacion->Total_de_personas = $request->get('p_total');
        $reservacion->Monto_uso_cochera = $request->get('num_cochera');
        $reservacion->Espacios_cochera = $request->get('uso_cochera');
        $reservacion->Tipo_de_cobro = $request->get('tipo_renta');
        $reservacion->Monto_pagado_anticipo = $request->get('monto_anticipo');
        $reservacion->Metodo_pago_anticipo = $request->get('metodo_pago');
        $reservacion->Fecha_pago_anticipo = $request->get('fecha_pago');
        $reservacion->save();
        $lastreservacion =DB::getPdo()->lastInsertId();
    
        $lugar_reserva = new Lugares_reservados();
        $lugar_reserva-> Id_reservacion  = $lastreservacion;
        $lugar_reserva-> Id_departamento = $Id_departamento;
        $lugar_reserva-> Id_cliente = $lastcliente;
        $lugar_reserva-> Id_estado_ocupacion  = "6";
        $lugar_reserva->save();
    
        $affected = DB::table('departamento')
        ->where('Id_departamento', '=', $Id_departamento)
        ->update(['Id_estado_ocupacion' => "6"]);

        $consulta_cocheras = DB::table('locacion')
        ->select('Uso_cocheras')
        ->where('Id_locacion', '=', $Id_locacion)
        ->get();

        $result_suma_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras + (int)$request->get('uso_cochera');

        $locacion_update = DB::table('locacion')
        ->where('Id_locacion', '=', $Id_locacion)
        ->update(['Uso_cocheras' => $result_suma_cocheras]);

        
//array que guarda la foto 2
      $this->validate($request, array(
        'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img2');
  
        if($image != ''){
           $nombreImagen = $nombrecliente[0]->Numero_celular.'_'.$request->get('fecha_pago').'_'.rand(). '.' . $image->getClientOriginalExtension();
           $base64Img = $request->nuevaImagen2;
           $base_to_php = explode(',',$base64Img);
           $data = base64_decode($base_to_php[1]);
  //aviso         
  //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
           $filepath = 'C:/xampp/htdocs/alohate/public/uploads/comprobantes_pago_anticipo/'.$nombreImagen;
           $guardarImagen = file_put_contents($filepath, $data);
  
           if ($guardarImagen !== false) {
              DB::table('reservacion')
              ->where('Id_reservacion', '=', $lastreservacion)
              ->update(['Foto_comprobante_anticipo' => $nombreImagen]);
        }}
        
        
        Alert::success('Exito', 'Se ha registrado la reservacion con exito');
        return redirect()->back();

    }else{
        Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
        return redirect()->back();
    }
    }else{
        Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
        return redirect()->back();
    }
}catch(Exception $ex){
        Alert::error('Error', 'La reservacion no se pudo registrar revisa que todo este en orden');
        return redirect()->back();
}
    
}




public function DetalleReservaDep($Id_reservacion, $Id_departamento, $Id_lugares_reservados){
    //consulta a la bd para traer los datos de los detalles de una reservacion 
            $detallereserva = DB::table('lugares_reservados')
            ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_locacion', 
                'lugares_reservados.Id_local', 'lugares_reservados.Id_departamento', 'lugares_reservados.Id_cliente',
                'est.Nombre_estado',
                'reserva.Id_reservacion','reserva.Id_colaborador',
                'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
                'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
                'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
                'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
                'reserva.Tipo_de_cobro',
                'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email',
                'cliente.Numero_celular',
                'dep.Id_departamento','dep.Id_locacion','dep.Id_estado_ocupacion', 
                'dep.Id_colaborador','dep.Nombre_depa','dep.Capacidad_personas', 'dep.Deposito_garantia_dep', 
                'dep.Precio_noche','dep.Precio_semana', 'dep.Precio_catorcedias', 'dep.Precio_mes', 
                'dep.Habitaciones_total', 'dep.Encargado','dep.Espacio_superficie','dep.Nota',
                'dep.Descripcion','dep.Cobro_p_ext_mes_d','dep.Cobro_p_ext_catorcena_d','dep.Cobro_p_ext_noche_d',
                'dep.Cobro_anticipo_mes_d','dep.Cobro_anticipo_catorcena_d','dep.Camas_juntas',
                'loc.Nombre_locacion')
            ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
            ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
            ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
            ->leftJoin("departamento as dep", "dep.Id_departamento", "=", "lugares_reservados.Id_departamento")
            ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "dep.Id_locacion")
            ->where('reserva.Id_reservacion', '=', $Id_reservacion)
            ->where('dep.Id_departamento', '=', $Id_departamento)
            ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
            ->get();
    
    //funcion para calcular dias entre 2 fechas
            $fecha1 =   $detallereserva[0]->Start_date;
            $fecha2 =   $detallereserva[0]->End_date;
    //aqui saco los segundos de las fechas
            $segfecha1 = strtotime($fecha1);
            $segfecha2 = strtotime($fecha2);
    //segundos de diferencia entre las 2 fechas
            $segtranscurridos = $segfecha2 - $segfecha1;
    //minutos transcurridos entre las 2 fechas
            $mintranscurridos = $segtranscurridos/60;
    //horas transcurridas entre las 2 fechas
            $horastranscurridas = $mintranscurridos/60;
    //dias transcurridos entre las 2 fechas
            $diastranscurridos = $horastranscurridas/24;
    //redondeando los dias para que esten completos
            $diasredondeados = floor($diastranscurridos);
    
        switch ($detallereserva[0]->Tipo_de_cobro) {
            case 'Noche':
    //variable que trae los dias
                $diasredondeados;
    //variable que me saca el costo de los dias de estancia
                $monto_por_dias = $diasredondeados * $detallereserva[0]->Precio_noche;
    //variable que me trae el costo por personas extra ya no se cobrara la persona extra por noche
                //$monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_noche_d * $detallereserva[0]->Numero_personas_extras;
    //variable que me hace la suma total            
                $suma_monto = $monto_por_dias + $detallereserva[0]->Deposito_garantia_dep + $detallereserva[0]->Monto_uso_cochera;
    
                return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservadep', compact('detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto'));
                //return "son $diasredondeados noches";
                break;
            
            case 'Semana':
    
                $calculosemana = $diasredondeados/7;
                $redondeosema = floor($calculosemana);
    //variable que me saca el costo de los dias de estancia
                $monto_por_dias = $redondeosema * $detallereserva[0]->Precio_semana;
    //variable que me trae el costo por personas extra
                $monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_catorcena_d * $detallereserva[0]->Numero_personas_extras;
    //variable que me hace la suma total y me resta el monto de anticipo       
                $suma_monto = $monto_por_dias + $monto_por_p_extras + $detallereserva[0]->Deposito_garantia_dep + $detallereserva[0]->Monto_uso_cochera - $detallereserva[0]->Cobro_anticipo_catorcena_d;
    
                return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservadep', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
                //return "son $redondeosema semanas";
                break;
             
            case 'Catorcena':
    
                $calculocatorcena = $diasredondeados/14;
                $redondeocatorcena = floor($calculocatorcena);
    //variable que me saca el costo de los dias de estancia
                $monto_por_dias = $redondeocatorcena * $detallereserva[0]->Precio_catorcedias;
    //variable que me trae el costo por personas extra
                $monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_catorcena_d * $detallereserva[0]->Numero_personas_extras;
    //variable que me hace la suma total y me resta el monto de anticipo       
                $suma_monto = $monto_por_dias + $monto_por_p_extras + $detallereserva[0]->Deposito_garantia_dep + $detallereserva[0]->Monto_uso_cochera - $detallereserva[0]->Cobro_anticipo_catorcena_d;
                
                return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservadep', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
                //return "son $redondeocatorcena catorcenas";
                break;
    
            case 'Mes':
    
                $calculomes = $diasredondeados/28;
                $redondeomes = floor($calculomes);
    //variable que me saca el costo de los dias de estancia
                $monto_por_dias = $redondeomes * $detallereserva[0]->Precio_mes;
    //variable que me trae el costo por personas extra
                $monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_mes_d * $detallereserva[0]->Numero_personas_extras;
    //variable que me hace la suma total y me resta el monto de anticipo       
                $suma_monto = $monto_por_dias + $monto_por_p_extras + $detallereserva[0]->Deposito_garantia_dep + $detallereserva[0]->Monto_uso_cochera - $detallereserva[0]->Cobro_anticipo_mes_d;
                
                return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservadep', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
                //return "es $redondeomes mes";
                break;
        }
    
    }





//funcion para la vista de editar una reservacion de un depa
public function EditarReservaDep($Id_reservacion, $Id_locacion, $Id_departamento, $Id_lugares_reservados){

    $reservacion = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_locacion', 
        'lugares_reservados.Id_local', 'lugares_reservados.Id_departamento', 'lugares_reservados.Id_cliente',
        'est.Nombre_estado',
        'reserva.Id_reservacion', 'reserva.Id_colaborador',  
        'reserva.Start_date','reserva.End_date', 'reserva.Title',
        'reserva.Fecha_reservacion','reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 
        'reserva.Fecha_pago_anticipo','reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento',
        'reserva.Monto_uso_cochera', 'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera',
        'reserva.Monto_pagado_anticipo','reserva.Total_de_personas','reserva.Tipo_de_cobro',
        'cliente.Id_cliente','cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email',
        'cliente.Numero_celular','cliente.Pais',
        'dep.Id_departamento','dep.Id_locacion','dep.Id_estado_ocupacion', 
        'dep.Id_colaborador','dep.Nombre_depa','dep.Capacidad_personas', 'dep.Deposito_garantia_dep', 
        'dep.Precio_noche','dep.Precio_semana', 'dep.Precio_catorcedias', 'dep.Precio_mes', 
        'dep.Habitaciones_total', 'dep.Encargado','dep.Espacio_superficie','dep.Nota',
        'dep.Descripcion','dep.Cobro_p_ext_mes_d','dep.Cobro_p_ext_catorcena_d','dep.Cobro_p_ext_noche_d',
        'dep.Cobro_anticipo_mes_d','dep.Cobro_anticipo_catorcena_d','dep.Camas_juntas',
        'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("departamento as dep", "dep.Id_departamento", "=", "lugares_reservados.Id_departamento")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "dep.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('dep.Id_departamento', '=', $Id_departamento)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    
    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.editarreserva_oc_depa',compact('Id_reservacion', 'Id_lugares_reservados', 'Id_locacion', 'Id_departamento', 'totalcocheras', 'result_resta', 'reservacion'));

}




public function UpdateReservaDep(Request $request, Reservacion $reservacion, Lugares_reservados $lugar_reservado, $Id_locacion, $Id_departamento ){


    try{
    //consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
        $fecha_bd = DB::table('reservacion')
        ->select('dep.Id_departamento', 'Start_date', 'End_date' )
        ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
        ->leftJoin("departamento as dep", "dep.Id_departamento", "=", "lugar_res.Id_departamento")
        ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
        ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
        ->where('dep.Id_departamento', '=', $Id_departamento)
        ->get();
    //consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
        $personastotal = DB::table('departamento')
        ->select('Id_departamento', 'Capacidad_personas')
        ->where('Id_departamento', '=', $Id_departamento)
        ->get();
    //consulta para checar las cocheras que esta usando la reservacion antes de la actualizacion
        $consulta_old_cochera_r = DB::table('reservacion')
        ->select('Espacios_cochera', 'Id_reservacion')
        ->where('Id_reservacion', '=', $reservacion->Id_reservacion)
        ->get(); 
    
        
    
    //con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
        if(count($fecha_bd) == 0){   
          if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){
            
            $nombrecliente = DB::table('cliente')
            ->select('Nombre', 'Numero_celular')
            ->where('Id_cliente', '=', $request->selector_cliente)
            ->get();
            
    //reservacion
            $reservacion->Title = $nombrecliente;
            $reservacion->Start_date = $request->f_entrada;
            $reservacion->End_date = $request->f_salida;
            $reservacion->Total_de_personas = $request->p_total;
            $reservacion->Numero_personas_extras = $request->extras;
            $reservacion->Monto_uso_cochera = $request->num_cochera;
            $reservacion->Espacios_cochera = $request->uso_cochera;
            $reservacion->Tipo_de_cobro = $request->tipo_renta;
            $reservacion->save();
    
    //lugar
            $lugar_reservado-> Id_lugares_reservados = $lugar_reservado->Id_lugares_reservados;
            $lugar_reservado-> Id_reservacion  = $reservacion->Id_reservacion;
            $lugar_reservado-> Id_departamento = $Id_departamento;
            $lugar_reservado-> Id_cliente = $request->selector_cliente;
            $lugar_reservado-> Id_estado_ocupacion  = "6";
            $lugar_reservado->save();
    
    //consulta para la actualizacion de las cocheras de la casa        
            $consulta_cocheras = DB::table('locacion')
            ->select('Uso_cocheras')
            ->where('Id_locacion', '=', $Id_locacion)
            ->get();
    //consulta para checar las cocheras que esta usando la reservacion despues de la actualizacion
            $consulta_new_cochera_r = DB::table('reservacion')
            ->select('Espacios_cochera', 'Id_reservacion')
            ->where('Id_reservacion', '=', $reservacion->Id_reservacion)
            ->get(); 
    //aqui hago un if para ver si se esta aumentando o se estan quitando cocheras de la reservacion
    //si el nuevo numero de cocheras es menor que el viejo. significa que le esta quitando y se le debe de restar 
            if($consulta_new_cochera_r[0]->Espacios_cochera < $consulta_old_cochera_r[0]->Espacios_cochera){
    //si el numero obtenido es igual a cero le resta 1 
                if($request->get('uso_cochera') == "0"){
    
                $result_resta_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
                $locacion_update = DB::table('locacion')
                ->where('Id_locacion', '=', $Id_locacion)
                ->update(['Uso_cocheras' => $result_resta_cocheras]);
    
                }else{
    
    
                $result_rest_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
                $locacion_up = DB::table('locacion')
                ->where('Id_locacion', '=', $Id_locacion)
                ->update(['Uso_cocheras' => $result_rest_cocheras]);
    
    
                $consulta_cochera_n = DB::table('locacion')
                ->select('Uso_cocheras')
                ->where('Id_locacion', '=', $Id_locacion)
                ->get();
    
                $result_suma_cochera = (int)$consulta_cochera_n[0]->Uso_cocheras + (int)$request->get('uso_cochera');
                $locacion_update = DB::table('locacion')
                ->where('Id_locacion', '=', $Id_locacion)
                ->update(['Uso_cocheras' => $result_suma_cochera]);
    
                }
    
            }elseif($consulta_new_cochera_r[0]->Espacios_cochera > $consulta_old_cochera_r[0]->Espacios_cochera){
    
                $result_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
                $locacion_update = DB::table('locacion')
                ->where('Id_locacion', '=', $Id_locacion)
                ->update(['Uso_cocheras' => $result_cocheras]);
    
    
                $consulta_cocheras_n = DB::table('locacion')
                ->select('Uso_cocheras')
                ->where('Id_locacion', '=', $Id_locacion)
                ->get();
    
                $result_suma_cocheras = (int)$consulta_cocheras_n[0]->Uso_cocheras + (int)$request->get('uso_cochera');
                $locacion_update = DB::table('locacion')
                ->where('Id_locacion', '=', $Id_locacion)
                ->update(['Uso_cocheras' => $result_suma_cocheras]);
    
               
            }
    
    
            Alert::success('Exito', 'Se ha actualizado la reservacion con exito');
            return redirect()->back();
            }else{
                Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
                return redirect()->back();
            }
        }else{
            Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
            return redirect()->back();
        }
    }catch(Exception $ex){
        Alert::error('Error', 'La reservacion no se pudo actualizar revisa que todo este en orden');
        return redirect()->back();
    }
    }
    
    public function ShowClientesEditDep(Request $request){
    
        $data = trim($request->valor);
        $result = DB::table('cliente')
        ->where('Numero_celular','like','%'.$data.'%')
        ->limit(3)
        ->get();
    
        return response()->json([
            "estado" =>1,
            "result" => $result
        ]);
    }









//funcion para la vista de editar una reservacion de un depa
public function EditarReservaDepNC($Id_reservacion,  $Id_lugares_reservados, $Id_locacion, $Id_departamento){

    $reservacion = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_locacion', 
        'lugares_reservados.Id_local', 'lugares_reservados.Id_departamento', 'lugares_reservados.Id_cliente',
        'est.Nombre_estado',
        'reserva.Id_reservacion', 'reserva.Id_colaborador',  
        'reserva.Start_date','reserva.End_date', 'reserva.Title',
        'reserva.Fecha_reservacion','reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 
        'reserva.Fecha_pago_anticipo','reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento',
        'reserva.Monto_uso_cochera', 'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera',
        'reserva.Monto_pagado_anticipo','reserva.Total_de_personas','reserva.Tipo_de_cobro',
        'cliente.Id_cliente','cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email',
        'cliente.Numero_celular','cliente.Pais',
        'dep.Id_departamento','dep.Id_locacion','dep.Id_estado_ocupacion', 
        'dep.Id_colaborador','dep.Nombre_depa','dep.Capacidad_personas', 'dep.Deposito_garantia_dep', 
        'dep.Precio_noche','dep.Precio_semana', 'dep.Precio_catorcedias', 'dep.Precio_mes', 
        'dep.Habitaciones_total', 'dep.Encargado','dep.Espacio_superficie','dep.Nota',
        'dep.Descripcion','dep.Cobro_p_ext_mes_d','dep.Cobro_p_ext_catorcena_d','dep.Cobro_p_ext_noche_d',
        'dep.Cobro_anticipo_mes_d','dep.Cobro_anticipo_catorcena_d','dep.Camas_juntas',
        'loc.Nombre_locacion')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("departamento as dep", "dep.Id_departamento", "=", "lugares_reservados.Id_departamento")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "dep.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('dep.Id_departamento', '=', $Id_departamento)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    
    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.editarreserva_nc_depa',compact('Id_reservacion', 'Id_lugares_reservados', 'Id_locacion', 'Id_departamento', 'totalcocheras', 'result_resta', 'reservacion'));

}




public function UpdateReservaDepNC(Request $request, Reservacion $reservacion, Lugares_reservados $lugar_reservado, $Id_locacion, $Id_departamento ){


    try{
    //consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
        $fecha_bd = DB::table('reservacion')
        ->select('dep.Id_departamento', 'Start_date', 'End_date' )
        ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
        ->leftJoin("departamento as dep", "dep.Id_departamento", "=", "lugar_res.Id_departamento")
        ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
        ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
        ->where('dep.Id_departamento', '=', $Id_departamento)
        ->get();
    //consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
        $personastotal = DB::table('departamento')
        ->select('Id_departamento', 'Capacidad_personas')
        ->where('Id_departamento', '=', $Id_departamento)
        ->get();
    //consulta para checar las cocheras que esta usando la reservacion antes de la actualizacion
        $consulta_old_cochera_r = DB::table('reservacion')
        ->select('Espacios_cochera', 'Id_reservacion')
        ->where('Id_reservacion', '=', $reservacion->Id_reservacion)
        ->get(); 
    
        
    
    //con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
        if(count($fecha_bd) == 0){   
          if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){
                      
            $cliente = new Cliente();
            $cliente->Numero_celular = $request->get('cel');
            $cliente->Nombre = $request->get('nombre');
            $cliente->Apellido_paterno = $request->get('a_paterno');
            $cliente->Apellido_materno = $request->get('a_materno');
            $cliente->Email = $request->get('email');
            $cliente->save();
            $lastcliente =DB::getPdo()->lastInsertId();

            $nombrecliente = DB::table('cliente')
            ->select('Nombre', 'Numero_celular')
            ->where('Id_cliente', '=', $lastcliente)
            ->get();
            
    //reservacion
            $reservacion->Title = $nombrecliente;
            $reservacion->Start_date = $request->f_entrada;
            $reservacion->End_date = $request->f_salida;
            $reservacion->Total_de_personas = $request->p_total;
            $reservacion->Numero_personas_extras = $request->extras;
            $reservacion->Monto_uso_cochera = $request->num_cochera;
            $reservacion->Espacios_cochera = $request->uso_cochera;
            $reservacion->Tipo_de_cobro = $request->tipo_renta;
            $reservacion->save();
    
    //lugar
            $lugar_reservado-> Id_lugares_reservados = $lugar_reservado->Id_lugares_reservados;
            $lugar_reservado-> Id_reservacion  = $reservacion->Id_reservacion;
            $lugar_reservado-> Id_departamento = $Id_departamento;
            $lugar_reservado-> Id_cliente = $lastcliente;
            $lugar_reservado-> Id_estado_ocupacion  = "6";
            $lugar_reservado->save();
    
    //consulta para la actualizacion de las cocheras de la casa        
            $consulta_cocheras = DB::table('locacion')
            ->select('Uso_cocheras')
            ->where('Id_locacion', '=', $Id_locacion)
            ->get();
    //consulta para checar las cocheras que esta usando la reservacion despues de la actualizacion
            $consulta_new_cochera_r = DB::table('reservacion')
            ->select('Espacios_cochera', 'Id_reservacion')
            ->where('Id_reservacion', '=', $reservacion->Id_reservacion)
            ->get(); 
    //aqui hago un if para ver si se esta aumentando o se estan quitando cocheras de la reservacion
    //si el nuevo numero de cocheras es menor que el viejo. significa que le esta quitando y se le debe de restar 
            if($consulta_new_cochera_r[0]->Espacios_cochera < $consulta_old_cochera_r[0]->Espacios_cochera){
    //si el numero obtenido es igual a cero le resta 1 
                if($request->get('uso_cochera') == "0"){
    
                $result_resta_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
                $locacion_update = DB::table('locacion')
                ->where('Id_locacion', '=', $Id_locacion)
                ->update(['Uso_cocheras' => $result_resta_cocheras]);
    
                }else{
    
    
                $result_rest_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
                $locacion_up = DB::table('locacion')
                ->where('Id_locacion', '=', $Id_locacion)
                ->update(['Uso_cocheras' => $result_rest_cocheras]);
    
    
                $consulta_cochera_n = DB::table('locacion')
                ->select('Uso_cocheras')
                ->where('Id_locacion', '=', $Id_locacion)
                ->get();
    
                $result_suma_cochera = (int)$consulta_cochera_n[0]->Uso_cocheras + (int)$request->get('uso_cochera');
                $locacion_update = DB::table('locacion')
                ->where('Id_locacion', '=', $Id_locacion)
                ->update(['Uso_cocheras' => $result_suma_cochera]);
    
                }
    
            }elseif($consulta_new_cochera_r[0]->Espacios_cochera > $consulta_old_cochera_r[0]->Espacios_cochera){
    
                $result_cocheras = (int)$consulta_cocheras[0]->Uso_cocheras - (int)$consulta_old_cochera_r[0]->Espacios_cochera;
                $locacion_update = DB::table('locacion')
                ->where('Id_locacion', '=', $Id_locacion)
                ->update(['Uso_cocheras' => $result_cocheras]);
    
    
                $consulta_cocheras_n = DB::table('locacion')
                ->select('Uso_cocheras')
                ->where('Id_locacion', '=', $Id_locacion)
                ->get();
    
                $result_suma_cocheras = (int)$consulta_cocheras_n[0]->Uso_cocheras + (int)$request->get('uso_cochera');
                $locacion_update = DB::table('locacion')
                ->where('Id_locacion', '=', $Id_locacion)
                ->update(['Uso_cocheras' => $result_suma_cocheras]);
    
               
            }
    
    
            Alert::success('Exito', 'Se ha actualizado la reservacion con exito');
            return redirect()->back();
            }else{
                Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
                return redirect()->back();
            }
        }else{
            Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
            return redirect()->back();
        }
    }catch(Exception $ex){
        Alert::error('Error', 'La reservacion no se pudo actualizar revisa que todo este en orden');
        return redirect()->back();
    }
    }
    

























//funcion que ayuda al autobuscador de los clientes en el form de reservar desde las casas
public function ShowClientesCasa(Request $request){
    $data = trim($request->valor);
    $result = DB::table('cliente')
    ->where('Numero_celular','like','%'.$data.'%')
    ->limit(3)
    ->get();

    return response()->json([
        "estado" =>1,
        "result" => $result
    ]);
    
}

//funcion que muestra el formulario para añadir una reservacion con cliente existente desde una casa
public function ViewReservaCasaOC($Id_locacion){
    
    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.agregarreserva_oc_casa',compact('Id_locacion', 'totalcocheras', 'result_resta'));

}

//funcion que guarda el registro de una reservacion de un cliente existete en una casa
public function StoreReservaCasaOC(Request $request, $Id_locacion){

try{
//consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
        $fecha_bd = DB::table('reservacion')
        ->select('locacion.Id_locacion', 'Start_date', 'End_date' )
        ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
        ->leftJoin("locacion", "locacion.Id_locacion", "=", "lugar_res.Id_locacion")
        ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
        ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
        ->where('locacion.Id_locacion', '=', $Id_locacion)
        ->get();

//consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
        $personastotal = DB::table('locacion')
        ->select('Id_locacion', 'Capacidad_personas')
        ->where('Id_locacion', '=', $Id_locacion)
        ->get();

//con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
        if(count($fecha_bd) == 0){
            if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){

            $nombrecliente = DB::table('cliente')
            ->select('Nombre', 'Numero_celular')
            ->where('Id_cliente', '=', $request->get('selector_cliente'))
            ->get();
            
            $reservacion = new Reservacion();
            $reservacion->Numero_personas_extras = $request->get('extras');
            $reservacion->Title = $nombrecliente;
            $reservacion->Fecha_reservacion = date('y-m-d');
            $reservacion->Start_date = $request->get('f_entrada');
            $reservacion->End_date = $request->get('f_salida');
            $reservacion->Total_de_personas = $request->get('p_total');
            $reservacion->Tipo_de_cobro = $request->get('tipo_renta');
            $reservacion->Monto_pagado_anticipo = $request->get('monto_anticipo');
            $reservacion->Metodo_pago_anticipo = $request->get('metodo_pago');
            $reservacion->Fecha_pago_anticipo = $request->get('fecha_pago');
            $reservacion->save();
            $lastreservacion =DB::getPdo()->lastInsertId();
        
            $lugar_reserva = new Lugares_reservados();
            $lugar_reserva-> Id_reservacion  = $lastreservacion;
            $lugar_reserva-> Id_locacion = $Id_locacion;
            $lugar_reserva-> Id_cliente = $request->get('selector_cliente');
            $lugar_reserva-> Id_estado_ocupacion  = "6";
            $lugar_reserva->save();
        
            $affected = DB::table('locacion')
            ->where('Id_locacion', '=', $Id_locacion)
            ->update(['Id_estado_ocupacion' => "6"]);

            
//array que guarda la foto 2
            $this->validate($request, array(
                'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
                ));
                $image = $request->file('img2');
        
                if($image != ''){
                $nombreImagen = $nombrecliente[0]->Numero_celular.'_'.$request->get('fecha_pago').'_'.rand(). '.' . $image->getClientOriginalExtension();
                $base64Img = $request->nuevaImagen2;
                $base_to_php = explode(',',$base64Img);
                $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
                $filepath = 'C:/xampp/htdocs/alohate/public/uploads/comprobantes_pago_anticipo/'.$nombreImagen;
                $guardarImagen = file_put_contents($filepath, $data);
        
                if ($guardarImagen !== false) {
                    DB::table('reservacion')
                    ->where('Id_reservacion', '=', $lastreservacion)
                    ->update(['Foto_comprobante_anticipo' => $nombreImagen]);
                }}
        

            Alert::success('Exito', 'Se ha registrado la reservacion con exito');
            return redirect()->back();

            }else{
                Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
                return redirect()->back();
            }
        }else{
            Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
            return redirect()->back();
        }

}catch(Exception $ex){
        Alert::error('Error', 'La reservacion no se pudo registrar revisa que todo este en orden');
        return redirect()->back();
}
    
}
    

   
//funcion que muestra el formulario para añadir una reservacion con cliente nuevo en una casa
public function ViewReservaCasaNC($Id_locacion){

    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.agregarreserva_nc_casa',compact('Id_locacion', 'totalcocheras', 'result_resta'));
}

//funcion que guarda el registro de una reservacion para un nuevo cliente en una casa
public function StoreReservaCasaNC(Request $request, $Id_locacion){

try{
    //consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
    $fecha_bd = DB::table('reservacion')
    ->select('locacion.Id_locacion', 'Start_date', 'End_date' )
    ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
    ->leftJoin("locacion", "locacion.Id_locacion", "=", "lugar_res.Id_locacion")
    ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
    ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
    ->where('locacion.Id_locacion', '=', $Id_locacion)
    ->get();
    
//consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
    $personastotal = DB::table('locacion')
    ->select('Id_locacion', 'Capacidad_personas')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

//con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
    if(count($fecha_bd) == 0){
        if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){
        
        $cliente = new Cliente();
        $cliente->Numero_celular = $request->get('cel');
        $cliente->Nombre = $request->get('nombre');
        $cliente->Apellido_paterno = $request->get('a_paterno');
        $cliente->Apellido_materno = $request->get('a_materno');
        $cliente->Email = $request->get('email');
        $cliente->save();
        $lastcliente =DB::getPdo()->lastInsertId();
    
        $nombrecliente = DB::table('cliente')
        ->select('Nombre', 'Numero_celular')
        ->where('Id_cliente', '=', $lastcliente)
        ->get();
        
        $reservacion = new Reservacion();
        $reservacion->Numero_personas_extras = $request->get('extras');
        $reservacion->Title = $nombrecliente;
        $reservacion->Fecha_reservacion = date('y-m-d');
        $reservacion->Start_date = $request->get('f_entrada');
        $reservacion-> End_date= $request->get('f_salida');
        $reservacion->Total_de_personas = $request->get('p_total');
        $reservacion->Tipo_de_cobro = $request->get('tipo_renta');
        $reservacion->Monto_pagado_anticipo = $request->get('monto_anticipo');
        $reservacion->Metodo_pago_anticipo = $request->get('metodo_pago');
        $reservacion->Fecha_pago_anticipo = $request->get('fecha_pago');
        $reservacion->save();
        $lastreservacion =DB::getPdo()->lastInsertId();
    
        $lugar_reserva = new Lugares_reservados();
        $lugar_reserva-> Id_reservacion  = $lastreservacion;
        $lugar_reserva-> Id_locacion = $Id_locacion;
        $lugar_reserva-> Id_cliente = $lastcliente;
        $lugar_reserva-> Id_estado_ocupacion  = "6";
        $lugar_reserva->save();
    
        $affected = DB::table('locacion')
        ->where('Id_locacion', '=', $Id_locacion)
        ->update(['Id_estado_ocupacion' => "6"]);

        
//array que guarda la foto 2
      $this->validate($request, array(
        'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img2');
  
        if($image != ''){
           $nombreImagen = $nombrecliente[0]->Numero_celular.'_'.$request->get('fecha_pago').'_'.rand(). '.' . $image->getClientOriginalExtension();
           $base64Img = $request->nuevaImagen2;
           $base_to_php = explode(',',$base64Img);
           $data = base64_decode($base_to_php[1]);
  //aviso         
  //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
           $filepath = 'C:/xampp/htdocs/alohate/public/uploads/comprobantes_pago_anticipo/'.$nombreImagen;
           $guardarImagen = file_put_contents($filepath, $data);
  
           if ($guardarImagen !== false) {
              DB::table('reservacion')
              ->where('Id_reservacion', '=', $lastreservacion)
              ->update(['Foto_comprobante_anticipo' => $nombreImagen]);
        }}
       
    
        Alert::success('Exito', 'Se ha registrado la reservacion con exito');
        return redirect()->back();

    }else{
        Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
        return redirect()->back();
    }
    }else{
        Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
        return redirect()->back();
    }
}catch(Exception $ex){
        Alert::error('Error', 'La reservacion no se pudo registrar revisa que todo este en orden');
        return redirect()->back();
}
    
}






public function DetalleReservaCasa($Id_reservacion, $Id_locacion, $Id_lugares_reservados){
    //consulta a la bd para traer los datos de los detalles de una reservacion 
            $detallereserva = DB::table('lugares_reservados')
            ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_locacion', 
                'lugares_reservados.Id_cliente',
                'est.Nombre_estado',
                'reserva.Id_reservacion','reserva.Id_colaborador',
                'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
                'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
                'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
                'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
                'reserva.Tipo_de_cobro',
                'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email',
                'cliente.Numero_celular',
                'loc.Id_locacion', 'loc.Id_estado_ocupacion',
                'loc.Nombre_locacion','loc.Tipo_renta', 'loc.Calle',
                'loc.Numero_ext', 'loc.Colonia', 'loc.Ubi_google_maps', 
                'loc.Numero_total_de_pisos','loc.Numero_total_habitaciones', 'loc.Numero_total_depas', 
                'loc.Numero_total_locales','loc.Capacidad_personas', 'loc.Precio_noche', 'loc.Precio_semana',
                'loc.Precio_catorcedias','loc.Precio_mes', 'loc.Deposito_garantia_casa', 
                'loc.Uso_cocheras', 'loc.Total_cocheras','loc.Encargado',
                'loc.Espacio_superficie','loc.Zona_ciudad', 'loc.Numero_habs_actuales', 
                'loc.Numero_depas_actuales', 'loc.Numero_locs_actuales','loc.Nota','loc.Descripcion',
                'loc.Cobro_p_ext_mes_c','loc.Cobro_p_ext_catorcena_c','loc.Cobro_p_ext_noche_c',
                'loc.Cobro_anticipo_mes_c','loc.Cobro_anticipo_catorcena_c','loc.Camas_juntas')
            ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
            ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
            ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
            ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "lugares_reservados.Id_locacion")
            ->where('reserva.Id_reservacion', '=', $Id_reservacion)
            ->where('loc.Id_locacion', '=', $Id_locacion)
            ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
            ->get();
    
    //funcion para calcular dias entre 2 fechas
            $fecha1 =   $detallereserva[0]->Start_date;
            $fecha2 =   $detallereserva[0]->End_date;
    //aqui saco los segundos de las fechas
            $segfecha1 = strtotime($fecha1);
            $segfecha2 = strtotime($fecha2);
    //segundos de diferencia entre las 2 fechas
            $segtranscurridos = $segfecha2 - $segfecha1;
    //minutos transcurridos entre las 2 fechas
            $mintranscurridos = $segtranscurridos/60;
    //horas transcurridas entre las 2 fechas
            $horastranscurridas = $mintranscurridos/60;
    //dias transcurridos entre las 2 fechas
            $diastranscurridos = $horastranscurridas/24;
    //redondeando los dias para que esten completos
            $diasredondeados = floor($diastranscurridos);
    
        switch ($detallereserva[0]->Tipo_de_cobro) {
            case 'Noche':
    //variable que trae los dias
                $diasredondeados;
    //variable que me saca el costo de los dias de estancia
                $monto_por_dias = $diasredondeados * $detallereserva[0]->Precio_noche;
    //variable que me trae el costo por personas extra ya no se cobrara las personas extras por noche
                //$monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_noche_c * $detallereserva[0]->Numero_personas_extras;
    //variable que me hace la suma total            
                $suma_monto = $monto_por_dias + $detallereserva[0]->Deposito_garantia_casa;
    
                return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservacasa', compact('detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto'));
                //return "son $diasredondeados noches";
                break;
            
            case 'Semana':
    
                $calculosemana = $diasredondeados/7;
                $redondeosema = floor($calculosemana);
    //variable que me saca el costo de los dias de estancia
                $monto_por_dias = $redondeosema * $detallereserva[0]->Precio_semana;
    //variable que me trae el costo por personas extra
                $monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_catorcena_c * $detallereserva[0]->Numero_personas_extras;
    //variable que me hace la suma total y me resta el monto de anticipo       
                $suma_monto = $monto_por_dias + $monto_por_p_extras + $detallereserva[0]->Deposito_garantia_casa - $detallereserva[0]->Cobro_anticipo_catorcena_c;
    
                return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservacasa', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
                //return "son $redondeosema semanas";
                break;
             
            case 'Catorcena':
    
                $calculocatorcena = $diasredondeados/14;
                $redondeocatorcena = floor($calculocatorcena);
    //variable que me saca el costo de los dias de estancia
                $monto_por_dias = $redondeocatorcena * $detallereserva[0]->Precio_catorcedias;
    //variable que me trae el costo por personas extra
                $monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_catorcena_c * $detallereserva[0]->Numero_personas_extras;
    //variable que me hace la suma total y me resta el monto de anticipo       
                $suma_monto = $monto_por_dias + $monto_por_p_extras + $detallereserva[0]->Deposito_garantia_casa - $detallereserva[0]->Cobro_anticipo_catorcena_c;
                
                return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservacasa', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
                //return "son $redondeocatorcena catorcenas";
                break;
    
            case 'Mes':
    
                $calculomes = $diasredondeados/28;
                $redondeomes = floor($calculomes);
    //variable que me saca el costo de los dias de estancia
                $monto_por_dias = $redondeomes * $detallereserva[0]->Precio_mes;
    //variable que me trae el costo por personas extra
                $monto_por_p_extras = $detallereserva[0]->Cobro_p_ext_mes_c * $detallereserva[0]->Numero_personas_extras;
    //variable que me hace la suma total y me resta el monto de anticipo       
                $suma_monto = $monto_por_dias + $monto_por_p_extras + $detallereserva[0]->Deposito_garantia_casa - $detallereserva[0]->Cobro_anticipo_mes_c;
                
                return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservacasa', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
                //return "es $redondeomes mes";
                break;
        }
    
    }





//funcion para la vista de editar una reservacion de un depa
public function EditarReservaCasa($Id_reservacion, $Id_locacion, $Id_lugares_reservados){

    $reservacion = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_locacion', 
        'lugares_reservados.Id_local', 'lugares_reservados.Id_departamento', 'lugares_reservados.Id_cliente',
        'est.Nombre_estado',
        'reserva.Id_reservacion', 'reserva.Id_colaborador',  
        'reserva.Start_date','reserva.End_date', 'reserva.Title',
        'reserva.Fecha_reservacion','reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 
        'reserva.Fecha_pago_anticipo','reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento',
        'reserva.Monto_uso_cochera', 'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera',
        'reserva.Monto_pagado_anticipo','reserva.Total_de_personas','reserva.Tipo_de_cobro',
        'cliente.Id_cliente','cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email',
        'cliente.Numero_celular','cliente.Pais',
        'loc.Id_locacion', 'loc.Id_estado_ocupacion',
        'loc.Nombre_locacion','loc.Tipo_renta', 'loc.Calle',
        'loc.Numero_ext', 'loc.Colonia', 'loc.Ubi_google_maps', 
        'loc.Numero_total_de_pisos','loc.Numero_total_habitaciones', 'loc.Numero_total_depas', 
        'loc.Numero_total_locales','loc.Capacidad_personas', 'loc.Precio_noche', 'loc.Precio_semana',
        'loc.Precio_catorcedias','loc.Precio_mes', 'loc.Deposito_garantia_casa', 
        'loc.Uso_cocheras', 'loc.Total_cocheras','loc.Encargado',
        'loc.Espacio_superficie','loc.Zona_ciudad', 'loc.Numero_habs_actuales', 
        'loc.Numero_depas_actuales', 'loc.Numero_locs_actuales','loc.Nota','loc.Descripcion',
        'loc.Cobro_p_ext_mes_c','loc.Cobro_p_ext_catorcena_c','loc.Cobro_p_ext_noche_c',
        'loc.Cobro_anticipo_mes_c','loc.Cobro_anticipo_catorcena_c','loc.Camas_juntas')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "lugares_reservados.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('loc.Id_locacion', '=', $Id_locacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    
    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.editarreserva_oc_casa',compact('Id_reservacion', 'Id_lugares_reservados', 'Id_locacion', 'totalcocheras', 'result_resta', 'reservacion'));

}




public function UpdateReservaCasa(Request $request, Reservacion $reservacion, Lugares_reservados $lugar_reservado, $Id_locacion){


    try{
    //consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
        $fecha_bd = DB::table('reservacion')
        ->select('loc.Id_locacion', 'Start_date', 'End_date' )
        ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
        ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "lugar_res.Id_locacion")
        ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
        ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
        ->where('loc.Id_locacion', '=', $Id_locacion)
        ->get();
    //consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
        $personastotal = DB::table('locacion')
        ->select('Id_locacion', 'Capacidad_personas')
        ->where('Id_locacion', '=', $Id_locacion)
        ->get();
    //consulta para checar las cocheras que esta usando la reservacion antes de la actualizacion
        $consulta_old_cochera_r = DB::table('reservacion')
        ->select('Espacios_cochera', 'Id_reservacion')
        ->where('Id_reservacion', '=', $reservacion->Id_reservacion)
        ->get(); 
    
        
    
    //con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
        if(count($fecha_bd) == 0){   
          if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){
            
            $nombrecliente = DB::table('cliente')
            ->select('Nombre', 'Numero_celular')
            ->where('Id_cliente', '=', $request->selector_cliente)
            ->get();
            
    //reservacion
            $reservacion->Title = $nombrecliente;
            $reservacion->Start_date = $request->f_entrada;
            $reservacion->End_date = $request->f_salida;
            $reservacion->Total_de_personas = $request->p_total;
            $reservacion->Numero_personas_extras = $request->extras;
            $reservacion->Tipo_de_cobro = $request->tipo_renta;
            $reservacion->save();
    
    //lugar
            $lugar_reservado-> Id_lugares_reservados = $lugar_reservado->Id_lugares_reservados;
            $lugar_reservado-> Id_reservacion  = $reservacion->Id_reservacion;
            $lugar_reservado-> Id_locacion = $Id_locacion;
            $lugar_reservado-> Id_cliente = $request->selector_cliente;
            $lugar_reservado-> Id_estado_ocupacion  = "6";
            $lugar_reservado->save();
    
            Alert::success('Exito', 'Se ha actualizado la reservacion con exito');
            return redirect()->back();
            }else{
                Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
                return redirect()->back();
            }
        }else{
            Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
            return redirect()->back();
        }
    }catch(Exception $ex){
        Alert::error('Error', 'La reservacion no se pudo actualizar revisa que todo este en orden');
        return redirect()->back();
    }
}
    
public function ShowClientesEditCasa(Request $request){
    
        $data = trim($request->valor);
        $result = DB::table('cliente')
        ->where('Numero_celular','like','%'.$data.'%')
        ->limit(3)
        ->get();
    
        return response()->json([
            "estado" =>1,
            "result" => $result
        ]);
}



//funcion para la vista de editar una reservacion de un depa
public function EditarReservaCasaNC($Id_reservacion , $Id_lugares_reservados, $Id_locacion){

    $reservacion = DB::table('lugares_reservados')
    ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_locacion', 
        'lugares_reservados.Id_local', 'lugares_reservados.Id_departamento', 'lugares_reservados.Id_cliente',
        'est.Nombre_estado',
        'reserva.Id_reservacion', 'reserva.Id_colaborador',  
        'reserva.Start_date','reserva.End_date', 'reserva.Title',
        'reserva.Fecha_reservacion','reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 
        'reserva.Fecha_pago_anticipo','reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento',
        'reserva.Monto_uso_cochera', 'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera',
        'reserva.Monto_pagado_anticipo','reserva.Total_de_personas','reserva.Tipo_de_cobro',
        'cliente.Id_cliente','cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno','cliente.Email',
        'cliente.Numero_celular','cliente.Pais',
        'loc.Id_locacion', 'loc.Id_estado_ocupacion',
        'loc.Nombre_locacion','loc.Tipo_renta', 'loc.Calle',
        'loc.Numero_ext', 'loc.Colonia', 'loc.Ubi_google_maps', 
        'loc.Numero_total_de_pisos','loc.Numero_total_habitaciones', 'loc.Numero_total_depas', 
        'loc.Numero_total_locales','loc.Capacidad_personas', 'loc.Precio_noche', 'loc.Precio_semana',
        'loc.Precio_catorcedias','loc.Precio_mes', 'loc.Deposito_garantia_casa', 
        'loc.Uso_cocheras', 'loc.Total_cocheras','loc.Encargado',
        'loc.Espacio_superficie','loc.Zona_ciudad', 'loc.Numero_habs_actuales', 
        'loc.Numero_depas_actuales', 'loc.Numero_locs_actuales','loc.Nota','loc.Descripcion',
        'loc.Cobro_p_ext_mes_c','loc.Cobro_p_ext_catorcena_c','loc.Cobro_p_ext_noche_c',
        'loc.Cobro_anticipo_mes_c','loc.Cobro_anticipo_catorcena_c','loc.Camas_juntas')
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
    ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "lugares_reservados.Id_locacion")
    ->where('reserva.Id_reservacion', '=', $Id_reservacion)
    ->where('loc.Id_locacion', '=', $Id_locacion)
    ->where('lugares_reservados.Id_lugares_reservados', '=', $Id_lugares_reservados)
    ->get();

    
    $totalcocheras = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato1 = DB::table('locacion')
    ->select( 'Id_locacion', 'Uso_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $dato2 = DB::table('locacion')
    ->select( 'Id_locacion', 'Total_cocheras')
    ->where('Id_locacion', '=', $Id_locacion)
    ->get();

    $result_resta = (int)$dato2[0]->Total_cocheras - (int)$dato1[0]->Uso_cocheras;

    return view('Reservaciones y rentas.Agregar_Editar_Reservaciones.editarreserva_nc_casa',compact('Id_reservacion', 'Id_lugares_reservados', 'Id_locacion', 'totalcocheras', 'result_resta', 'reservacion'));

}




public function UpdateReservaCasaNC(Request $request, Reservacion $reservacion, Lugares_reservados $lugar_reservado, $Id_locacion){


    try{
    //consulta que me revisa las fechas ya agendadas en la bd para que no se repitan las fechas
        $fecha_bd = DB::table('reservacion')
        ->select('loc.Id_locacion', 'Start_date', 'End_date' )
        ->leftJoin("lugares_reservados as lugar_res", "lugar_res.Id_reservacion", "=", "reservacion.Id_reservacion")
        ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "lugar_res.Id_locacion")
        ->whereRaw("date(Start_date)= '".date_format(date_create($request->get('f_entrada')),"Y-m-d")."'")
        ->whereRaw("date(End_date)= '".date_format(date_create($request->get('f_salida')),"Y-m-d")."'")
        ->where('loc.Id_locacion', '=', $Id_locacion)
        ->get();
    //consulta que me trae la capacidad de personas del lugar y la uso para el if para que no sobrepase el numero de personas
        $personastotal = DB::table('locacion')
        ->select('Id_locacion', 'Capacidad_personas')
        ->where('Id_locacion', '=', $Id_locacion)
        ->get();
    //consulta para checar las cocheras que esta usando la reservacion antes de la actualizacion
        $consulta_old_cochera_r = DB::table('reservacion')
        ->select('Espacios_cochera', 'Id_reservacion')
        ->where('Id_reservacion', '=', $reservacion->Id_reservacion)
        ->get(); 
    
        
    
//con el if pongo la regla, si no hay algun registro con la fecha dada que se haga el registro nuevo, si no que me alerte para tomar otra fecha
        if(count($fecha_bd) == 0){   
          if($request->get('p_total') <= $personastotal[0]->Capacidad_personas){

            $cliente = new Cliente();
            $cliente->Numero_celular = $request->get('cel');
            $cliente->Nombre = $request->get('nombre');
            $cliente->Apellido_paterno = $request->get('a_paterno');
            $cliente->Apellido_materno = $request->get('a_materno');
            $cliente->Email = $request->get('email');
            $cliente->save();
            $lastcliente =DB::getPdo()->lastInsertId();
        
            $nombrecliente = DB::table('cliente')
            ->select('Nombre', 'Numero_celular')
            ->where('Id_cliente', '=', $lastcliente)
            ->get();
            
//reservacion
            $reservacion->Title = $nombrecliente;
            $reservacion->Start_date = $request->f_entrada;
            $reservacion->End_date = $request->f_salida;
            $reservacion->Total_de_personas = $request->p_total;
            $reservacion->Numero_personas_extras = $request->extras;
            $reservacion->Tipo_de_cobro = $request->tipo_renta;
            $reservacion->save();
    
    //lugar
            $lugar_reservado-> Id_lugares_reservados = $lugar_reservado->Id_lugares_reservados;
            $lugar_reservado-> Id_reservacion  = $reservacion->Id_reservacion;
            $lugar_reservado-> Id_locacion = $Id_locacion;
            $lugar_reservado-> Id_cliente = $lastcliente;
            $lugar_reservado-> Id_estado_ocupacion  = "6";
            $lugar_reservado->save();
    
            Alert::success('Exito', 'Se ha actualizado la reservacion con exito');
            return redirect()->back();
            }else{
                Alert::warning('Advertencia', 'El numero de personas sobrepasa el limite de la capacidad de este lugar. busca un lugar con mayor capacidad de personas');
                return redirect()->back();
            }
        }else{
            Alert::warning('Advertencia', 'La fecha seleccionada ya esta ocupada, por favor selecciona otra');
            return redirect()->back();
        }
    }catch(Exception $ex){
        Alert::error('Error', 'La reservacion no se pudo actualizar revisa que todo este en orden');
        return redirect()->back();
    }
}











}



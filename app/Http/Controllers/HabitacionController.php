<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Query\Builder;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\Locacion;
use App\Models\Plantas_pisos;
use App\Models\Estado_ocupacion;
use App\Models\Servicio_bano;
use App\Models\Servicio_cama;
use App\Models\Habitacion;
use App\Models\Local;
use App\Models\Departamento;
use App\Models\Servicios_estancia;
use App\Models\Fotos_lugares;
use App\Models\Relacion_servicios;

use function Laravel\Prompts\alert;
use Exception;

class HabitacionController extends Controller
{
    public function ViewHabitaciones($Id_locacion, Request $request){

    $estatus_hab =  Estado_ocupacion::get();
//variables para el buscador
//variables para el buscador   
    $cappersonas = trim($request->get('personas'));
    $estatus = trim($request->get('estatus'));
    $banos = trim($request->get('banos'));
    $reservacion = trim($request->get('reservacion'));


//consulta padre que me trae los registros de las habitaciones
    $habitaciones = DB::table('habitacion')
    ->select('habitacion.Id_habitacion', 'habitacion.Id_locacion','est.Nombre_estado', 'Id_colaborador',
         'Nombre_hab','Capacidad_personas', 'Deposito_garantia_hab', 
         'Precio_noche', 'Precio_semana',
         'Precio_catorcedias', 'Precio_mes', 
         'Encargado', 'Espacio_superficie','Nota',
         'Descripcion','Cobro_p_ext_mes_h',
         'Cobro_p_ext_catorcena_h', 'Cobro_p_ext_noche_h',
         'Cobro_anticipo_mes_h','Cobro_anticipo_catorcena_h',
         'Camas_juntas', 'bano.Bano_compartido',
         'bano.Bano_medio', 'bano.Bano_completo', 'bano.Bano_completo_RL', 'cama.Cama_individual',
         'cama.Cama_matrimonial', 'cama.Litera_individual', 'cama.Litera_matrimonial', 'cama.Litera_ind_mat',
         'cama.Cama_kingsize', 'planta.Nombre_planta')
//joins que me vinculan a otras tablas para hacer consultas 
   ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "habitacion.Id_estado_ocupacion")
   ->leftJoin("servicio_bano as bano", "bano.Id_habitacion", "=", "habitacion.Id_habitacion")
   ->leftJoin("servicio_cama as cama", "cama.Id_habitacion", "=", "habitacion.Id_habitacion")
   ->leftJoin("plantas_pisos as planta", "planta.Id_habitacion", "=", "habitacion.Id_habitacion")

//consulta crudas de sql que me cuenta los registros que esten en las plantas para poder mostrarlos en la vista 
//planta baja
->selectRaw("(SELECT COUNT(*) AS piso1 FROM `habitacion` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_habitacion = habitacion.Id_habitacion
    WHERE piso.Nombre_planta = 'Planta baja'
    AND habitacion.Id_locacion =  ".$Id_locacion.") as totalhabspisobajo")
//piso 1
    ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `habitacion` as piso_de_habs1 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_habitacion = piso_de_habs1.Id_habitacion
    WHERE piso.Nombre_planta = 'Planta 1'
    AND piso_de_habs1.Id_locacion =  ".$Id_locacion.") as totalhabspiso1")
//piso 2
    ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `habitacion` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_habitacion = habitacion.Id_habitacion
    WHERE piso.Nombre_planta = 'Planta 2'
    AND habitacion.Id_locacion =  ".$Id_locacion.") as totalhabspiso2")
//piso 3
    ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `habitacion` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_habitacion = habitacion.Id_habitacion
    WHERE piso.Nombre_planta = 'Planta 3'
    AND habitacion.Id_locacion =  ".$Id_locacion.") as totalhabspiso3")
//piso 4
    ->selectRaw("(SELECT COUNT(*) AS piso4 FROM `habitacion` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_habitacion = habitacion.Id_habitacion
    WHERE piso.Nombre_planta = 'Planta 4'
    AND habitacion.Id_locacion =  ".$Id_locacion.") as totalhabspiso4")
//piso 5
    ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `habitacion` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_habitacion = habitacion.Id_habitacion
    WHERE piso.Nombre_planta = 'Planta 5'
    AND habitacion.Id_locacion =  ".$Id_locacion.") as totalhabspiso5")
//piso 6
    ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `habitacion` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_habitacion = habitacion.Id_habitacion
    WHERE piso.Nombre_planta = 'Planta 6'
    AND habitacion.Id_locacion =  ".$Id_locacion.") as totalhabspiso6")


   ->where('habitacion.Id_locacion', '=', $Id_locacion)
   ->where('Capacidad_personas','LIKE','%'.$cappersonas.'%')
   ->where('est.Id_estado_ocupacion','LIKE','%'.$estatus.'%')
   ->get();


    $locacion=DB::table('locacion')
   ->select('locacion.Id_locacion', 'Nombre_locacion', 'est.Nombre_estado' ,'Tipo_renta', 'Calle',
        'Numero_ext', 'Colonia', 'Ubi_google_maps', 'Numero_total_de_pisos', 
        'Numero_total_habitaciones','Numero_habs_actuales','Numero_total_depas', 'Numero_depas_actuales', 'Numero_locs_actuales', 'Numero_total_locales',
        'Capacidad_personas', 'Precio_noche', 'Precio_semana', 'Precio_catorcedias',
        'Precio_mes', 'Deposito_garantia_casa', 'Uso_cocheras', 'Total_cocheras', 'Espacio_superficie', 'Zona_ciudad')
   ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "locacion.Id_estado_ocupacion")
   ->where('Id_locacion', '=', $Id_locacion)
   ->get();

   return view('Habitaciones.habitaciones', compact('locacion', 'estatus_hab', 'habitaciones', 'cappersonas', 'estatus', 'reservacion'));
   
}


//funcion que muestra los detalles de una habitacion libre
public function DetallesHabLibre($Id_locacion, $Id_habitacion ){

   $locacion=DB::table('locacion')
      ->select('locacion.Id_locacion', 'Nombre_locacion', 'est.Nombre_estado' ,'Tipo_renta', 'Calle',
      'Numero_ext', 'Colonia', 'Ubi_google_maps', 'Numero_total_de_pisos', 
      'Numero_total_habitaciones', 'Numero_total_depas', 'Numero_total_locales',
      'Capacidad_personas', 'Precio_noche', 'Precio_semana', 'Precio_catorcedias',
      'Precio_mes', 'Deposito_garantia_casa', 'Uso_cocheras', 'Total_cocheras', 'Espacio_superficie', 'Zona_ciudad'
      , 'Nota')
      -> leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "locacion.Id_estado_ocupacion")
      ->where('Id_locacion', '=', $Id_locacion)
      ->get();


      $habitaciones = DB::table('habitacion')
      ->select('habitacion.Id_habitacion', 'habitacion.Id_locacion','est.Nombre_estado', 'Id_colaborador',
         'Nombre_hab','Capacidad_personas', 'Deposito_garantia_hab', 
         'Precio_noche',  'Precio_semana',
         'Precio_catorcedias', 'Precio_mes', 'Encargado', 
         'Espacio_superficie','Nota','Descripcion','Cobro_p_ext_mes_h',
         'Cobro_p_ext_catorcena_h','Cobro_p_ext_noche_h','Cobro_anticipo_mes_h',
         'Cobro_anticipo_catorcena_h','Camas_juntas',
         'bano.Bano_compartido',
         'bano.Bano_medio', 'bano.Bano_completo', 'bano.Bano_completo_RL', 'cama.Cama_individual',
         'cama.Cama_matrimonial', 'cama.Litera_individual', 'cama.Litera_matrimonial', 'cama.Litera_ind_mat',
         'cama.Cama_kingsize', 'planta.Nombre_planta')
      //joins que me vinculan a otras tablas para hacer consultas 
      ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "habitacion.Id_estado_ocupacion")
      ->leftJoin("servicio_bano as bano", "bano.Id_habitacion", "=", "habitacion.Id_habitacion")
      ->leftJoin("servicio_cama as cama", "cama.Id_habitacion", "=", "habitacion.Id_habitacion")
      ->leftJoin("plantas_pisos as planta", "planta.Id_habitacion", "=", "habitacion.Id_habitacion")
      ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
      ->get();

switch($habitaciones[0]->Nombre_estado){
   case 'Desocupada':

         $lugar_cliente_reservado = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
         $servicios = Servicios_estancia::get();
      
         $files = DB::table('fotos_lugares')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $relacion_servicio =  DB::table('relacion_servicios') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_bano= DB::table('servicio_bano') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_cama= DB::table('servicio_cama')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
         
         $reglafiles = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas','reserva.Foto_reglamento','reserva.Foto_aviso_privacidad',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
   
   
   
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
      $lugar_reservacion= DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
               'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();
   
   //llaado de datos para el calendario
   //aqui hago una variable de tipo array sola
      $reservacion = [];
   //hago un foreach con la variable que me hace la consulta a la bd
      foreach($lugar_reservacion as $reserv){
   //ejemplo para cambiar el titulo
         $json = json_decode(
            $reserv->Title
         );
         $titulo = $json[0]->Nombre." ".$json[0]->Numero_celular;
   
   //llamo a la variable que tiene el array solo para llenarlo de datos el formato lo saque del calendario
         $reservacion[] = [
            'title' => $titulo,
            'start' => $reserv->Start_date,
            'end' => $reserv->End_date,
         ];
      }
   //despues ponemos en formato json los datos para que los entienda el calendario y esa variable se la enviamos a la vista con el metodo compact
      response()->json($lugar_reservacion);
   
   
   
      if($habitaciones[0]->Nombre_estado  == "Desocupada"){
         return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($habitaciones[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
            return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($habitaciones[0]->Nombre_estado == "Desactivada"){
               return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
            }else{
               if($habitaciones[0]->Nombre_estado == "Rentada"){
                  return view('Habitaciones.detalleshabocupada', compact('monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto','locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
               }else{
                  if($habitaciones[0]->Nombre_estado == "Reservada"){
                     return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }else{
                     if($habitaciones[0]->Nombre_estado == "Pago por confirmar"){
                        return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }
               }
            }
   
         }
      }
   }
   
   break;
   case 'En Mantenimiento/Limpieza':
         
         $lugar_cliente_reservado = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
         $servicios = Servicios_estancia::get();
      
         $files = DB::table('fotos_lugares')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $relacion_servicio =  DB::table('relacion_servicios') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_bano= DB::table('servicio_bano') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_cama= DB::table('servicio_cama')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
         
         $reglafiles = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas','reserva.Foto_reglamento','reserva.Foto_aviso_privacidad',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
   
   
   
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
      $lugar_reservacion= DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
               'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();
   
   //llaado de datos para el calendario
   //aqui hago una variable de tipo array sola
      $reservacion = [];
   //hago un foreach con la variable que me hace la consulta a la bd
      foreach($lugar_reservacion as $reserv){
   //ejemplo para cambiar el titulo
         $json = json_decode(
            $reserv->Title
         );
         $titulo = $json[0]->Nombre." ".$json[0]->Numero_celular;
   
   //llamo a la variable que tiene el array solo para llenarlo de datos el formato lo saque del calendario
         $reservacion[] = [
            'title' => $titulo,
            'start' => $reserv->Start_date,
            'end' => $reserv->End_date,
         ];
      }
   //despues ponemos en formato json los datos para que los entienda el calendario y esa variable se la enviamos a la vista con el metodo compact
      response()->json($lugar_reservacion);
   
   
   
      if($habitaciones[0]->Nombre_estado  == "Desocupada"){
         return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($habitaciones[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
            return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($habitaciones[0]->Nombre_estado == "Desactivada"){
               return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
            }else{
               if($habitaciones[0]->Nombre_estado == "Rentada"){
                  return view('Habitaciones.detalleshabocupada', compact('monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto','locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
               }else{
                  if($habitaciones[0]->Nombre_estado == "Reservada"){
                     return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }else{
                     if($habitaciones[0]->Nombre_estado == "Pago por confirmar"){
                        return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }
               }
            }
   
         }
      }
   }
   
   break;
   case 'Desactivada':
         
         $lugar_cliente_reservado = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
         $servicios = Servicios_estancia::get();
      
         $files = DB::table('fotos_lugares')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $relacion_servicio =  DB::table('relacion_servicios') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_bano= DB::table('servicio_bano') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_cama= DB::table('servicio_cama')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
         
         $reglafiles = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas','reserva.Foto_reglamento','reserva.Foto_aviso_privacidad',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
   
   
   
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
      $lugar_reservacion= DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
               'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();
   
   //llaado de datos para el calendario
   //aqui hago una variable de tipo array sola
      $reservacion = [];
   //hago un foreach con la variable que me hace la consulta a la bd
      foreach($lugar_reservacion as $reserv){
   //ejemplo para cambiar el titulo
         $json = json_decode(
            $reserv->Title
         );
         $titulo = $json[0]->Nombre." ".$json[0]->Numero_celular;
   
   //llamo a la variable que tiene el array solo para llenarlo de datos el formato lo saque del calendario
         $reservacion[] = [
            'title' => $titulo,
            'start' => $reserv->Start_date,
            'end' => $reserv->End_date,
         ];
      }
   //despues ponemos en formato json los datos para que los entienda el calendario y esa variable se la enviamos a la vista con el metodo compact
      response()->json($lugar_reservacion);
   
   
   
      if($habitaciones[0]->Nombre_estado  == "Desocupada"){
         return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($habitaciones[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
            return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($habitaciones[0]->Nombre_estado == "Desactivada"){
               return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
            }else{
               if($habitaciones[0]->Nombre_estado == "Rentada"){
                  return view('Habitaciones.detalleshabocupada', compact('monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto','locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
               }else{
                  if($habitaciones[0]->Nombre_estado == "Reservada"){
                     return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }else{
                     if($habitaciones[0]->Nombre_estado == "Pago por confirmar"){
                        return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }
               }
            }
   
         }
      }
   }
   
   break;
   case 'Reservada':
         
         $lugar_cliente_reservado = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
         $servicios = Servicios_estancia::get();
      
         $files = DB::table('fotos_lugares')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $relacion_servicio =  DB::table('relacion_servicios') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_bano= DB::table('servicio_bano') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_cama= DB::table('servicio_cama')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
         
         $reglafiles = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas','reserva.Foto_reglamento','reserva.Foto_aviso_privacidad',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
   
   
   
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
      $lugar_reservacion= DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
               'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();
   
   //llaado de datos para el calendario
   //aqui hago una variable de tipo array sola
      $reservacion = [];
   //hago un foreach con la variable que me hace la consulta a la bd
      foreach($lugar_reservacion as $reserv){
   //ejemplo para cambiar el titulo
         $json = json_decode(
            $reserv->Title
         );
         $titulo = $json[0]->Nombre." ".$json[0]->Numero_celular;
   
   //llamo a la variable que tiene el array solo para llenarlo de datos el formato lo saque del calendario
         $reservacion[] = [
            'title' => $titulo,
            'start' => $reserv->Start_date,
            'end' => $reserv->End_date,
         ];
      }
   //despues ponemos en formato json los datos para que los entienda el calendario y esa variable se la enviamos a la vista con el metodo compact
      response()->json($lugar_reservacion);
   
   
   
      if($habitaciones[0]->Nombre_estado  == "Desocupada"){
         return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($habitaciones[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
            return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($habitaciones[0]->Nombre_estado == "Desactivada"){
               return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
            }else{
               if($habitaciones[0]->Nombre_estado == "Rentada"){
                  return view('Habitaciones.detalleshabocupada', compact('monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto','locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
               }else{
                  if($habitaciones[0]->Nombre_estado == "Reservada"){
                     return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }else{
                     if($habitaciones[0]->Nombre_estado == "Pago por confirmar"){
                        return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }
               }
            }
   
         }
      }
   }
   
   break;
   case 'Pago por confirmar':
         
         $lugar_cliente_reservado = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
         $servicios = Servicios_estancia::get();
      
         $files = DB::table('fotos_lugares')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $relacion_servicio =  DB::table('relacion_servicios') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_bano= DB::table('servicio_bano') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_cama= DB::table('servicio_cama')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
         
         $reglafiles = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas','reserva.Foto_reglamento','reserva.Foto_aviso_privacidad',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
   
   
   
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
      $lugar_reservacion= DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
               'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();
   
   //llaado de datos para el calendario
   //aqui hago una variable de tipo array sola
      $reservacion = [];
   //hago un foreach con la variable que me hace la consulta a la bd
      foreach($lugar_reservacion as $reserv){
   //ejemplo para cambiar el titulo
         $json = json_decode(
            $reserv->Title
         );
         $titulo = $json[0]->Nombre." ".$json[0]->Numero_celular;
   
   //llamo a la variable que tiene el array solo para llenarlo de datos el formato lo saque del calendario
         $reservacion[] = [
            'title' => $titulo,
            'start' => $reserv->Start_date,
            'end' => $reserv->End_date,
         ];
      }
   //despues ponemos en formato json los datos para que los entienda el calendario y esa variable se la enviamos a la vista con el metodo compact
      response()->json($lugar_reservacion);
   
   
   
      if($habitaciones[0]->Nombre_estado  == "Desocupada"){
         return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($habitaciones[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
            return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($habitaciones[0]->Nombre_estado == "Desactivada"){
               return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
            }else{
               if($habitaciones[0]->Nombre_estado == "Rentada"){
                  return view('Habitaciones.detalleshabocupada', compact('monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto','locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
               }else{
                  if($habitaciones[0]->Nombre_estado == "Reservada"){
                     return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }else{
                     if($habitaciones[0]->Nombre_estado == "Pago por confirmar"){
                        return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }
               }
            }
   
         }
      }
   }
   
   break;
   case 'Rentada':
               
      $consulta_reserva = DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados','lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
          'lugares_reservados.Id_cliente','est.Nombre_estado',
          'hab.Id_habitacion','hab.Id_locacion','hab.Id_estado_ocupacion', 
          'hab.Id_colaborador','hab.Nombre_hab', 'hab.Capacidad_personas',  'hab.Deposito_garantia_hab', 
          'hab.Precio_noche', 'hab.Precio_semana','hab.Precio_catorcedias', 'hab.Precio_mes', 
          'hab.Encargado','hab.Espacio_superficie', 'hab.Nota','hab.Descripcion',
          'hab.Cobro_p_ext_mes_h','hab.Cobro_p_ext_catorcena_h','hab.Cobro_p_ext_noche_h',
          'hab.Cobro_anticipo_mes_h','hab.Cobro_anticipo_catorcena_h','hab.Camas_juntas',
          'loc.Nombre_locacion')
          ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
          ->leftJoin("habitacion as hab", "hab.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
          ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "hab.Id_locacion")
          ->where('hab.Id_habitacion', '=', $Id_habitacion)
          ->get();
   
      $Id_reservacion = $consulta_reserva[0]->Id_reservacion;

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
         //  ->where('reserva.Id_reservacion', '=', $Id_reservacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
          ->where('hab.Id_habitacion', '=', $Id_habitacion)
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
   $monto_por_p_extras = 0;
   //variable que me hace la suma total            
          $suma_monto = $monto_por_dias  + $detallereserva[0]->Deposito_garantia_hab + $detallereserva[0]->Monto_uso_cochera;
   
     // return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservahab', compact('detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto'));
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
   
      //return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservahab', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
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
              
      //return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservahab', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
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
              
      //return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservahab', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
   //return "es $redondeomes mes";
      break;
      }
      
         $lugar_cliente_reservado = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   
         $servicios = Servicios_estancia::get();
      
         $files = DB::table('fotos_lugares')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $relacion_servicio =  DB::table('relacion_servicios') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_bano= DB::table('servicio_bano') 
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
      
         $servicio_cama= DB::table('servicio_cama')
         ->where('Id_habitacion', '=', $Id_habitacion)
         ->get();
         
         $reglafiles = DB::table('lugares_reservados')
         ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
         'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
         'lugares_reservados.Id_estado_ocupacion', 'reserva.Start_date','reserva.Total_de_personas','reserva.Foto_reglamento','reserva.Foto_aviso_privacidad',
         'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
         'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
         'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
         'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
         'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso')
         ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
         ->leftJoin("habitacion", "habitacion.Id_habitacion", "=", "lugares_reservados.Id_habitacion")
         ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
         // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
         // LEFT JOIN habitacion ON habitacion.Id_habitacion = lugares_reservados.Id_habitacion
         // WHERE habitacion.Id_habitacion =  ".$Id_habitacion.") as totalclientes")
         ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
         ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
         ->get();
   

   
   
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
      $lugar_reservacion= DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
               'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();
   
   //llaado de datos para el calendario
   //aqui hago una variable de tipo array sola
      $reservacion = [];
   //hago un foreach con la variable que me hace la consulta a la bd
      foreach($lugar_reservacion as $reserv){
   //ejemplo para cambiar el titulo
         $json = json_decode(
            $reserv->Title
         );
         $titulo = $json[0]->Nombre." ".$json[0]->Numero_celular;
   
   //llamo a la variable que tiene el array solo para llenarlo de datos el formato lo saque del calendario
         $reservacion[] = [
            'title' => $titulo,
            'start' => $reserv->Start_date,
            'end' => $reserv->End_date,
         ];
      }
   //despues ponemos en formato json los datos para que los entienda el calendario y esa variable se la enviamos a la vista con el metodo compact
      response()->json($lugar_reservacion);
   
   
   
      if($habitaciones[0]->Nombre_estado  == "Desocupada"){
         return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($habitaciones[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
            return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($habitaciones[0]->Nombre_estado == "Desactivada"){
               return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
            }else{
               if($habitaciones[0]->Nombre_estado == "Rentada"){
                  return view('Habitaciones.detalleshabocupada', compact('monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto','locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
               }else{
                  if($habitaciones[0]->Nombre_estado == "Reservada"){
                     return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }else{
                     if($habitaciones[0]->Nombre_estado == "Pago por confirmar"){
                        return view('Habitaciones.detalleshablibre', compact('locacion', 'habitaciones','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }
               }
            }
   
         }
      }
   }
   
   break;
   
}

}


//funcion para la vista del formulario de habitaciones 
public function ViewHab($Id_locacion){
//aqui envie la vairable de "idlocacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact
      
//consultas que me traen los registros de la bd
      $estatus_locaciones =  Estado_ocupacion::get();
      $servicios = Servicios_estancia::get();
//aqui mando la variable de "id locacion en el metodo compact, esta variable la pondre en las rutas y en los forms"
      return view('Habitaciones.agregarhab', compact('Id_locacion', 'servicios', 'estatus_locaciones'));
   }


//funcion para guardar un registro de hab 
public function HabStore(Request $request, $Id_locacion, Locacion $lochabstotal){
//aqui envie la vairable de "idlocacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact
//info basica
try{ 

//minifuncion para el bucle de crear las habs      
//hago una consulta a la bd para checar el numero de de habs 
      $lochabstotal=DB::table('locacion')
      ->select('Id_locacion', 'Numero_total_habitaciones','Numero_habs_actuales', 
            'Numero_total_depas', 'Numero_total_locales')
      ->where('Id_locacion', '=', $Id_locacion)
      ->get();
//declaro una variable que tomara el registro de num de habs actuales y le sumara 1 
      $aumentador = $lochabstotal[0]->Numero_habs_actuales + 1;
//hago la actualizacion del dato 
      $affected = DB::table('locacion')
      ->where('Id_locacion', '=', $Id_locacion)
      ->update(['Numero_habs_actuales' => $aumentador]);
//vuelvo a hacer una consulta a la bd
      $lochabstotal=DB::table('locacion')
      ->select('Id_locacion', 'Numero_total_habitaciones','Numero_habs_actuales', 
            'Numero_total_depas', 'Numero_total_locales')
      ->where('Id_locacion', '=', $Id_locacion)
      ->get();

      $agregarhab = new Habitacion();
      $agregarhab-> Id_locacion = $Id_locacion;
      $agregarhab-> Id_estado_ocupacion = $request->get('estatus');
      $agregarhab-> Nombre_hab = $request->get('nombre');
      $agregarhab-> Capacidad_personas = $request->get('cap_personas');
      $agregarhab-> Deposito_garantia_hab = $request->get('garantia');
      $agregarhab-> Precio_noche = $request->get('precio_noche');
      $agregarhab-> Precio_semana = $request->get('precio_semana');
      $agregarhab-> Precio_catorcedias = $request->get('precio_catorcena');
      $agregarhab-> Precio_mes = $request->get('precio_mes');
      $agregarhab-> Espacio_superficie = $request->get('superficie');
      $agregarhab-> Nota = $request->get('nota');
      $agregarhab-> Descripcion = $request->get('descripcion');
      $agregarhab-> Cobro_p_ext_mes_h = $request->get('p_ext_mes');
      $agregarhab-> Cobro_p_ext_catorcena_h = $request->get('p_ext_catorce');
      $agregarhab-> Cobro_p_ext_noche_h = $request->get('p_ext_noche');
      $agregarhab-> Cobro_anticipo_mes_h = $request->get('c_anticipo_mes');
      $agregarhab-> Cobro_anticipo_catorcena_h = $request->get('c_anticipo_catorce');
      $agregarhab-> Camas_juntas = $request->get('camas_juntas');
      $agregarhab->save();

//obtengo el id del ultimo registro guardado
      $idhab =DB::getPdo()->lastInsertId();
//servicios
      foreach ($request->arregloServicios as $arreglo) {
         DB::table('relacion_servicios')->insert(
         ['Id_habitacion' => $agregarhab->Id_habitacion, 'Id_servicios_estancia' => $arreglo]);
      }

//planta
      $plantahab = new Plantas_pisos();
//llamo a la variable que contiene el id y lo guardo en el campo 
      $plantahab-> Id_habitacion  = $idhab;
      $plantahab-> Nombre_planta = $request->get('planta');
      $plantahab->save();

//baños
      $agregarbañohab = new Servicio_bano();
//llamo a la variable que contiene el id y lo guardo en el campo 
      $agregarbañohab-> Id_habitacion = $idhab;
      $agregarbañohab-> Bano_compartido = $request->get('b_compartido');
      $agregarbañohab-> Bano_medio = $request->get('b_medio');
      $agregarbañohab-> Bano_completo = $request->get('b_completo');
      $agregarbañohab-> Bano_completo_RL = $request->get('b_completorl');
      $agregarbañohab->save();
//camas   
      $agregarcamahab = new Servicio_cama();
      $agregarcamahab-> Id_habitacion = $idhab;
      $agregarcamahab-> Cama_individual = $request->get('c_individual');
      $agregarcamahab-> Cama_matrimonial = $request->get('c_matrimonial');
      $agregarcamahab-> Litera_individual = $request->get('c_l_individual');
      $agregarcamahab-> Litera_matrimonial = $request->get('c_l_matrimonial');
      $agregarcamahab-> Litera_ind_mat = $request->get('c_litera_im');
      $agregarcamahab-> Cama_kingsize = $request->get('c_kingsize');
      $agregarcamahab->save();

//fotografias        
//array que guarda la foto 1
      $this->validate($request, array(
      'img1' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img1');
   
      if($image != ''){
         $nombreImagen = $agregarhab->Id_habitacion.'_'.$agregarhab->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen1;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_habitacion' => $agregarhab->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
      }}
      else{ Alert::error('Error', 'LA IMAGEN 1 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}
         
//array que guarda la foto 2
      $this->validate($request, array(
      'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img2');

      if($image != ''){
         $nombreImagen = $agregarhab->Id_habitacion.'_'.$agregarhab->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen2;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_habitacion' => $agregarhab->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
      }}
      else{ Alert::error('Error', 'LA IMAGEN 2 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}
   
//array que guarda la foto 3
      $this->validate($request, array(
      'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img3');

      if($image != ''){
         $nombreImagen = $agregarhab->Id_habitacion.'_'.$agregarhab->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen3;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_habitacion' => $agregarhab->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
      }}
      else{Alert::error('Error', 'LA IMAGEN 3 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}
   
//array que guarda la foto 4
      $this->validate($request, array(
      'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img4');

      if($image != ''){
         $nombreImagen = $agregarhab->Id_habitacion.'_'.$agregarhab->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen4;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_habitacion' => $agregarhab->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
      }}
      else{Alert::error('Error', 'LA IMAGEN 4 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}
   
//array que guarda la foto 5
      $this->validate($request, array(
      'img5' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img5');

      if($image != ''){
         $nombreImagen = $agregarhab->Id_habitacion.'_'.$agregarhab->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen5;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_habitacion' => $agregarhab->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
      }}
      else{Alert::error('Error', 'LA IMAGEN 5 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}
   
//array que guarda la foto 6
      $this->validate($request, array(
      'img6' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img6');

      if($image != ''){
         $nombreImagen = $agregarhab->Id_habitacion.'_'.$agregarhab->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen6;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_habitacion' => $agregarhab->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
      }}
      else{Alert::error('Error', 'LA IMAGEN 6 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}
  

         Alert::success('Exito', 'Se agrego la habitacion con exito. Ya puedes cerrar esta ventana');
         return redirect()->back();
        }catch(Exception $ex){
            Alert::error('Error', 'La habitacion no se pudo agregar. revisa que todo este en orden');
            return redirect()->back();
         }
}

//funcion que lanza la alerta de que no se pueden guardar mas habs en la casa
public function HabsLlenas(){
      Alert::info('Advertencia', 'parece que estamos llenos. si quieres añadir mas habitaciones edita la locacion para tener mayor capacidad de habitaciones');
      return redirect()->back();
}



//funcion para la vista de editar locacion entera
public function ViewEditarHab($Id_habitacion){
      
      $habitaciones = Habitacion::findOrFail($Id_habitacion);

      $estatus_habs =  Estado_ocupacion::get();

      $servicios = Servicios_estancia::get();

      $relacion_servicio =  DB::table('relacion_servicios') 
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();

      $servicio_bano= DB::table('servicio_bano') 
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();

      $servicio_cama= DB::table('servicio_cama')
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();
      
      $fotos = DB::table('fotos_lugares')
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();

      $plantas_pisos = DB::table('plantas_pisos')
      ->where('Id_habitacion', '=', $Id_habitacion)
      ->get();
      

      return view('Habitaciones.editarhab', compact('plantas_pisos','fotos', 'servicio_cama', 'servicio_bano', 'relacion_servicio', 'servicios', 'estatus_habs', 'habitaciones'));        
}




//funcion para actualizar los registros de las habs
public function UpdateHab( Request $request, Habitacion $habitaciones, Servicio_bano $servicio_bano,  Servicio_cama $servicio_cama, Plantas_pisos $plantas_pisos){
      try{  
    
//habitacion   
         $habitaciones -> Nombre_hab = $request->nombre;
         $habitaciones -> Id_estado_ocupacion = $request-> estatus;
         $habitaciones -> Capacidad_personas = $request->cap_personas;
         $habitaciones -> Precio_noche = $request->precio_noche;
         $habitaciones -> Precio_semana = $request->precio_semana;
         $habitaciones -> Precio_catorcedias = $request->precio_catorcena;
         $habitaciones -> Precio_mes = $request->precio_mes;
         $habitaciones -> Deposito_garantia_hab = $request->garantia;
         $habitaciones -> Espacio_superficie = $request->superficie;
         $habitaciones -> Nota = $request->nota;
         $habitaciones -> Descripcion = $request->descripcion;
         $habitaciones -> Cobro_p_ext_mes_h = $request->p_ext_mes;
         $habitaciones -> Cobro_p_ext_catorcena_h = $request->p_ext_catorce;
         $habitaciones -> Cobro_p_ext_noche_h = $request->p_ext_noche;
         $habitaciones -> Cobro_anticipo_mes_h = $request->c_anticipo_mes;
         $habitaciones -> Cobro_anticipo_catorcena_h = $request->c_anticipo_catorce;
         $habitaciones -> Camas_juntas = $request->camas_juntas;
         $habitaciones ->save();
   
//planta
         $plantas_pisos-> Id_habitacion = $habitaciones -> Id_habitacion;
         $plantas_pisos-> Nombre_planta = $request->get('planta');
         $plantas_pisos->save();
         
//servicios
//borra los registros que tienen el id de la hab   
            $borrarservicios = DB::table('relacion_servicios')
            ->where('Id_habitacion', '=', $habitaciones -> Id_habitacion)
            ->delete();
            //hace un nuevo insert para colocar los datos actualizados
            foreach ($request->arregloServicios as $arreglo) {
               DB::table('relacion_servicios')->insert(
               ['Id_habitacion' => $habitaciones -> Id_habitacion, 'Id_servicios_estancia' => $arreglo]);
            }
                  
   
//baños
//llamo a la variable que contiene el id y lo guardo en el campo 
         
         $servicio_bano-> Id_habitacion = $habitaciones -> Id_habitacion;
         $servicio_bano-> Bano_compartido = $request->b_compartido;
         $servicio_bano-> Bano_medio = $request->b_medio;
         $servicio_bano-> Bano_completo = $request->b_completo;
         $servicio_bano-> Bano_completo_RL = $request->b_completorl;
         $servicio_bano->save();
//camas   
         
         $servicio_cama-> Id_habitacion = $habitaciones -> Id_habitacion;
         $servicio_cama-> Cama_individual = $request->c_individual;
         $servicio_cama-> Cama_matrimonial = $request->c_matrimonial;
         $servicio_cama-> Litera_individual = $request->c_l_individual;
         $servicio_cama-> Litera_matrimonial = $request->c_l_matrimonial;
         $servicio_cama-> Litera_ind_mat = $request->c_litera_im;
         $servicio_cama-> Cama_kingsize = $request->c_kingsize;
         $servicio_cama->save();
   
//fotografias        
//array que guarda la foto 1
         $this->validate($request, array(
         'img1' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
         ));
         $image = $request->file('img1');
      
         if($image != ''){
            $nombreImagen = $habitaciones->Id_habitacion.'_'.$habitaciones->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
            $base64Img = $request->nuevaImagen1;
            $base_to_php = explode(',',$base64Img);
            $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
            $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
            $guardarImagen = file_put_contents($filepath, $data);
      
            if ($guardarImagen !== false) {
               DB::table('fotos_lugares')->insert(
               ['Id_habitacion' => $habitaciones->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
         }}
            
//array que guarda la foto 2
         $this->validate($request, array(
         'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
         ));
         $image = $request->file('img2');
   
         if($image != ''){
            $nombreImagen = $habitaciones->Id_habitacion.'_'.$habitaciones->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
            $base64Img = $request->nuevaImagen2;
            $base_to_php = explode(',',$base64Img);
            $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
            $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
            $guardarImagen = file_put_contents($filepath, $data);
   
            if ($guardarImagen !== false) {
               DB::table('fotos_lugares')->insert(
               ['Id_habitacion' => $habitaciones->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
         }}
      
//array que guarda la foto 3
         $this->validate($request, array(
         'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
         ));
         $image = $request->file('img3');
   
         if($image != ''){
            $nombreImagen = $habitaciones->Id_habitacion.'_'.$habitaciones->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
            $base64Img = $request->nuevaImagen3;
            $base_to_php = explode(',',$base64Img);
            $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
            $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
            $guardarImagen = file_put_contents($filepath, $data);
   
            if ($guardarImagen !== false) {
               DB::table('fotos_lugares')->insert(
               ['Id_habitacion' => $habitaciones->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
         }}
      
//array que guarda la foto 4
         $this->validate($request, array(
         'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
         ));
         $image = $request->file('img4');
   
         if($image != ''){
            $nombreImagen = $habitaciones->Id_habitacion.'_'.$habitaciones->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
            $base64Img = $request->nuevaImagen4;
            $base_to_php = explode(',',$base64Img);
            $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
            $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
            $guardarImagen = file_put_contents($filepath, $data);
   
            if ($guardarImagen !== false) {
               DB::table('fotos_lugares')->insert(
               ['Id_habitacion' => $habitaciones->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
         }}
      
//array que guarda la foto 5
         $this->validate($request, array(
         'img5' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
         ));
         $image = $request->file('img5');
   
         if($image != ''){
            $nombreImagen = $habitaciones->Id_habitacion.'_'.$habitaciones->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
            $base64Img = $request->nuevaImagen5;
            $base_to_php = explode(',',$base64Img);
            $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
            $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
            $guardarImagen = file_put_contents($filepath, $data);
   
            if ($guardarImagen !== false) {
               DB::table('fotos_lugares')->insert(
               ['Id_habitacion' => $habitaciones->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
         }}
      
//array que guarda la foto 6
         $this->validate($request, array(
         'img6' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
         ));
         $image = $request->file('img6');
   
         if($image != ''){
            $nombreImagen = $habitaciones->Id_habitacion.'_'.$habitaciones->Nombre_hab.'_'.rand(). '.' . $image->getClientOriginalExtension();
            $base64Img = $request->nuevaImagen6;
            $base_to_php = explode(',',$base64Img);
            $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
            $filepath = 'C:/xampp/htdocs/alohate/public/uploads/habitaciones/'.$nombreImagen;
            $guardarImagen = file_put_contents($filepath, $data);
   
            if ($guardarImagen !== false) {
               DB::table('fotos_lugares')->insert(
               ['Id_habitacion' => $habitaciones->Id_habitacion, 'Ruta_lugar' => $nombreImagen]);
         }}
      
         Alert::success('Exito', 'Se ha actualizado la habitacion con exito');
         return redirect()->back();
      }
      catch(Exception $ex){
         Alert::error('Error', 'La habitacion no se pudo actualizar revisa que todo este en orden');
         return redirect()->back();
      }
   
}
   
//funcion que destruye las imagenes en la pagina de editar activo
public function DestroyImgHab( Request $request, $Id_foto_lugar ) {
                  
            try{
         $habitaciones=DB::table('fotos_lugares')
         ->where('Id_foto_lugar', '=', $Id_foto_lugar )
         ->get();
         //se hace un json encode y decode para transformar los datos en json 
         $habitaciones = json_decode(json_encode($habitaciones));
                  
         foreach($habitaciones as $habitacion)
         {
            if(!empty($habitacion->Ruta_lugar)){
               File::delete('C:\xampp\htdocs\alohate\public\uploads\habitaciones'.$habitacion->Ruta_lugar);}           
         }
                  
         $deleted = DB::table('fotos_lugares')->where('Id_foto_lugar', '=', $Id_foto_lugar)->delete();
        
         Alert::success('Exito', 'Se ha eliminado la imagen con exito');
         return redirect()->back();
      }
      catch(Exception $ex){
         Alert::error('Error', 'No se pudo eliminar la imagen');
         return redirect()->back();
      }
}


//funcion para la vista de eliminar locacion
public function ViewDesactHab($Id_habitacion ){

      $habitacion = DB::table('habitacion')
      ->select('habitacion.Id_habitacion','est.Nombre_estado', 'Id_colaborador',
          'Nombre_hab','Capacidad_personas', 'Deposito_garantia_hab', 'Precio_noche', 'Precio_semana',
          'Precio_catorcedias', 'Precio_mes', 'Encargado', 'Espacio_superficie', 'Nota')
  //joins que me vinculan a otras tablas para hacer consultas 
     ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "habitacion.Id_estado_ocupacion")
     ->where('habitacion.Id_habitacion', '=', $Id_habitacion)
     ->get();
      
      return view('Habitaciones.desactivarhab', compact('habitacion'));
   }


public function DesactivarHab( $Id_habitacion){
      try{

            $habitacion = DB::table('habitacion')
            ->where('Id_habitacion', '=', $Id_habitacion )
            ->update(['Id_estado_ocupacion' => 3]);

      Alert::success('Exito', 'Se ha desactivado la habitacion con exito');
      return redirect()->back();

      }catch(Exception $ex){
      Alert::error('Error', 'La habitacion no se pudo desactivar revisa que todo este en orden');
      return redirect()->back();
   }  
      
}
         
 

}

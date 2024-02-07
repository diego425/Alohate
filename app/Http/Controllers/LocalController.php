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
use App\Models\Habitacion;
use App\Models\Local;
use App\Models\Departamento;
use App\Models\Servicios_estancia;
use App\Models\Fotos_lugares;
use App\Models\Relacion_servicios;

use function Laravel\Prompts\alert;
use Exception;
class LocalController extends Controller
{

public function ViewLocalesLoc($Id_locacion, Request $request){

   $estatus_loc =  Estado_ocupacion::get();
//variables para el buscador
//variables para el buscador   
            
        
//consulta padre que me trae los registros de las habitaciones
   $locales = DB::table('local')
   ->select('local.Id_local', 'Id_colaborador','est.Nombre_estado', 'local.Id_locacion','Nombre_local',
   'Precio_renta','Espacio_superficie', 'Encargado','Nota','Descripcion', 'Deposito_garantia_local' ,'planta.Nombre_planta',
   'bano.Bano_compartido','bano.Bano_medio', 'bano.Bano_completo', 'bano.Bano_completo_RL')
        //joins que me vinculan a otras tablas para hacer consultas 
   ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "local.Id_estado_ocupacion")
   ->leftJoin("servicio_bano as bano", "bano.Id_local", "=", "local.Id_local")
   ->leftJoin("plantas_pisos as planta", "planta.Id_local", "=", "local.Id_local")
        
//consulta crudas de sql que me cuenta los registros que esten en las plantas para poder mostrarlos en la vista 
//planta baja
   ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `local` 
   LEFT JOIN plantas_pisos AS piso ON piso.Id_local = local.Id_local
   WHERE piso.Nombre_planta = 'Planta baja'
   AND local.Id_locacion =  ".$Id_locacion.") as totallocpisobajo")
//piso 1
   ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `local` 
   LEFT JOIN plantas_pisos AS piso ON piso.Id_local = local.Id_local
   WHERE piso.Nombre_planta = 'Planta 1'
   AND local.Id_locacion =  ".$Id_locacion.") as totallocpiso1")
//piso 2
   ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `local` 
   LEFT JOIN plantas_pisos AS piso ON piso.Id_local = local.Id_local
   WHERE piso.Nombre_planta = 'Planta 2'
   AND local.Id_locacion =  ".$Id_locacion.") as totallocpiso2")
//piso 3
   ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `local` 
   LEFT JOIN plantas_pisos AS piso ON piso.Id_local = local.Id_local
   WHERE piso.Nombre_planta = 'Planta 3'
   AND local.Id_locacion =  ".$Id_locacion.") as totallocpiso3")
//piso 4
   ->selectRaw("(SELECT COUNT(*) AS piso4 FROM `local` 
   LEFT JOIN plantas_pisos AS piso ON piso.Id_local = local.Id_local
   WHERE piso.Nombre_planta = 'Planta 4'
   AND local.Id_locacion =  ".$Id_locacion.") as totallocpiso4")
//piso 5
   ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `local` 
   LEFT JOIN plantas_pisos AS piso ON piso.Id_local = local.Id_local
   WHERE piso.Nombre_planta = 'Planta 5'
   AND local.Id_locacion =  ".$Id_locacion.") as totallocpiso5")
//piso 6
   ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `local` 
   LEFT JOIN plantas_pisos AS piso ON piso.Id_local = local.Id_local
   WHERE piso.Nombre_planta = 'Planta 6'
   AND local.Id_locacion =  ".$Id_locacion.") as totallocpiso6")
        
   ->where('local.Id_locacion', '=', $Id_locacion)
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
        
     
return view('Locales.locales', compact('locacion', 'locales', 'estatus_loc'));

}

//funcion que lanza la alerta de que no se pueden guardar mas habs en la casa
public function LocsLlenos(){
    Alert::info('Advertencia', 'parece que estamos llenos. si quieres añadir mas locales edita la locacion para tener mayor capacidad de locales');
return redirect()->back();
}



//funcion para la vista del formulario de los locales
public function ViewLocal($Id_locacion){
//aqui envie la vairable de "Id_locacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact
    
//consultas que me traen los registros de la bd
      $estatus_locaciones =  Estado_ocupacion::get();
      $servicios = Servicios_estancia::get();
//aqui mando la variable de "id locacion en el metodo compact, esta variable la pondre en las rutas y en los forms"
          return view('Locales.agregarlocal', compact('Id_locacion', 'estatus_locaciones', 'servicios'));
}
    
    
//funcion para guardar el registro de los depas y vincula a la intro de los locales
public function LocStore(Request $request, $Id_locacion, Locacion $loclocstotal){
try{
//aqui envie la vairable de "Id_locacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact
//minifuncion para el bucle de crear los depas     
//hago una consulta a la bd para checar el numero de de habs 
        $loclocstotal=DB::table('locacion')
        ->select('Id_locacion', 'Numero_locs_actuales' ,'Numero_total_locales')
        ->where('Id_locacion', '=', $Id_locacion)
        ->get();
//declaro una variable que tomara el registro de num de habs actuales y le sumara 1 
        $aumentador = $loclocstotal[0]->Numero_locs_actuales + 1;
//hago la actualizacion del dato 
        $affected = DB::table('locacion')
        ->where('Id_locacion', '=', $Id_locacion)
        ->update(['Numero_locs_actuales' => $aumentador]);
//vuelvo a hacer una consulta a la bd
        $loclocstotal=DB::table('locacion')
        ->select('Id_locacion', 'Numero_locs_actuales' ,'Numero_total_locales')
        ->where('Id_locacion', '=', $Id_locacion)
        ->get();
//info basica
             
       $agregarloc = new Local();
       $agregarloc-> Id_locacion = $Id_locacion;
       $agregarloc-> Id_estado_ocupacion = $request->get('estatus');
       $agregarloc-> Nombre_local = $request->get('nombre');
       $agregarloc-> Precio_renta = $request->get('renta');
       $agregarloc-> Espacio_superficie = $request->get('superficie');
       $agregarloc-> Nota = $request->get('nota');
       $agregarloc-> Descripcion = $request->get('descripcion');
       $agregarloc-> Deposito_garantia_local = $request->get('garantia');
       $agregarloc->save();
    
//obtengo el id del ultimo registro guardado
       $idloc =DB::getPdo()->lastInsertId();
//servicios
       foreach ($request->arregloServicios as $arreglo) {
          DB::table('relacion_servicios')->insert(
          ['Id_local' => $agregarloc->Id_local, 'Id_servicios_estancia' => $arreglo]);
       }
    
//planta
       $plantaloc = new Plantas_pisos();
//llamo a la variable que contiene el id y lo guardo en el campo 
       $plantaloc-> Id_local  = $idloc;
       $plantaloc-> Nombre_planta = $request->get('planta');
       $plantaloc->save();
    
//baños
       $agregarbañoloc = new Servicio_bano();
//llamo a la variable que contiene el id y lo guardo en el campo 
       $agregarbañoloc-> Id_local = $idloc;
       $agregarbañoloc-> Bano_compartido = $request->get('b_compartido');
       $agregarbañoloc-> Bano_medio = $request->get('b_medio');
       $agregarbañoloc-> Bano_completo = $request->get('b_completo');
       $agregarbañoloc-> Bano_completo_RL = $request->get('b_completorl');
       $agregarbañoloc->save();
    
//fotografias        
//array que guarda la foto 1
       $this->validate($request, array(
       'img1' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
       ));
       $image = $request->file('img1');
    
       if($image != ''){
          $nombreImagen = $agregarloc->Id_local.'_'.$agregarloc->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
          $base64Img = $request->nuevaImagen1;
          $base_to_php = explode(',',$base64Img);
          $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
          $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
          $guardarImagen = file_put_contents($filepath, $data);
    
          if ($guardarImagen !== false) {
             DB::table('fotos_lugares')->insert(
             ['Id_local' => $agregarloc->Id_local, 'Ruta_lugar' => $nombreImagen]);
       }}
       else{Alert::error('Error', 'LA IMAGEN 1 NO ES ADMITIDA, CAMBIALA');
          return redirect()->back();}
      
//array que guarda la foto 2
       $this->validate($request, array(
       'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
       ));
       $image = $request->file('img2');
    
       if($image != ''){
          $nombreImagen = $agregarloc->Id_local.'_'.$agregarloc->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
          $base64Img = $request->nuevaImagen2;
          $base_to_php = explode(',',$base64Img);
          $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
          $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
          $guardarImagen = file_put_contents($filepath, $data);
    
          if ($guardarImagen !== false) {
             DB::table('fotos_lugares')->insert(
             ['Id_local' => $agregarloc->Id_local, 'Ruta_lugar' => $nombreImagen]);
       }}
       else{Alert::error('Error', 'LA IMAGEN 2 NO ES ADMITIDA, CAMBIALA');
          return redirect()->back();}
    
//array que guarda la foto 3
       $this->validate($request, array(
       'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
       ));
       $image = $request->file('img3');
    
       if($image != ''){
          $nombreImagen = $agregarloc->Id_local.'_'.$agregarloc->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
          $base64Img = $request->nuevaImagen3;
          $base_to_php = explode(',',$base64Img);
          $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
          $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
          $guardarImagen = file_put_contents($filepath, $data);
    
          if ($guardarImagen !== false) {
             DB::table('fotos_lugares')->insert(
             ['Id_local' => $agregarloc->Id_local, 'Ruta_lugar' => $nombreImagen]);
       }}
       else{Alert::error('Error', 'LA IMAGEN 3 NO ES ADMITIDA, CAMBIALA');
          return redirect()->back();}
    
//array que guarda la foto 4
       $this->validate($request, array(
       'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
       ));
       $image = $request->file('img4');
    
       if($image != ''){
          $nombreImagen = $agregarloc->Id_local.'_'.$agregarloc->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
          $base64Img = $request->nuevaImagen4;
          $base_to_php = explode(',',$base64Img);
          $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
          $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
          $guardarImagen = file_put_contents($filepath, $data);
    
          if ($guardarImagen !== false) {
             DB::table('fotos_lugares')->insert(
             ['Id_local' => $agregarloc->Id_local, 'Ruta_lugar' => $nombreImagen]);
       }}
       else{Alert::error('Error', 'LA IMAGEN 4 NO ES ADMITIDA, CAMBIALA');
          return redirect()->back();}
    
//array que guarda la foto 5
       $this->validate($request, array(
       'img5' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
       ));
       $image = $request->file('img5');
    
       if($image != ''){
          $nombreImagen = $agregarloc->Id_local.'_'.$agregarloc->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
          $base64Img = $request->nuevaImagen5;
          $base_to_php = explode(',',$base64Img);
          $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
          $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
          $guardarImagen = file_put_contents($filepath, $data);
    
          if ($guardarImagen !== false) {
             DB::table('fotos_lugares')->insert(
             ['Id_local' => $agregarloc->Id_local, 'Ruta_lugar' => $nombreImagen]);
       }}
       else{Alert::error('Error', 'LA IMAGEN 5 NO ES ADMITIDA, CAMBIALA');
          return redirect()->back();}
    
//array que guarda la foto 6
       $this->validate($request, array(
       'img6' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
       ));
       $image = $request->file('img6');
    
       if($image != ''){
          $nombreImagen = $agregarloc->Id_local.'_'.$agregarloc->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
          $base64Img = $request->nuevaImagen6;
          $base_to_php = explode(',',$base64Img);
          $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
          $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
          $guardarImagen = file_put_contents($filepath, $data);
    
          if ($guardarImagen !== false) {
             DB::table('fotos_lugares')->insert(
             ['Id_local' => $agregarloc->Id_local, 'Ruta_lugar' => $nombreImagen]);
       }}
       else{Alert::error('Error', 'LA IMAGEN 6 NO ES ADMITIDA, CAMBIALA');
          return redirect()->back();}
    
    
    
    Alert::success('Exito', 'Se agrego el local con exito. Ya puedes cerrar esta ventana');
        return redirect()->back();
    }catch(Exception $ex){
        Alert::error('Error', 'El local no se pudo agregar. revisa que todo este en orden');
        return redirect()->back();
    }

}



//funcion para la vista de eliminar locacion
public function ViewDesactLocal($Id_local){

    $local = DB::table('local')
    ->select('local.Id_local', 'Id_colaborador','est.Nombre_estado', 'local.Id_locacion','Nombre_local',
    'Precio_renta','Espacio_superficie', 'Encargado','Nota','Descripcion')
 //joins que me vinculan a otras tablas para hacer consultas 
   ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "local.Id_estado_ocupacion")
   ->where('local.Id_local', '=', $Id_local)
   ->get();
    
    return view('Locales.desactivarlocal', compact('local'));
 }
 
 
 public function DesactivarLocal($Id_local){
    try{
 
          $local = DB::table('local')
          ->where('Id_local', '=', $Id_local )
          ->update(['Id_estado_ocupacion' => 3]);
 
    Alert::success('Exito', 'Se ha desactivado el local con exito');
    return redirect()->back();
 
    }catch(Exception $ex){
    Alert::error('Error', 'El local no se pudo desactivar revisa que todo este en orden');
    return redirect()->back();
 }  
    
 }
 


//funcion que muestra los detalles de una habitacion libre
public function DetallesLocalLibre($Id_locacion, $Id_local){

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
 
    $locales = DB::table('local')
     ->select('local.Id_local', 'Id_colaborador','est.Nombre_estado', 'local.Id_locacion','Nombre_local',
     'Precio_renta','Espacio_superficie', 'Encargado','Nota','Descripcion', 'Deposito_garantia_local' ,'planta.Nombre_planta',
     'bano.Bano_compartido','bano.Bano_medio', 'bano.Bano_completo', 'bano.Bano_completo_RL')
 //joins que me vinculan a otras tablas para hacer consultas 
     ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "local.Id_estado_ocupacion")
     ->leftJoin("servicio_bano as bano", "bano.Id_local", "=", "local.Id_local")
     ->leftJoin("plantas_pisos as planta", "planta.Id_local", "=", "local.Id_local")
    ->where('local.Id_local', '=', $Id_local)
    ->get();
 
switch($locales[0]->Nombre_estado){
   case 'Desocupada':

   $lugar_cliente_reservado = DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
      'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_local','lugares_reservados.Id_cliente',
      'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
      'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
      'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
      'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
      'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
      )
      ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
   //->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   //LEFT JOIN local ON local.Id_local = lugares_reservados.Id_local
   //WHERE local.Id_local =  ".$Id_local.") as totalclientes")
   ->where('local.Id_local', '=', $Id_local)
   ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
   ->get();
 
 
    $servicios = Servicios_estancia::get();
 
    $files = DB::table('fotos_lugares')
    ->where('Id_local', '=', $Id_local)
    ->get();
 
    $relacion_servicio =  DB::table('relacion_servicios') 
    ->where('Id_local', '=', $Id_local)
    ->get();
 
    $servicio_bano= DB::table('servicio_bano') 
    ->where('Id_local', '=', $Id_local)
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
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
    // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
    // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
    // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
    ->where('local.Id_local', '=', $Id_local)
    ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
    ->get();
 
 
 
   
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
   $lugar_reservacion= DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
            'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->where('Id_local', '=', $Id_local)
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
 
 
    if($locales[0]->Nombre_estado  == "Desocupada"){
       return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
    }else{
       if($locales[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
          return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
       }else{
          if($locales[0]->Nombre_estado == "Desactivada"){
             return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
          }else{
             if($locales[0]->Nombre_estado == "Rentada"){
                return view('Locales.detalleslocalocupado', compact('locacion', 'locales','monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
             }else{
                if($locales[0]->Nombre_estado == "Reservada"){
                   return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                }
             }
          }
 
       }
    }

   break;
   case 'En Mantenimiento/Limpieza':

   $lugar_cliente_reservado = DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
      'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_local','lugares_reservados.Id_cliente',
      'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
      'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
      'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
      'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
      'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
      )
   ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
   //->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   //LEFT JOIN local ON local.Id_local = lugares_reservados.Id_local
   //WHERE local.Id_local =  ".$Id_local.") as totalclientes")
   ->where('local.Id_local', '=', $Id_local)
   ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
   ->get();
 
 
    $servicios = Servicios_estancia::get();
 
    $files = DB::table('fotos_lugares')
    ->where('Id_local', '=', $Id_local)
    ->get();
 
    $relacion_servicio =  DB::table('relacion_servicios') 
    ->where('Id_local', '=', $Id_local)
    ->get();
 
    $servicio_bano= DB::table('servicio_bano') 
    ->where('Id_local', '=', $Id_local)
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
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
    // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
    // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
    // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
    ->where('local.Id_local', '=', $Id_local)
    ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
    ->get();
 
 
 
 
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
   $lugar_reservacion= DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
            'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->where('Id_local', '=', $Id_local)
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
 
 
    if($locales[0]->Nombre_estado  == "Desocupada"){
       return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
    }else{
       if($locales[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
          return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
       }else{
          if($locales[0]->Nombre_estado == "Desactivada"){
             return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
          }else{
             if($locales[0]->Nombre_estado == "Rentada"){
                return view('Locales.detalleslocalocupado', compact('locacion', 'locales','monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
             }else{
                if($locales[0]->Nombre_estado == "Reservada"){
                   return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                }
             }
          }
 
       }
    }

   break;
   case 'Desactivada':

   $lugar_cliente_reservado = DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
   'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_local','lugares_reservados.Id_cliente',
   'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
   'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
   'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
   'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
   'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
   )
   ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
   //->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   //LEFT JOIN local ON local.Id_local = lugares_reservados.Id_local
   //WHERE local.Id_local =  ".$Id_local.") as totalclientes")
   ->where('local.Id_local', '=', $Id_local)
   ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
   ->get();
 
 
    $servicios = Servicios_estancia::get();
 
    $files = DB::table('fotos_lugares')
    ->where('Id_local', '=', $Id_local)
    ->get();
 
    $relacion_servicio =  DB::table('relacion_servicios') 
    ->where('Id_local', '=', $Id_local)
    ->get();
 
    $servicio_bano= DB::table('servicio_bano') 
    ->where('Id_local', '=', $Id_local)
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
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
    //->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
    //LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
    //WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
    ->where('local.Id_local', '=', $Id_local)
    ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
    ->get();
 
 
 
 
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
   $lugar_reservacion= DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
            'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->where('Id_local', '=', $Id_local)
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
 
 
    if($locales[0]->Nombre_estado  == "Desocupada"){
       return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
    }else{
       if($locales[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
          return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
       }else{
          if($locales[0]->Nombre_estado == "Desactivada"){
             return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
          }else{
             if($locales[0]->Nombre_estado == "Rentada"){
                return view('Locales.detalleslocalocupado', compact('locacion', 'locales','monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
             }else{
                if($locales[0]->Nombre_estado == "Reservada"){
                   return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                }
             }
          }
 
       }
    }

   break;
   case 'Reservada': 

   $lugar_cliente_reservado = DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
   'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_local','lugares_reservados.Id_cliente',
   'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
   'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
   'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
   'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
   'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
   )
   ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
   //->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   //LEFT JOIN local ON local.Id_local = lugares_reservados.Id_local
   //WHERE local.Id_local =  ".$Id_local.") as totalclientes")
   ->where('local.Id_local', '=', $Id_local)
   ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
   ->get();
 
 
    $servicios = Servicios_estancia::get();
 
    $files = DB::table('fotos_lugares')
    ->where('Id_local', '=', $Id_local)
    ->get();
 
    $relacion_servicio =  DB::table('relacion_servicios') 
    ->where('Id_local', '=', $Id_local)
    ->get();
 
    $servicio_bano= DB::table('servicio_bano') 
    ->where('Id_local', '=', $Id_local)
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
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
    // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
    // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
    // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
    ->where('local.Id_local', '=', $Id_local)
    ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
    ->get();
 
 
 
 
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
   $lugar_reservacion= DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
            'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->where('Id_local', '=', $Id_local)
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
 
 
    if($locales[0]->Nombre_estado  == "Desocupada"){
       return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
    }else{
       if($locales[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
          return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
       }else{
          if($locales[0]->Nombre_estado == "Desactivada"){
             return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
          }else{
             if($locales[0]->Nombre_estado == "Rentada"){
                return view('Locales.detalleslocalocupado', compact('locacion', 'locales','monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
             }else{
                if($locales[0]->Nombre_estado == "Reservada"){
                   return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                }
             }
          }
 
       }
    }

   break;
   case 'Pago por confirmar':  

   $lugar_cliente_reservado = DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
   'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_local','lugares_reservados.Id_cliente',
   'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
   'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
   'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
   'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
   'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
   )
   ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
   //->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   //LEFT JOIN local ON local.Id_local = lugares_reservados.Id_local
   //WHERE local.Id_local =  ".$Id_local.") as totalclientes")
   ->where('local.Id_local', '=', $Id_local)
   ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
   ->get();
 
 
    $servicios = Servicios_estancia::get();
 
    $files = DB::table('fotos_lugares')
    ->where('Id_local', '=', $Id_local)
    ->get();
 
    $relacion_servicio =  DB::table('relacion_servicios') 
    ->where('Id_local', '=', $Id_local)
    ->get();
 
    $servicio_bano= DB::table('servicio_bano') 
    ->where('Id_local', '=', $Id_local)
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
    ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
    ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
    // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
    // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
    // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
    ->where('local.Id_local', '=', $Id_local)
    ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
    ->get();
 
 
 
 
   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
   $lugar_reservacion= DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
            'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->where('Id_local', '=', $Id_local)
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
   
 
    if($locales[0]->Nombre_estado  == "Desocupada"){
       return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
    }else{
       if($locales[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
          return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
       }else{
          if($locales[0]->Nombre_estado == "Desactivada"){
             return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
          }else{
             if($locales[0]->Nombre_estado == "Rentada"){
                return view('Locales.detalleslocalocupado', compact('locacion', 'locales','monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
             }else{
                if($locales[0]->Nombre_estado == "Reservada"){
                   return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
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
      'local.Id_local', 'local.Id_colaborador','local.Id_estado_ocupacion',
      'local.Id_locacion','local.Nombre_local', 'local.Precio_renta','local.Espacio_superficie', 
      'local.Encargado','local.Nota','local.Descripcion', 'local.Deposito_garantia_local',
      'loc.Nombre_locacion')
      ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
      ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "local.Id_locacion")
      ->where('local.Id_local', '=', $Id_local)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();

      $Id_reservacion = $consulta_reserva[0]->Id_reservacion;


      $consulta_renta = DB::table('cobro_renta')
      ->select('Id_cobro_renta','cobro_renta.Id_reservacion',
      'Id_lugares_reservados','cobro_renta.Id_colaborador','Cobro_persona_extra',
      'Periodo_total','Estatus_cobro','Tiempo_rebasado',
      'Deposito_garantia','Monto_total','Saldo',
      'reserva.Id_reservacion','reserva.Id_colaborador',
      'reserva.Start_date','reserva.End_date', 'reserva.Title','reserva.Fecha_reservacion',
      'reserva.Numero_personas_extras', 'reserva.Foto_comprobante_anticipo', 'reserva.Fecha_pago_anticipo',
      'reserva.Foto_aviso_privacidad', 'reserva.Foto_reglamento','reserva.Monto_uso_cochera', 
      'reserva.Metodo_pago_anticipo','reserva.Espacios_cochera','reserva.Monto_pagado_anticipo',
      'reserva.Tipo_de_cobro')
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "cobro_renta.Id_reservacion")
      ->where('reserva.Id_reservacion', '=', $Id_reservacion)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();

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
      'local.Id_local', 'local.Id_colaborador','local.Id_estado_ocupacion',
      'local.Id_locacion','local.Nombre_local', 'local.Precio_renta','local.Espacio_superficie', 
      'local.Encargado','local.Nota','local.Descripcion', 'local.Deposito_garantia_local',
      'loc.Nombre_locacion')
      ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
      ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
      ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "local.Id_locacion")
      ->where('local.Id_local', '=', $Id_local)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();







      $lugar_cliente_reservado = DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
      'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_local','lugares_reservados.Id_cliente',
      'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
      'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
      'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
      'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
      'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
      ,'reserva.Start_date','reserva.Total_de_personas',
      )
      ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
      //->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
      //LEFT JOIN local ON local.Id_local = lugares_reservados.Id_local
      //WHERE local.Id_local =  ".$Id_local.") as totalclientes")
      ->where('local.Id_local', '=', $Id_local)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();
   
   
      $servicios = Servicios_estancia::get();
   
      $files = DB::table('fotos_lugares')
      ->where('Id_local', '=', $Id_local)
      ->get();
   
      $relacion_servicio =  DB::table('relacion_servicios') 
      ->where('Id_local', '=', $Id_local)
      ->get();
   
      $servicio_bano= DB::table('servicio_bano') 
      ->where('Id_local', '=', $Id_local)
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
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("local", "local.Id_local", "=", "lugares_reservados.Id_local")
      // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
      // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
      // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
      ->where('local.Id_local', '=', $Id_local)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();
   
   
      $contratofiles = DB::table('contratos')
      ->select('Id_contrato','Id_fiador',
      'contratos.Id_reservacion','Fecha_inicio',
      'Fecha_termino', 'Foto_contrato','Tipo_contrato',)
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "contratos.Id_reservacion")
      //->where('reserva.Id_reservacion', '=', $Id_reservacion)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();
   
      /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
      para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
      $lugar_reservacion= DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
               'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->where('Id_local', '=', $Id_local)
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
   
   
      if($locales[0]->Nombre_estado  == "Desocupada"){
         return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($locales[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
            return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($locales[0]->Nombre_estado == "Desactivada"){
               return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
            }else{
               if($locales[0]->Nombre_estado == "Rentada"){
                  return view('Locales.detalleslocalocupado', compact('locacion', 'locales', 'servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles', 'consulta_renta', 'detallereserva', 'contratofiles'));
               }else{
                  if($locales[0]->Nombre_estado == "Reservada"){
                     return view('Locales.detalleslocallibre', compact('locacion', 'locales','servicios','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }
               }
            }
   
         }
      }

   break;

}

}
 
 
 
//funcion para la vista de editar locacion entera
public function ViewEditarLocal($Id_local){
      
   $locales = Local::findOrFail($Id_local);
   

   $estatus_locales =  Estado_ocupacion::get();

   $servicios = Servicios_estancia::get();

   $relacion_servicio =  DB::table('relacion_servicios') 
   ->where('Id_local', '=', $Id_local)
   ->get();

   $servicio_bano= DB::table('servicio_bano') 
   ->where('Id_local', '=', $Id_local)
   ->get();

   $fotos = DB::table('fotos_lugares')
   ->where('Id_local', '=', $Id_local)
   ->get();

   $plantas_pisos = DB::table('plantas_pisos')
   ->where('Id_local', '=', $Id_local)
   ->get();
   

   return view('Locales.editarlocal', compact('plantas_pisos','fotos', 'servicio_bano', 'relacion_servicio', 'servicios', 'estatus_locales', 'locales'));        
}



//funcion para guardar un registro de depas 
public function UpdateLocal(Request $request, Local $locales, Servicio_bano $servicio_bano,  Plantas_pisos $plantas_pisos){
   try{ 
     
   //info basica
   
      $locales-> Id_estado_ocupacion = $request->estatus;
      $locales-> Nombre_local = $request->nombre;
      $locales-> Precio_renta = $request->renta;
      $locales-> Espacio_superficie = $request->superficie;
      $locales-> Nota = $request->nota;
      $locales-> Descripcion = $request->descripcion;
      $locales-> Deposito_garantia_local = $request->garantia;
      $locales->save();
   

      //planta
      $plantas_pisos-> Id_local  = $locales -> Id_local;
      $plantas_pisos-> Nombre_planta = $request->get('planta');
      $plantas_pisos->save();

      //servicios
      //borra los registros que tienen el id de la hab   
         $borrarservicios = DB::table('relacion_servicios')
         ->where('Id_local', '=', $locales -> Id_local)
         ->delete();
         //hace un nuevo insert para colocar los datos actualizados
         foreach ($request->arregloServicios as $arreglo) {
            DB::table('relacion_servicios')->insert(
            ['Id_local' => $locales -> Id_local, 'Id_servicios_estancia' => $arreglo]);
         }
               

      //baños
      //llamo a la variable que contiene el id y lo guardo en el campo 

      $servicio_bano-> Id_local = $locales -> Id_local;
      $servicio_bano-> Bano_compartido = $request->b_compartido;
      $servicio_bano-> Bano_medio = $request->b_medio;
      $servicio_bano-> Bano_completo = $request->b_completo;
      $servicio_bano-> Bano_completo_RL = $request->b_completorl;
      $servicio_bano->save();

   
   //fotografias        
   //array que guarda la foto 1
      $this->validate($request, array(
      'img1' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img1');
   
      if($image != ''){
         $nombreImagen = $locales->Id_local.'_'.$locales->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen1;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_local' => $locales->Id_local, 'Ruta_lugar' => $nombreImagen]);
      }}
      
         
   //array que guarda la foto 2
      $this->validate($request, array(
      'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img2');
   
      if($image != ''){
         $nombreImagen = $locales->Id_local.'_'.$locales->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen2;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_local' => $locales->Id_local, 'Ruta_lugar' => $nombreImagen]);
      }}
      
   
   //array que guarda la foto 3
      $this->validate($request, array(
      'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img3');
   
      if($image != ''){
         $nombreImagen = $locales->Id_local.'_'.$locales->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen3;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_local' => $locales->Id_local, 'Ruta_lugar' => $nombreImagen]);
      }}
      
   
   //array que guarda la foto 4
      $this->validate($request, array(
      'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img4');
   
      if($image != ''){
         $nombreImagen = $locales->Id_local.'_'.$locales->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen4;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_local' => $locales->Id_local, 'Ruta_lugar' => $nombreImagen]);
      }}
      
   
   //array que guarda la foto 5
      $this->validate($request, array(
      'img5' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img5');
   
      if($image != ''){
         $nombreImagen = $locales->Id_local.'_'.$locales->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen5;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_local' => $locales->Id_local, 'Ruta_lugar' => $nombreImagen]);
      }}
      
   
   //array que guarda la foto 6
      $this->validate($request, array(
      'img6' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img6');
   
      if($image != ''){
         $nombreImagen = $locales->Id_local.'_'.$locales->Nombre_local.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen6;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locales/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_local' => $locales->Id_local, 'Ruta_lugar' => $nombreImagen]);
      }}
      
       Alert::success('Exito', 'Se actualizo el local con exito. Ya puedes cerrar esta ventana');
       return redirect()->back();
   }catch(Exception $ex){
       Alert::error('Error', 'El local no se pudo actualizar. revisa que todo este en orden');
       return redirect()->back();
       }
   }
   
   

//funcion que destruye las imagenes en la pagina de editar activo
public function DestroyImglocal( Request $request, $Id_foto_lugar ) {
   try{
$locales=DB::table('fotos_lugares')
->where('Id_foto_lugar', '=', $Id_foto_lugar )
->get();
//se hace un json encode y decode para transformar los datos en json 
$locales = json_decode(json_encode($locales));
         
foreach($locales as $local)
{
   if(!empty($local->Ruta_lugar)){
      File::delete('C:\xampp\htdocs\alohate\public\uploads\locales'.$local->Ruta_lugar);}           
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

}

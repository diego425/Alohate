<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Query\Builder;

use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Locacion;
use App\Models\Reservacion;
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

class LocacionController extends Controller
{


//funciones generales que se usan tanto en locaciones enteras como por secciones    
//funcion para la vista de locaciones
public function VistaLocaciones(Request $request)
   {

//consultas que me traen los registros de la bd de las locaciones
   $estatus_locaciones =  Estado_ocupacion::get();
   $casas = Locacion::get();

//variables para el buscador   
      $nickname =trim($request->get('nickname'));
      $tiporenta =trim($request->get('tiporenta'));
      $colonia =trim($request->get('colonia'));
      $estatus =trim($request->get('estatus'));

   $locaciones=DB::table('locacion')
   ->select('locacion.Id_locacion', 'Nombre_locacion', 'est.Nombre_estado' ,
   'Tipo_renta', 'Calle','Numero_ext', 'Colonia', 
   'Ubi_google_maps', 'Numero_total_de_pisos','Numero_total_habitaciones', 
   'Numero_total_depas', 'Numero_total_locales','Capacidad_personas', 
   'Precio_noche', 'Precio_semana','Precio_catorcedias',
   'Precio_mes', 'Deposito_garantia_casa', 'Uso_cocheras', 
   'Total_cocheras','Encargado','Espacio_superficie',
   'Zona_ciudad', 'Numero_habs_actuales', 'Numero_depas_actuales', 
   'Numero_locs_actuales','Nota','Descripcion',
   'Cobro_p_ext_mes_c','Cobro_p_ext_catorcena_c',
   'Cobro_p_ext_noche_c','Cobro_anticipo_mes_c','Cobro_anticipo_catorcena_c',
   'Camas_juntas')
   -> leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "locacion.Id_estado_ocupacion")

   //consulta que trae las habitaciones desocupadas
   ->selectRaw("(SELECT COUNT(*) AS totalhab FROM `habitacion` 
   LEFT JOIN estado_ocupacion AS estatus ON estatus.Id_estado_ocupacion = habitacion.Id_estado_ocupacion
   WHERE estatus.Nombre_estado = 'Desocupada'
   AND habitacion.Id_locacion = locacion.Id_locacion) as totalhabslibres")
//consulta que trae las habitaciones ocupadas
   ->selectRaw("(SELECT COUNT(*) AS totalhabitacion FROM `habitacion` 
   LEFT JOIN estado_ocupacion AS estatus ON estatus.Id_estado_ocupacion = habitacion.Id_estado_ocupacion
   WHERE estatus.Nombre_estado = 'Rentada'
   AND habitacion.Id_locacion = locacion.Id_locacion) as totalhabsocupadas")
//consulta que trae las habitaciones reservadas
   ->selectRaw("(SELECT COUNT(*) AS habitacionreserva FROM `habitacion` 
   LEFT JOIN estado_ocupacion AS estatus ON estatus.Id_estado_ocupacion = habitacion.Id_estado_ocupacion
   WHERE estatus.Nombre_estado = 'Reservada'
   AND habitacion.Id_locacion = locacion.Id_locacion) as totalhabsreservada")

//consulta que trae los depas desocupadas
   ->selectRaw("(SELECT COUNT(*) AS totaldepa FROM `departamento` 
   LEFT JOIN estado_ocupacion AS estatus ON estatus.Id_estado_ocupacion = departamento.Id_estado_ocupacion
   WHERE estatus.Nombre_estado = 'Desocupada'
   AND departamento.Id_locacion = locacion.Id_locacion) as totaldepaslibres")
//consulta que trae los depas ocupadas
   ->selectRaw("(SELECT COUNT(*) AS totaldepas FROM `departamento` 
   LEFT JOIN estado_ocupacion AS estatus ON estatus.Id_estado_ocupacion = departamento.Id_estado_ocupacion
   WHERE estatus.Nombre_estado = 'Rentada'
   AND departamento.Id_locacion = locacion.Id_locacion) as totaldepasocupadas")
//consulta que trae los depas ocupadas
   ->selectRaw("(SELECT COUNT(*) AS depasreserva FROM `departamento` 
   LEFT JOIN estado_ocupacion AS estatus ON estatus.Id_estado_ocupacion = departamento.Id_estado_ocupacion
   WHERE estatus.Nombre_estado = 'Reservada'
   AND departamento.Id_locacion = locacion.Id_locacion) as totaldepasreservada")

//consulta que trae los locales desocupados
   ->selectRaw("(SELECT COUNT(*) AS totalloc FROM `local` 
   LEFT JOIN estado_ocupacion AS estatus ON estatus.Id_estado_ocupacion = local.Id_estado_ocupacion
   WHERE estatus.Nombre_estado = 'Desocupada'
   AND local.Id_locacion = locacion.Id_locacion) as totallocslibres")
//consulta que trae los locales ocupadas
   ->selectRaw("(SELECT COUNT(*) AS totallocs FROM `local` 
   LEFT JOIN estado_ocupacion AS estatus ON estatus.Id_estado_ocupacion = local.Id_estado_ocupacion
   WHERE estatus.Nombre_estado = 'Rentada'
   AND local.Id_locacion = locacion.Id_locacion) as totallocsocupadas")
//consulta que trae los locales ocupadas
   ->selectRaw("(SELECT COUNT(*) AS locsreserva FROM `local` 
   LEFT JOIN estado_ocupacion AS estatus ON estatus.Id_estado_ocupacion = local.Id_estado_ocupacion
   WHERE estatus.Nombre_estado = 'Reservada'
   AND local.Id_locacion = locacion.Id_locacion) as totallocsreservada")


   ->where('Nombre_locacion','LIKE','%'.$nickname.'%')
   ->where(function($query) use($tiporenta){
         if($tiporenta =='1'){
      return $query->where('Tipo_renta');}
      if($tiporenta =='2'){
         return $query->where('Tipo_renta');}
      })
   ->where('Colonia','LIKE','%'.$colonia.'%')
   ->where('est.Id_estado_ocupacion','LIKE','%'.$estatus.'%')
   ->get();


   return view('Locaciones/locaciones', compact('locaciones','nickname','tiporenta','colonia','estatus','estatus_locaciones','casas'));
   }


//funcion para la vista de eliminar locacion
public function ViewDesactivarLoc($Id_locacion ){

   $locacion = DB::table('locacion')
   ->select('locacion.Id_locacion', 'Nombre_locacion', 'est.Nombre_estado',
   'Tipo_renta', 'Calle','Numero_ext', 
   'Colonia', 'Ubi_google_maps', 'Numero_total_de_pisos',
   'Numero_total_habitaciones', 'Numero_total_depas', 
   'Numero_total_locales','Capacidad_personas', 'Precio_noche', 'Precio_semana',
   'Precio_catorcedias','Precio_mes', 'Deposito_garantia_casa', 'Uso_cocheras', 
   'Total_cocheras','Encargado','Espacio_superficie','Zona_ciudad', 
   'Numero_habs_actuales', 'Numero_depas_actuales', 'Numero_locs_actuales','Nota',
   'Descripcion','Cobro_p_ext_mes_c','Cobro_p_ext_catorcena_c',
   'Cobro_p_ext_noche_c', 'Cobro_anticipo_mes_c','Cobro_anticipo_catorcena_c','Camas_juntas')
//joins que me vinculan a otras tablas para hacer consultas 
  ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "locacion.Id_estado_ocupacion")
  ->where('locacion.Id_locacion', '=', $Id_locacion)
  ->get();
   
   return view('Locaciones.desactivarLocacion', compact('locacion'));
}

public function DesactivarLocacion($Id_locacion){
   try{

         $locacion = DB::table('locacion')
         ->where('Id_locacion', '=', $Id_locacion )
         ->update(['Id_estado_ocupacion' => 3]);

   Alert::success('Exito', 'Se ha desactivado la locacion con exito');
   return redirect()->back();

   }catch(Exception $ex){
   Alert::error('Error', 'La locacion no se pudo desactivar revisa que todo este en orden');
   return redirect()->back();
}  
   
}
      


//funcion para la vista de detalles de locacion
public function ViewDetalleLoc($Id_locacion){

   $locacion=DB::table('locacion')
   ->select('locacion.Id_locacion', 'Nombre_locacion', 'est.Nombre_estado',
   'Tipo_renta', 'Calle','Numero_ext', 'Colonia', 
   'Ubi_google_maps', 'Numero_total_de_pisos', 'Numero_total_habitaciones', 
   'Numero_total_depas', 'Numero_total_locales','Capacidad_personas', 
   'Precio_noche', 'Precio_semana','Precio_catorcedias',
   'Precio_mes', 'Deposito_garantia_casa', 'Uso_cocheras', 
   'Total_cocheras','Encargado','Espacio_superficie','Zona_ciudad', 
   'Numero_habs_actuales', 'Numero_depas_actuales', 'Numero_locs_actuales',
   'Nota','Descripcion','Cobro_p_ext_mes_c','Cobro_p_ext_catorcena_c',
   'Cobro_p_ext_noche_c','Cobro_anticipo_mes_c','Cobro_anticipo_catorcena_c',
   'Camas_juntas')
   ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "locacion.Id_estado_ocupacion")
   ->where('Id_locacion', '=', $Id_locacion)
   ->get();

   $lugar_cliente_reservado = DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
   'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
   'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
   'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
   'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
   'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
   'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
   )
   ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
   ->leftJoin("locacion", "locacion.Id_locacion", "=", "lugares_reservados.Id_locacion")
   ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   LEFT JOIN locacion ON locacion.Id_locacion = lugares_reservados.Id_locacion
   WHERE locacion.Id_locacion =  ".$Id_locacion.") as totalclientes")
   ->where('locacion.Id_locacion', '=', $Id_locacion)
   ->get();

   $servicios = Servicios_estancia::get();
   
   $files = DB::table('fotos_lugares')
   ->where('Id_locacion', '=', $Id_locacion)
   ->get();

   $relacion_servicio =  DB::table('relacion_servicios') 
   ->where('Id_locacion', '=', $Id_locacion)
   ->get();

   $servicio_bano= DB::table('servicio_bano') 
   ->where('Id_locacion', '=', $Id_locacion)
   ->get();

   $servicio_cama= DB::table('servicio_cama')
   ->where('Id_locacion', '=', $Id_locacion)
   ->get();

   /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
   para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
   $lugar_reservacion= DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
            'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->where('Id_locacion', '=', $Id_locacion)
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

   if($locacion[0]->Nombre_estado  == "Desocupada"){
      return view('Locaciones.detalleLocacionLibre', compact('locacion', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
   }else{
      if($locacion[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
         return view('Locaciones.detalleLocacionLibre', compact('locacion', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($locacion[0]->Nombre_estado == "Desactivada"){
            return view('Locaciones.detalleLocacionLibre', compact('locacion', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($locacion[0]->Nombre_estado == "Rentada"){
               return view('Locaciones.detalleLocacionOcupada', compact('locacion', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
            }else{
               if($locacion[0]->Nombre_estado == "Reservada"){
                  return view('Locaciones.detalleLocacionLibre', compact('locacion', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
               }else{
                  if($locacion[0]->Nombre_estado == "Pago por confirmar"){
                     return view('Locaciones.detalleLocacionLibre', compact('locacion', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }   
               }
            }
         }

      }
   }
   
}

//funcion para la vista de intro para agregar una locacion
public function IntroLoc(){
   return view('Locaciones.agregarLocacion');
}


//funcion que muestra la vista de agregar un servicio desde casa entera
public function ViewServicioSecciones(){
   return view('Servicios_estancia.Agregar_ServicioSeccion');
}

//funcion que muestra la vista de agregar un servicio desde casa entera
public function ViewServicioEntera(){
   return view('Servicios_estancia.Agregar_ServicioEntera');
}


//funcion para guardar el registro de un nuevo servicio 
public function StoreServicio(Request $request){
   
   try{

//array que guarda la foto 7
$this->validate($request, array(
   'img7' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img7');

   if($image != ''){
      $agregarservicio = new Servicios_estancia();
      $agregarservicio-> Nombre_servicio = $request->get('nombre_servicio');
      $nombreImagen ='images/'.$agregarservicio->Nombre_servicio. '.' .$image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen7;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         $agregarservicio->Ruta_servicio = $nombreImagen; 
         $agregarservicio-> Seccion_servicio = $request->get('seccion_servicio');
         $agregarservicio->save();
   }}
   else{ 
      Alert::warning('cuidado', 'el icono no es aceptable intenta con otro');
      return redirect()->back();
   }

   Alert::success('Exito', 'El servicio se agrego correctamente');
   return redirect()->back();
   }catch(Exception $ex){
      Alert::error('Error', 'El servicio no se pudo agregar revisa que todo este en orden');
      return redirect()->back();
   }
}

//funcion de alertas
public function ViewAlertas(){
   return view('Alertas.alertas');
}



































//funciones de las locaciones enteras

//funcion para la vista de locacion entera
public function ViewEntera(){

   //consultas que me traen los registros de la bd
      $estatus_locaciones =  Estado_ocupacion::get();
      $servicios = Servicios_estancia::get();
         return view('Locaciones.Locacion_entera.locacionEntera', compact('estatus_locaciones', 'servicios'));
}
   
   //funcion que guarda un registro de locacion entera
public function StoreEntera(Request $request){

   $request->validate([
      'nombre' => 'required',
      'calle' => 'required',
      'numero_ext'=> 'required',
      'colonia'=> 'required',
      'LinkGM'=> 'required',
      'pisos'=> 'required',
      'cap_personas'=> 'required',
      'precio_noche'=> 'required',
      'precio_semana'=> 'required',
      'precio_catorcena'=> 'required',
      'precio_mes'=> 'required',
      'garantia'=> 'required',
      'total_hab'=> 'required',
      'superficie'=> 'required',
      'zona'=> 'required',
      'descripcion'=> 'required',
      'p_ext_mes'=> 'required',
      'p_ext_catorce'=> 'required',
      'p_ext_noche'=> 'required',
      'c_anticipo_mes'=> 'required',
      'c_anticipo_catorce'=> 'required',
      'camas_juntas'=> 'required'
  ]);

//info basica
   
   $agregarLocEntera = new Locacion();
   $agregarLocEntera-> Nombre_locacion = $request->get('nombre');
   $agregarLocEntera-> Calle = $request->get('calle');
   $agregarLocEntera-> Numero_ext = $request->get('numero_ext');
   $agregarLocEntera-> Colonia = $request->get('colonia');
   $agregarLocEntera-> Ubi_google_maps = $request->get('LinkGM');
   $agregarLocEntera-> Numero_total_de_pisos = $request->get('pisos');
   $agregarLocEntera-> Capacidad_personas = $request->get('cap_personas');
   $agregarLocEntera-> Precio_noche = $request->get('precio_noche');
   $agregarLocEntera-> Precio_semana = $request->get('precio_semana');
   $agregarLocEntera-> Precio_catorcedias = $request->get('precio_catorcena');
   $agregarLocEntera-> Precio_mes = $request->get('precio_mes');
   $agregarLocEntera-> Deposito_garantia_casa = $request->get('garantia');
   $agregarLocEntera-> Numero_total_habitaciones = $request->get('total_hab');
   $agregarLocEntera-> Espacio_superficie = $request->get('superficie');
   $agregarLocEntera-> Zona_ciudad = $request->get('zona'); 
   $agregarLocEntera-> Total_cocheras = $request->get('num_cochera');
   $agregarLocEntera-> Nota = $request->get('nota');
   $agregarLocEntera-> Descripcion = $request->get('descripcion');
   $agregarLocEntera-> Cobro_p_ext_mes_c = $request->get('p_ext_mes');
   $agregarLocEntera-> Cobro_p_ext_catorcena_c = $request->get('p_ext_catorce');
   $agregarLocEntera-> Cobro_p_ext_noche_c = $request->get('p_ext_noche');
   $agregarLocEntera-> Cobro_anticipo_mes_c = $request->get('c_anticipo_mes');
   $agregarLocEntera-> Cobro_anticipo_catorcena_c = $request->get('c_anticipo_catorce');
   $agregarLocEntera-> Camas_juntas = $request->get('camas_juntas');
   $agregarLocEntera-> Tipo_renta = "Entera";
   $agregarLocEntera-> Id_estado_ocupacion = "2";
   $agregarLocEntera->save();
//obtengo el id del ultimo registro guardado
   $idlocacion =DB::getPdo()->lastInsertId();
//servicios
   foreach ($request->arregloServicios as $arreglo) {
      DB::table('relacion_servicios')->insert(
      ['Id_locacion' => $agregarLocEntera->Id_locacion, 'Id_servicios_estancia' => $arreglo]);
   }
//baños
   $agregarbañoEntera = new Servicio_bano();
//llamo a la variable que contiene el id y lo guardo en el campo 
   $agregarbañoEntera-> Id_locacion = $idlocacion;
   $agregarbañoEntera-> Bano_compartido = $request->get('b_compartido');
   $agregarbañoEntera-> Bano_medio = $request->get('b_medio');
   $agregarbañoEntera-> Bano_completo = $request->get('b_completo');
   $agregarbañoEntera-> Bano_completo_RL = $request->get('b_completorl');
   $agregarbañoEntera->save();
//camas   
   $agregarcamaEntera = new Servicio_cama();
   $agregarcamaEntera-> Id_locacion = $idlocacion;
   $agregarcamaEntera-> Cama_individual = $request->get('c_individual');
   $agregarcamaEntera-> Cama_matrimonial = $request->get('c_matrimonial');
   $agregarcamaEntera-> Litera_individual = $request->get('c_l_individual');
   $agregarcamaEntera-> Litera_matrimonial = $request->get('c_l_matrimonial');
   $agregarcamaEntera-> Litera_ind_mat = $request->get('c_litera_im');
   $agregarcamaEntera-> Cama_kingsize = $request->get('c_kingsize');
   $agregarcamaEntera->save();

//fotografias        
//array que guarda la foto 1
   $this->validate($request, array(
   'img1' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img1');

   if($image != ''){
      $nombreImagen = $agregarLocEntera->Id_locacion.'_'.$agregarLocEntera->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen1;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $agregarLocEntera->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{ 
      Alert::warning('cuidado', 'LA IMAGEN 1 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();
      }
      
//array que guarda la foto 2
   $this->validate($request, array(
   'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img2');

   if($image != ''){
      $nombreImagen = $agregarLocEntera->Id_locacion.'_'.$agregarLocEntera->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen2;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $agregarLocEntera->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{  Alert::warning('cuidado', 'LA IMAGEN 2 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}

//array que guarda la foto 3
   $this->validate($request, array(
   'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img3');

   if($image != ''){
      $nombreImagen = $agregarLocEntera->Id_locacion.'_'.$agregarLocEntera->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen3;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $agregarLocEntera->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{  Alert::warning('cuidado', 'LA IMAGEN 3 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}

//array que guarda la foto 4
   $this->validate($request, array(
   'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img4');

   if($image != ''){
      $nombreImagen = $agregarLocEntera->Id_locacion.'_'.$agregarLocEntera->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen4;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $agregarLocEntera->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{  Alert::warning('cuidado', 'LA IMAGEN 4 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}

//array que guarda la foto 5
   $this->validate($request, array(
   'img5' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img5');

   if($image != ''){
      $nombreImagen = $agregarLocEntera->Id_locacion.'_'.$agregarLocEntera->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen5;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $agregarLocEntera->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{  Alert::warning('cuidado', 'LA IMAGEN 5 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}

//array que guarda la foto 6
   $this->validate($request, array(
   'img6' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img6');

   if($image != ''){
      $nombreImagen = $agregarLocEntera->Id_locacion.'_'.$agregarLocEntera->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen6;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $agregarLocEntera->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{  Alert::warning('cuidado', 'LA IMAGEN 6 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}

      UsuariosController::historial_log(Cookie::get('Id_colaborador'),"registro una nueva locacion entera");
   
   return view('Locaciones.finalizarFormSecc');
  
}
   
   

//funcion para la vista de editar locacion entera
public function ViewEditarLoc($Id_locacion){
      
      $locacion = Locacion::findOrFail($Id_locacion);

      $estatus_locaciones =  Estado_ocupacion::get();

      $servicios = Servicios_estancia::get();

      $relacion_servicio =  DB::table('relacion_servicios') 
      ->where('Id_locacion', '=', $Id_locacion)
      ->get();

      $servicio_bano= DB::table('servicio_bano') 
      ->where('Id_locacion', '=', $Id_locacion)
      ->get();

      $servicio_cama= DB::table('servicio_cama')
      ->where('Id_locacion', '=', $Id_locacion)
      ->get();
      
      $fotos = DB::table('fotos_lugares')
      ->where('Id_locacion', '=', $Id_locacion)
      ->get();



      if($locacion->Tipo_renta == "Secciones"){
         return view('Locaciones.Locacion_secciones.editarLocacionSecc', compact('estatus_locaciones', 'servicios', 'locacion', 'relacion_servicio', 'servicio_bano', 'servicio_cama', 'fotos'));
      }else{
         if($locacion->Tipo_renta == "Entera"){
            return view('Locaciones.Locacion_entera.editarLocacion', compact('estatus_locaciones', 'servicios', 'locacion', 'relacion_servicio', 'servicio_bano', 'servicio_cama', 'fotos'));
         }
      }
         
   }

//funcion para actualizar los registros de las locaciones enteras
public function UpdateEntera(Request $request, Locacion $locacion, Servicio_bano $servicio_bano, Servicio_cama $servicio_cama ){
   try{

      
      $locacion -> Tipo_renta = $request->tipo_renta;
      $locacion -> Id_estado_ocupacion = $request-> estatus;
      $locacion -> Nombre_locacion = $request-> nombre;
      $locacion -> Calle = $request-> calle;
      $locacion -> Numero_ext = $request->numero_ext;
      $locacion -> Colonia = $request->colonia;
      $locacion -> Ubi_google_maps = $request->LinkGM;
      $locacion -> Numero_total_de_pisos = $request->pisos;
      $locacion -> Capacidad_personas = $request->cap_personas;
      $locacion -> Precio_noche = $request->precio_noche;
      $locacion -> Precio_semana = $request->precio_semana;
      $locacion -> Precio_catorcedias = $request->precio_catorcena;
      $locacion -> Precio_mes = $request->precio_mes;
      $locacion -> Deposito_garantia_casa = $request->garantia;
      $locacion -> Numero_total_habitaciones = $request->total_hab;
      $locacion -> Espacio_superficie = $request->superficie;
      $locacion -> Zona_ciudad = $request->zona; 
      $locacion -> Total_cocheras = $request->num_cochera;
      $locacion -> Nota = $request->nota;
      $locacion -> Descripcion = $request->descripcion;
      $locacion -> Cobro_p_ext_mes_c = $request->p_ext_mes;
      $locacion -> Cobro_p_ext_catorcena_c = $request->p_ext_catorce;
      $locacion -> Cobro_p_ext_noche_c = $request->p_ext_noche;
      $locacion -> Cobro_anticipo_mes_c = $request->c_anticipo_mes;
      $locacion -> Cobro_anticipo_catorcena_c = $request->c_anticipo_catorce;
      $locacion-> Camas_juntas = $request->camas_juntas;
      $locacion ->save();


      
 //servicios
 //borra los registros que tienen el id de la loc    
         $borrarservicios = DB::table('relacion_servicios')
         ->where('Id_locacion', '=', $locacion -> Id_locacion)
         ->delete();
         //hace un nuevo insert para colocar los datos actualizados
         foreach ($request->arregloServicios as $arreglo) {
            DB::table('relacion_servicios')->insert(
            ['Id_locacion' => $locacion->Id_locacion, 'Id_servicios_estancia' => $arreglo]);
         }
               

//baños
//llamo a la variable que contiene el id y lo guardo en el campo 
      
      $servicio_bano-> Id_locacion = $locacion -> Id_locacion;
      $servicio_bano-> Bano_compartido = $request->b_compartido;
      $servicio_bano-> Bano_medio = $request->b_medio;
      $servicio_bano-> Bano_completo = $request->b_completo;
      $servicio_bano-> Bano_completo_RL = $request->b_completorl;
      $servicio_bano->save();
//camas   
      
      $servicio_cama-> Id_locacion = $locacion -> Id_locacion;
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
         $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen1;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
      }}
         
//array que guarda la foto 2
      $this->validate($request, array(
      'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img2');

      if($image != ''){
         $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen2;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
      }}
   
//array que guarda la foto 3
      $this->validate($request, array(
      'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img3');

      if($image != ''){
         $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen3;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
      }}
   
//array que guarda la foto 4
      $this->validate($request, array(
      'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img4');

      if($image != ''){
         $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen4;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
      }}
   
//array que guarda la foto 5
      $this->validate($request, array(
      'img5' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img5');

      if($image != ''){
         $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen5;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
      }}
   
//array que guarda la foto 6
      $this->validate($request, array(
      'img6' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img6');

      if($image != ''){
         $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen6;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);

         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
      }}
   
      Alert::success('Exito', 'Se ha actualizado la locacion con exito');
      return redirect()->back();
   }
   catch(Exception $ex){
      Alert::error('Error', 'La locacion no se pudo actualizar revisa que todo este en orden');
      return redirect()->back();
   }

   }

      //funcion que destruye las imagenes en la pagina de editar activo
public function DestroyImg( Request $request, $Id_foto_lugar ) {
               
         try{
      $locaciones=DB::table('fotos_lugares')
      ->where('Id_foto_lugar', '=', $Id_foto_lugar )
      ->get();
      //se hace un json encode y decode para transformar los datos en json 
      $locaciones = json_decode(json_encode($locaciones));
               
      foreach($locaciones as $locacion)
      {
         if(!empty($locacion->Ruta_lugar)) {
            File::delete('C:\xampp\htdocs\alohate\public\uploads\locacion'.$locacion->Ruta_lugar);}
                   
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
             

































//funciones para las laciones por secciones
//funcion para la vista de locacion por secciones
public function ViewSecciones(){
//consultas que me traen los registros de la bd
      $estatus_locaciones =  Estado_ocupacion::get();
      $servicios = Servicios_estancia::get();

      return view('Locaciones.Locacion_secciones.locacionSecciones', compact('servicios', 'estatus_locaciones'));
   }


//funcion para guardar el registro de la locacion por secciones y vincula a la intro de habs
public function StoreSecciones(Request $request){
     
   
   
   $request->validate([
      'nombre' => 'required',
      'calle' => 'required',
      'numero_ext'=> 'required',
      'colonia'=> 'required',
      'LinkGM'=> 'required',
      'pisos'=> 'required',
      'cap_personas'=> 'required',
      'zona'=> 'required',
      'descripcion'=> 'required',
      'total_hab'=> 'required',
      'total_dep'=> 'required',
      'total_loc'=> 'required',

  ]);

      $agregarLocSecc = new Locacion();
      $agregarLocSecc-> Tipo_renta = "Secciones";
      $agregarLocSecc-> Id_estado_ocupacion = "2";
      $agregarLocSecc-> Nombre_locacion = $request->get('nombre');
      $agregarLocSecc-> Calle = $request->get('calle');
      $agregarLocSecc-> Numero_ext = $request->get('numero_ext');
      $agregarLocSecc-> Colonia = $request->get('colonia');
      $agregarLocSecc-> Ubi_google_maps = $request->get('LinkGM');
      $agregarLocSecc-> Numero_total_de_pisos = $request->get('pisos');
      $agregarLocSecc-> Capacidad_personas = $request->get('cap_personas');
      $agregarLocSecc-> Numero_total_habitaciones = $request->get('total_hab');
      $agregarLocSecc-> Numero_total_depas = $request->get('total_dep');
      $agregarLocSecc-> Numero_total_locales = $request->get('total_loc');
      $agregarLocSecc-> Zona_ciudad = $request->get('zona'); 
      $agregarLocSecc-> Total_cocheras = $request->get('num_cochera');
      $agregarLocSecc-> Nota = $request->get('nota');
      $agregarLocSecc-> Descripcion = $request->get('descripcion');
      $agregarLocSecc->save();


//obtengo el id del ultimo registro guardado
//tambien esta variable la mandare a las vistas de introhabs/depas/locales para poder guardarlo en el campo de la locacion a la que pertenece
   $idlocacion =DB::getPdo()->lastInsertId();
//servicios
        foreach ($request->arregloServicios as $arreglo) {
           DB::table('relacion_servicios')->insert(
           ['Id_locacion' => $agregarLocSecc->Id_locacion, 'Id_servicios_estancia' => $arreglo]);
        }
  
//fotografias        
//array que guarda la foto 1
        $this->validate($request, array(
        'img1' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img1');
     
        if($image != ''){
           $nombreImagen = $agregarLocSecc->Id_locacion.'_'.$agregarLocSecc->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
           $base64Img = $request->nuevaImagen1;
           $base_to_php = explode(',',$base64Img);
           $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
           $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
           $guardarImagen = file_put_contents($filepath, $data);
     
           if ($guardarImagen !== false) {
              DB::table('fotos_lugares')->insert(
              ['Id_locacion' => $agregarLocSecc->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
        }}
        else{ 
         Alert::error('Error', 'LA IMAGEN 1 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();
      }
         
           
//array que guarda la foto 2
        $this->validate($request, array(
        'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img2');
  
        if($image != ''){
           $nombreImagen = $agregarLocSecc->Id_locacion.'_'.$agregarLocSecc->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
           $base64Img = $request->nuevaImagen2;
           $base_to_php = explode(',',$base64Img);
           $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
           $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
           $guardarImagen = file_put_contents($filepath, $data);
  
           if ($guardarImagen !== false) {
              DB::table('fotos_lugares')->insert(
              ['Id_locacion' => $agregarLocSecc->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
        }}
        else{ Alert::error('Error', 'LA IMAGEN 2 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}
     
//array que guarda la foto 3
        $this->validate($request, array(
        'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img3');
  
        if($image != ''){
           $nombreImagen = $agregarLocSecc->Id_locacion.'_'.$agregarLocSecc->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
           $base64Img = $request->nuevaImagen3;
           $base_to_php = explode(',',$base64Img);
           $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
           $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
           $guardarImagen = file_put_contents($filepath, $data);
  
           if ($guardarImagen !== false) {
              DB::table('fotos_lugares')->insert(
              ['Id_locacion' => $agregarLocSecc->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
        }}
        else{ Alert::error('Error', 'LA IMAGEN 3 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}
     
//array que guarda la foto 4
        $this->validate($request, array(
        'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img4');
  
        if($image != ''){
           $nombreImagen = $agregarLocSecc->Id_locacion.'_'.$agregarLocSecc->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
           $base64Img = $request->nuevaImagen4;
           $base_to_php = explode(',',$base64Img);
           $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
           $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
           $guardarImagen = file_put_contents($filepath, $data);
  
           if ($guardarImagen !== false) {
              DB::table('fotos_lugares')->insert(
              ['Id_locacion' => $agregarLocSecc->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
        }}
        else{ Alert::error('Error', 'LA IMAGEN 4 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}
     
//array que guarda la foto 5
        $this->validate($request, array(
        'img5' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img5');
  
        if($image != ''){
           $nombreImagen = $agregarLocSecc->Id_locacion.'_'.$agregarLocSecc->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
           $base64Img = $request->nuevaImagen5;
           $base_to_php = explode(',',$base64Img);
           $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
           $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
           $guardarImagen = file_put_contents($filepath, $data);
  
           if ($guardarImagen !== false) {
              DB::table('fotos_lugares')->insert(
              ['Id_locacion' => $agregarLocSecc->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
        }}
        else{ Alert::error('Error', 'LA IMAGEN 5 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}
     
//array que guarda la foto 6
        $this->validate($request, array(
        'img6' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ));
        $image = $request->file('img6');
  
        if($image != ''){
           $nombreImagen = $agregarLocSecc->Id_locacion.'_'.$agregarLocSecc->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
           $base64Img = $request->nuevaImagen6;
           $base_to_php = explode(',',$base64Img);
           $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
           $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
           $guardarImagen = file_put_contents($filepath, $data);
  
           if ($guardarImagen !== false) {
              DB::table('fotos_lugares')->insert(
              ['Id_locacion' => $agregarLocSecc->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
        }}
        else{ Alert::error('Error', 'LA IMAGEN 6 NO ES ADMITIDA, CAMBIALA');
         return redirect()->back();}

         UsuariosController::historial_log(Cookie::get('Id_colaborador'),"registro una nueva locacion por secciones");

         $lochabstotal=DB::table('locacion')
         ->select('Id_locacion', 'Numero_total_habitaciones', 
                   'Numero_total_depas', 'Numero_total_locales')
         ->where('Id_locacion', '=', $idlocacion)
         ->get();
   
         UsuariosController::historial_log(Cookie::get('Id_colaborador'),"registro una nueva habitacion");
   
   //creo un ciclo con if para que iguale el numero de de habs actuales y totales, si no es igual se repite el form pero si es igual pasa a los depas      
         if($lochabstotal[0]->Numero_total_habitaciones != 0){
           //aqui mando la variable de "id locacion en el metodo compact, esta variable la pondre en las rutas y en los forms"
            return view('Locaciones.Locacion_secciones.introHabs',compact('idlocacion'));
         }
            else{
               if($lochabstotal[0]->Numero_total_depas != 0){
                  return view('Locaciones.Locacion_secciones.introDepas', compact('idlocacion'));
                  }
                  else{
                     if($lochabstotal[0]->Numero_total_locales != 0){
                        return view('Locaciones.Locacion_secciones.introLocales', compact('idlocacion'));
                        }
                        else{
                           return view('Locaciones.finalizarFormSecc', compact('idlocacion'));
                            }
                      }
                }
} 

//funcion para actualizar los registros de las locaciones por secciones
public function UpdateSecciones(Request $request, Locacion $locacion ){
   
   try{
      $locacion -> Tipo_renta = $request->tipo_renta;
      $locacion-> Id_estado_ocupacion = $request->estatus;
      $locacion-> Nombre_locacion = $request->nombre;
      $locacion-> Calle = $request->calle;
      $locacion-> Numero_ext = $request->numero_ext;
      $locacion-> Colonia = $request->colonia;
      $locacion-> Ubi_google_maps = $request->LinkGM;
      $locacion-> Numero_total_de_pisos = $request->pisos;
      $locacion-> Capacidad_personas = $request->cap_personas;
      $locacion-> Precio_noche = $request->precio_noche;
      $locacion-> Precio_semana = $request->precio_semana;
      $locacion-> Precio_catorcedias = $request->precio_catorcena;
      $locacion-> Precio_mes = $request->precio_mes;
      $locacion-> Deposito_garantia_casa = $request->garantia;
      $locacion-> Numero_total_habitaciones = $request->total_hab;
      $locacion-> Numero_total_depas = $request->total_dep;
      $locacion-> Numero_total_locales = $request->total_loc;
      $locacion-> Espacio_superficie = $request->superficie;
      $locacion-> Zona_ciudad = $request->zona; 
      $locacion-> Total_cocheras = $request->num_cochera;
      $locacion-> Nota = $request->nota;
      $locacion-> Descripcion = $request->descripcion;
      $locacion ->save();


   
//servicios
//borra los registros que tienen el id de la loc    
      $borrarservicios = DB::table('relacion_servicios')
      ->where('Id_locacion', '=', $locacion -> Id_locacion)
      ->delete();
      //hace un nuevo insert para colocar los datos actualizados
      foreach ($request->arregloServicios as $arreglo) {
         DB::table('relacion_servicios')->insert(
         ['Id_locacion' => $locacion->Id_locacion, 'Id_servicios_estancia' => $arreglo]);
      }
            

//fotografias        
//array que guarda la foto 1
   $this->validate($request, array(
   'img1' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img1');

   if($image != ''){
      $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen1;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}
      
//array que guarda la foto 2
   $this->validate($request, array(
   'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img2');

   if($image != ''){
      $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen2;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}

//array que guarda la foto 3
   $this->validate($request, array(
   'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img3');

   if($image != ''){
      $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen3;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}

//array que guarda la foto 4
   $this->validate($request, array(
   'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img4');

   if($image != ''){
      $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen4;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}

//array que guarda la foto 5
   $this->validate($request, array(
   'img5' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img5');

   if($image != ''){
      $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen5;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}

//array que guarda la foto 6
   $this->validate($request, array(
   'img6' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img6');

   if($image != ''){
      $nombreImagen = $locacion->Id_locacion.'_'.$locacion->Nombre_locacion.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen6;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/locacion/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_locacion' => $locacion->Id_locacion, 'Ruta_lugar' => $nombreImagen]);
   }}

   Alert::success('Exito', 'Se ha actualizado la locacion correctamente');
   return redirect()->back();
   
}
catch(Exception $ex){
   Alert::error('Error', 'Error al actualizar la locacion revisa que los datos esten bien y vuelve a intentarlo');
   return redirect()->back();
}
}


//funcion para la vista de las habitaciones 
public function ViewHabs($idlocacion){
//aqui envie la vairable de "idlocacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact
      
//consultas que me traen los registros de la bd
      $estatus_locaciones =  Estado_ocupacion::get();
      $servicios = Servicios_estancia::get();
//aqui mando la variable de "id locacion en el metodo compact, esta variable la pondre en las rutas y en los forms"
      return view('Locaciones.Locacion_secciones.locacionHabs', compact('idlocacion', 'servicios', 'estatus_locaciones'));
   }

//funcion para guardar el registro de las habitaciones y vincula a la intro de depas
public function HabsStore(Request $request, $idlocacion, Locacion $lochabstotal){
//aqui envie la vairable de "idlocacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact
$request->validate([
   'planta' => 'required',
   'cap_personas' => 'required',
   'precio_noche'=> 'required',
   'precio_semana'=> 'required',
   'precio_catorcena'=> 'required',
   'precio_mes'=> 'required',
   'garantia'=> 'required',
   'p_ext_mes'=> 'required',
   'p_ext_catorce'=> 'required',
   'p_ext_noche'=> 'required',
   'c_anticipo_mes'=> 'required',
   'c_anticipo_catorce'=> 'required',
   'superficie'=> 'required',
   'descripcion'=> 'required',
   'camas_juntas'=> 'required',
]);
//info basica
      
      $agregarhab = new Habitacion();
      $agregarhab-> Id_locacion = $idlocacion;
      $agregarhab-> Id_estado_ocupacion = "2";
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
  


//minifuncion para el bucle de crear las habs      
//hago una consulta a la bd para checar el numero de de habs 
      $lochabstotal=DB::table('locacion')
      ->select('Id_locacion', 'Numero_total_habitaciones','Numero_habs_actuales', 
                'Numero_total_depas', 'Numero_total_locales')
      ->where('Id_locacion', '=', $idlocacion)
      ->get();
//declaro una variable que tomara el registro de num de habs actuales y le sumara 1 
      $aumentador = $lochabstotal[0]->Numero_habs_actuales + 1;
//hago la actualizacion del dato 
      $affected = DB::table('locacion')
      ->where('Id_locacion', '=', $idlocacion)
      ->update(['Numero_habs_actuales' => $aumentador]);
 //vuelvo a hacer una consulta a la bd
      $lochabstotal=DB::table('locacion')
      ->select('Id_locacion', 'Numero_total_habitaciones','Numero_habs_actuales', 
                'Numero_total_depas', 'Numero_total_locales')
      ->where('Id_locacion', '=', $idlocacion)
      ->get();

      UsuariosController::historial_log(Cookie::get('Id_colaborador'),"registro una nueva habitacion");

//creo un ciclo con if para que iguale el numero de de habs actuales y totales, si no es igual se repite el form pero si es igual pasa a los depas      
      if( $lochabstotal[0]->Numero_habs_actuales < $lochabstotal[0]->Numero_total_habitaciones){
        
         Alert::info('Exito', 'Se agrego la habitacion con exito pero aun quedan habitaciones por registrar');
         return redirect()->back();
      }
         else{
            if($lochabstotal[0]->Numero_total_depas != 0){
               return view('Locaciones.Locacion_secciones.introDepas', compact('idlocacion'));
               }
               else{
                  if($lochabstotal[0]->Numero_total_locales != 0){
                     return view('Locaciones.Locacion_secciones.introLocales', compact('idlocacion'));
                     }
                     else{
                        return view('Locaciones.finalizarFormSecc', compact('idlocacion'));
                         }
                   }
             }
   }

//funcion para la vista de los depas
public function ViewDepas($idlocacion){
//aqui envie la vairable de "idlocacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact
  
//consultas que me traen los registros de la bd
  $estatus_locaciones =  Estado_ocupacion::get();
  $servicios = Servicios_estancia::get();
//aqui mando la variable de "id locacion en el metodo compact, esta variable la pondre en las rutas y en los forms"
      return view('Locaciones.Locacion_secciones.locacionDepas', compact('idlocacion', 'estatus_locaciones', 'servicios'));
   }


//funcion para guardar el registro de los depas y vincula a la intro de los locales
public function DepasStore(Request $request, $idlocacion, Locacion $locdepstotal){
//aqui envie la vairable de "idlocacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact
$request->validate([
   'planta' => 'required',
   'cap_personas' => 'required',
   'precio_noche'=> 'required',
   'precio_semana'=> 'required',
   'precio_catorcena'=> 'required',
   'precio_mes'=> 'required',
   'garantia'=> 'required',
   'p_ext_mes'=> 'required',
   'p_ext_catorce'=> 'required',
   'p_ext_noche'=> 'required',
   'c_anticipo_mes'=> 'required',
   'c_anticipo_catorce'=> 'required',
   'superficie'=> 'required',
   'recamaras'=> 'required',
   'descripcion'=> 'required',
   'camas_juntas'=> 'required',
]);
//info basica
      
   $agregardep = new Departamento();
   $agregardep-> Id_locacion = $idlocacion;
   $agregardep-> Id_estado_ocupacion = "2";
   $agregardep-> Nombre_depa = $request->get('nombre');
   $agregardep-> Capacidad_personas = $request->get('cap_personas');
   $agregardep-> Deposito_garantia_dep = $request->get('garantia');
   $agregardep-> Precio_noche = $request->get('precio_noche');
   $agregardep-> Precio_semana = $request->get('precio_semana');
   $agregardep-> Precio_catorcedias = $request->get('precio_catorcena');
   $agregardep-> Precio_mes = $request->get('precio_mes');
   $agregardep-> Espacio_superficie = $request->get('superficie');
   $agregardep-> Habitaciones_total = $request->get('recamaras');
   $agregardep-> Nota = $request->get('nota');
   $agregardep-> Descripcion = $request->get('descripcion');
   $agregardep-> Cobro_p_ext_mes_d = $request->get('p_ext_mes');
   $agregardep-> Cobro_p_ext_catorcena_d = $request->get('p_ext_catorce');
   $agregardep-> Cobro_p_ext_noche_d = $request->get('p_ext_noche');
   $agregardep-> Cobro_anticipo_mes_d = $request->get('c_anticipo_mes');
   $agregardep-> Cobro_anticipo_catorcena_d = $request->get('c_anticipo_catorce');
   $agregardep-> Camas_juntas = $request->get('camas_juntas');
   $agregardep->save();

//obtengo el id del ultimo registro guardado
   $iddep =DB::getPdo()->lastInsertId();
//servicios
   foreach ($request->arregloServicios as $arreglo) {
      DB::table('relacion_servicios')->insert(
      ['Id_departamento' => $agregardep->Id_departamento, 'Id_servicios_estancia' => $arreglo]);
   }

//planta
   $plantadep = new Plantas_pisos();
//llamo a la variable que contiene el id y lo guardo en el campo 
   $plantadep-> Id_departamento  = $iddep;
   $plantadep-> Nombre_planta = $request->get('planta');
   $plantadep->save();

//baños
   $agregarbañodep = new Servicio_bano();
//llamo a la variable que contiene el id y lo guardo en el campo 
   $agregarbañodep-> Id_departamento = $iddep;
   $agregarbañodep-> Bano_compartido = $request->get('b_compartido');
   $agregarbañodep-> Bano_medio = $request->get('b_medio');
   $agregarbañodep-> Bano_completo = $request->get('b_completo');
   $agregarbañodep-> Bano_completo_RL = $request->get('b_completorl');
   $agregarbañodep->save();
//camas   
   $agregarcamadep = new Servicio_cama();
   $agregarcamadep-> Id_departamento = $iddep;
   $agregarcamadep-> Cama_individual = $request->get('c_individual');
   $agregarcamadep-> Cama_matrimonial = $request->get('c_matrimonial');
   $agregarcamadep-> Litera_individual = $request->get('c_l_individual');
   $agregarcamadep-> Litera_matrimonial = $request->get('c_l_matrimonial');
   $agregarcamadep-> Litera_ind_mat = $request->get('c_litera_im');
   $agregarcamadep-> Cama_kingsize = $request->get('c_kingsize');
   $agregarcamadep->save();

//fotografias        
//array que guarda la foto 1
   $this->validate($request, array(
   'img1' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img1');

   if($image != ''){
      $nombreImagen = $agregardep->Id_departamento.'_'.$agregardep->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen1;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_departamento' => $agregardep->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{ Alert::error('Error', 'LA IMAGEN 1 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}
      
//array que guarda la foto 2
   $this->validate($request, array(
   'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img2');

   if($image != ''){
      $nombreImagen = $agregardep->Id_departamento.'_'.$agregardep->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen2;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_departamento' => $agregardep->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{ Alert::error('Error', 'LA IMAGEN 2 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}

//array que guarda la foto 3
   $this->validate($request, array(
   'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img3');

   if($image != ''){
      $nombreImagen = $agregardep->Id_departamento.'_'.$agregardep->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen3;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_departamento' => $agregardep->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{Alert::error('Error', 'LA IMAGEN 3 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}

//array que guarda la foto 4
   $this->validate($request, array(
   'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img4');

   if($image != ''){
      $nombreImagen = $agregardep->Id_departamento.'_'.$agregardep->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen4;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_departamento' => $agregardep->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{Alert::error('Error', 'LA IMAGEN 4 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}

//array que guarda la foto 5
   $this->validate($request, array(
   'img5' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img5');

   if($image != ''){
      $nombreImagen = $agregardep->Id_departamento.'_'.$agregardep->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen5;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_departamento' => $agregardep->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{ Alert::error('Error', 'LA IMAGEN 5 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}

//array que guarda la foto 6
   $this->validate($request, array(
   'img6' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
   ));
   $image = $request->file('img6');

   if($image != ''){
      $nombreImagen = $agregardep->Id_departamento.'_'.$agregardep->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
      $base64Img = $request->nuevaImagen6;
      $base_to_php = explode(',',$base64Img);
      $data = base64_decode($base_to_php[1]);
//aviso         
//en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
      $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
      $guardarImagen = file_put_contents($filepath, $data);

      if ($guardarImagen !== false) {
         DB::table('fotos_lugares')->insert(
         ['Id_departamento' => $agregardep->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
   }}
   else{Alert::error('Error', 'LA IMAGEN 6 NO ES ADMITIDA, CAMBIALA');
      return redirect()->back();}



//minifuncion para el bucle de crear los depas     
//hago una consulta a la bd para checar el numero de de habs 
      $locdepstotal=DB::table('locacion')
      ->select('Id_locacion', 'Numero_total_depas', 'Numero_depas_actuales', 'Numero_total_locales')
      ->where('Id_locacion', '=', $idlocacion)
      ->get();
//declaro una variable que tomara el registro de num de habs actuales y le sumara 1 
      $aumentador = $locdepstotal[0]->Numero_depas_actuales + 1;
//hago la actualizacion del dato 
      $affected = DB::table('locacion')
      ->where('Id_locacion', '=', $idlocacion)
      ->update(['Numero_depas_actuales' => $aumentador]);
//vuelvo a hacer una consulta a la bd
      $locdepstotal=DB::table('locacion')
      ->select('Id_locacion', 'Numero_total_depas', 'Numero_depas_actuales','Numero_total_locales')
      ->where('Id_locacion', '=', $idlocacion)
      ->get();
      
      UsuariosController::historial_log(Cookie::get('Id_colaborador'),"registro un nuevo departamento");

//creo un ciclo con if para que iguale el numero de de habs actuales y totales, si no es igual se repite el form pero si es igual pasa a los depas      
      if( $locdepstotal[0]->Numero_depas_actuales < $locdepstotal[0]->Numero_total_depas){
         Alert::info('Exito', 'Se agrego el departamento con exito pero aun quedan departamentos por registrar');
         return redirect()->back();
      }
         else{
               if($locdepstotal[0]->Numero_total_locales != 0){
//aqui mando la variable de "id locacion en el metodo compact, esta variable la pondre en las rutas y en los forms"    
                  return view('Locaciones.Locacion_secciones.introLocales', compact('idlocacion'));
               }
               else{
                  return view('Locaciones.finalizarFormSecc', compact('idlocacion'));
               }
            }

   }

//funcion para la vista de los locales
public function ViewLocales($idlocacion){
//aqui envie la vairable de "idlocacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact

//consultas que me traen los registros de la bd
  $estatus_locaciones =  Estado_ocupacion::get();
  $servicios = Servicios_estancia::get();
//aqui mando la variable de "id locacion en el metodo compact, esta variable la pondre en las rutas y en los forms"
      return view('Locaciones.Locacion_secciones.locacionLocales', compact('idlocacion', 'estatus_locaciones', 'servicios'));
   }


//funcion para guardar el registro de los depas y vincula a la intro de los locales
public function LocsStore(Request $request, $idlocacion, Locacion $loclocstotal){
//aqui envie la vairable de "idlocacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact
$request->validate([
   'planta' => 'required',
   'renta' => 'required',
   'superficie'=> 'required',
   'descripcion'=> 'required',
   'garantia'=> 'required',
]);
//info basica
         
   $agregarloc = new Local();
   $agregarloc-> Id_locacion = $idlocacion;
   $agregarloc-> Id_estado_ocupacion = "2";
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


//minifuncion para el bucle de crear los depas     
//hago una consulta a la bd para checar el numero de de habs 
      $loclocstotal=DB::table('locacion')
      ->select('Id_locacion', 'Numero_locs_actuales' ,'Numero_total_locales')
      ->where('Id_locacion', '=', $idlocacion)
      ->get();
//declaro una variable que tomara el registro de num de habs actuales y le sumara 1 
      $aumentador = $loclocstotal[0]->Numero_locs_actuales + 1;
//hago la actualizacion del dato 
      $affected = DB::table('locacion')
      ->where('Id_locacion', '=', $idlocacion)
      ->update(['Numero_locs_actuales' => $aumentador]);
//vuelvo a hacer una consulta a la bd
      $loclocstotal=DB::table('locacion')
      ->select('Id_locacion', 'Numero_locs_actuales' ,'Numero_total_locales')
      ->where('Id_locacion', '=', $idlocacion)
      ->get();

      UsuariosController::historial_log(Cookie::get('Id_colaborador'),"registro un nuevo local");

//creo un ciclo con if para que iguale el numero de de habs actuales y totales, si no es igual se repite el form pero si es igual pasa a los depas      
      if( $loclocstotal[0]->Numero_locs_actuales < $loclocstotal[0]->Numero_total_locales){   
         Alert::info('Exito', 'Se agrego el local con exito pero aun quedan locales por registrar');
         return redirect()->back();
      }
      else{
         return view('Locaciones.finalizarFormSecc', compact('idlocacion'));
      }

   }

}


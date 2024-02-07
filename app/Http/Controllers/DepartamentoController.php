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

class DepartamentoController extends Controller
{

public function ViewDepartamentos($Id_locacion){

    $estatus_depa =  Estado_ocupacion::get();

    $departamentos = DB::table('departamento')
    ->select('departamento.Id_departamento', 'departamento.Id_locacion','est.Nombre_estado', 'Id_colaborador',
    'Nombre_depa','Capacidad_personas', 'Deposito_garantia_dep', 'Precio_noche', 'Precio_semana', 
    'Precio_catorcedias', 'Precio_mes', 'Habitaciones_total', 
    'Encargado','Espacio_superficie','Nota','Descripcion',
    'Cobro_p_ext_mes_d','Cobro_p_ext_catorcena_d','Cobro_p_ext_noche_d',
    'Cobro_anticipo_mes_d','Cobro_anticipo_catorcena_d','Camas_juntas', 
    'bano.Bano_compartido', 'bano.Bano_medio', 'bano.Bano_completo', 'bano.Bano_completo_RL',
    'cama.Cama_individual', 'cama.Cama_matrimonial', 'cama.Litera_individual', 'cama.Litera_matrimonial',
    'cama.Litera_ind_mat','cama.Cama_kingsize', 'planta.Nombre_planta')
//joins que me vinculan a otras tablas para hacer consultas 
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "departamento.Id_estado_ocupacion")
    ->leftJoin("servicio_bano as bano", "bano.Id_departamento", "=", "departamento.Id_departamento")
    ->leftJoin("servicio_cama as cama", "cama.Id_departamento", "=", "departamento.Id_departamento")
    ->leftJoin("plantas_pisos as planta", "planta.Id_departamento", "=", "departamento.Id_departamento")

//consulta crudas de sql que me cuenta los registros que esten en las plantas para poder mostrarlos en la vista 
//planta baja
    ->selectRaw("(SELECT COUNT(*) AS pisobajo FROM `departamento` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_departamento = departamento.Id_departamento
    WHERE piso.Nombre_planta = 'Planta baja'
    AND departamento.Id_locacion =  ".$Id_locacion.") as totaldepaspisobajo")
//piso 1
    ->selectRaw("(SELECT COUNT(*) AS piso1 FROM `departamento` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_departamento = departamento.Id_departamento
    WHERE piso.Nombre_planta = 'Planta 1'
    AND departamento.Id_locacion =  ".$Id_locacion.") as totaldepaspiso1")
//piso 2
    ->selectRaw("(SELECT COUNT(*) AS piso2 FROM `departamento` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_departamento = departamento.Id_departamento
    WHERE piso.Nombre_planta = 'Planta 2'
    AND departamento.Id_locacion =  ".$Id_locacion.") as totaldepaspiso2")
//piso 3
    ->selectRaw("(SELECT COUNT(*) AS piso3 FROM `departamento` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_departamento = departamento.Id_departamento
    WHERE piso.Nombre_planta = 'Planta 3'
    AND departamento.Id_locacion =  ".$Id_locacion.") as totaldepaspiso3")
//piso 4
    ->selectRaw("(SELECT COUNT(*) AS piso4 FROM `departamento` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_departamento = departamento.Id_departamento
    WHERE piso.Nombre_planta = 'Planta 4'
    AND departamento.Id_locacion =  ".$Id_locacion.") as totaldepaspiso4")
//piso 5
    ->selectRaw("(SELECT COUNT(*) AS piso5 FROM `departamento` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_departamento = departamento.Id_departamento
    WHERE piso.Nombre_planta = 'Planta 5'
    AND departamento.Id_locacion =  ".$Id_locacion.") as totaldepaspiso5")
//piso 6
    ->selectRaw("(SELECT COUNT(*) AS piso6 FROM `departamento` 
    LEFT JOIN plantas_pisos AS piso ON piso.Id_departamento = departamento.Id_departamento
    WHERE piso.Nombre_planta = 'Planta 6'
    AND departamento.Id_locacion =  ".$Id_locacion.") as totaldepaspiso6")
    ->where('departamento.Id_locacion', '=', $Id_locacion)
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

return view('Departamentos.departamentos', compact('locacion', 'estatus_depa', 'departamentos'));
 
    }

//funcion que lanza la alerta de que no se pueden guardar mas habs en la casa
public function DepasLlenos(){
    Alert::info('Advertencia', 'parece que estamos llenos. si quieres añadir mas departamentos edita la locacion para tener mayor capacidad de departamentos');
    return redirect()->back();
}

//funcion para la vista del formulario de habitaciones 
public function ViewDepa($Id_locacion){
//aqui envie la vairable de "idlocacion" como parametro para obtener el id al que corresponde la hab y lo envio en el compact
         
//consultas que me traen los registros de la bd
      $estatus_depas =  Estado_ocupacion::get();
      $servicios = Servicios_estancia::get();
//aqui mando la variable de "id locacion en el metodo compact, esta variable la pondre en las rutas y en los forms"
      return view('Departamentos.agregardepa', compact('Id_locacion', 'servicios', 'estatus_depas'));
      }
   

//funcion para guardar un registro de depas 
public function DepaStore(Request $request, $Id_locacion, Locacion $locdepastotal){
try{ 
//minifuncion para el bucle de crear las depas      
//hago una consulta a la bd para checar el numero de de depas 
         $locdepastotal=DB::table('locacion')
         ->select('Id_locacion', 'Numero_total_depas','Numero_depas_actuales')
         ->where('Id_locacion', '=', $Id_locacion)
         ->get();
//declaro una variable que tomara el registro de num de depas actuales y le sumara 1 
         $aumentador = $locdepastotal[0]->Numero_depas_actuales + 1;
//hago la actualizacion del dato 
         $affected = DB::table('locacion')
         ->where('Id_locacion', '=', $Id_locacion)
         ->update(['Numero_depas_actuales' => $aumentador]);
//vuelvo a hacer una consulta a la bd
         $locdepastotal=DB::table('locacion')
         ->select('Id_locacion', 'Numero_total_depas','Numero_depas_actuales')
         ->where('Id_locacion', '=', $Id_locacion)
         ->get();
    
//info basica
      
   $agregardep = new Departamento();
   $agregardep-> Id_locacion = $Id_locacion;
   $agregardep-> Id_estado_ocupacion = $request->get('estatus');
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

      
    
    Alert::success('Exito', 'Se agrego el departamento con exito. Ya puedes cerrar esta ventana');
    return redirect()->back();
}catch(Exception $ex){
    Alert::error('Error', 'El departamento no se pudo agregar. revisa que todo este en orden');
    return redirect()->back();
    }
}



//funcion para la vista de editar locacion entera
public function ViewEditarDepa($Id_departamento){
      
   $departamentos = Departamento::findOrFail($Id_departamento );
   

   $estatus_depas =  Estado_ocupacion::get();

   $servicios = Servicios_estancia::get();

   $relacion_servicio =  DB::table('relacion_servicios') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_bano= DB::table('servicio_bano') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_cama= DB::table('servicio_cama')
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $fotos = DB::table('fotos_lugares')
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $plantas_pisos = DB::table('plantas_pisos')
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();
   

   return view('Departamentos.editardepa', compact('plantas_pisos','fotos', 'servicio_cama', 'servicio_bano', 'relacion_servicio', 'servicios', 'estatus_depas', 'departamentos'));        
}



//funcion para guardar un registro de depas 
public function UpdateDepa(Request $request, Departamento $departamentos, Servicio_bano $servicio_bano,  Servicio_cama $servicio_cama, Plantas_pisos $plantas_pisos){
   try{ 
     
   //info basica
   
   
      $departamentos-> Id_estado_ocupacion = $request->estatus;
      $departamentos-> Nombre_depa = $request->nombre;
      $departamentos-> Capacidad_personas = $request->cap_personas;
      $departamentos-> Deposito_garantia_dep = $request->garantia;
      $departamentos-> Precio_noche = $request->precio_noche;
      $departamentos-> Precio_semana = $request->precio_semana;
      $departamentos-> Precio_catorcedias = $request->precio_catorcena;
      $departamentos-> Precio_mes = $request->precio_mes;
      $departamentos-> Espacio_superficie = $request->superficie;
      $departamentos-> Habitaciones_total = $request->recamaras;
      $departamentos-> Nota = $request->nota;
      $departamentos-> Descripcion = $request->descripcion;
      $departamentos -> Cobro_p_ext_mes_d = $request->p_ext_mes;
      $departamentos -> Cobro_p_ext_catorcena_d = $request->p_ext_catorce;
      $departamentos -> Cobro_p_ext_noche_d = $request->p_ext_noche;
      $departamentos -> Cobro_anticipo_mes_d = $request->c_anticipo_mes;
      $departamentos -> Cobro_anticipo_catorcena_d = $request->c_anticipo_catorce;
      $departamentos-> Camas_juntas = $request->camas_juntas;
      $departamentos->save();
   

      //planta
      $plantas_pisos-> Id_departamento = $departamentos -> Id_departamento;
      $plantas_pisos-> Nombre_planta = $request->get('planta');
      $plantas_pisos->save();

      //servicios
      //borra los registros que tienen el id de la hab   
         $borrarservicios = DB::table('relacion_servicios')
         ->where('Id_departamento', '=', $departamentos -> Id_departamento)
         ->delete();
         //hace un nuevo insert para colocar los datos actualizados
         foreach ($request->arregloServicios as $arreglo) {
            DB::table('relacion_servicios')->insert(
            ['Id_departamento' => $departamentos -> Id_departamento, 'Id_servicios_estancia' => $arreglo]);
         }
               

      //baños
      //llamo a la variable que contiene el id y lo guardo en el campo 

      $servicio_bano-> Id_departamento = $departamentos -> Id_departamento;
      $servicio_bano-> Bano_compartido = $request->b_compartido;
      $servicio_bano-> Bano_medio = $request->b_medio;
      $servicio_bano-> Bano_completo = $request->b_completo;
      $servicio_bano-> Bano_completo_RL = $request->b_completorl;
      $servicio_bano->save();
      //camas   

      $servicio_cama-> Id_departamento = $departamentos -> Id_departamento;
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
         $nombreImagen = $departamentos->Id_departamento.'_'.$departamentos->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen1;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_departamento' => $departamentos->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
      }}
      
         
   //array que guarda la foto 2
      $this->validate($request, array(
      'img2' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img2');
   
      if($image != ''){
         $nombreImagen = $departamentos->Id_departamento.'_'.$departamentos->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen2;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_departamento' => $departamentos->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
      }}
      
   
   //array que guarda la foto 3
      $this->validate($request, array(
      'img3' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img3');
   
      if($image != ''){
         $nombreImagen = $departamentos->Id_departamento.'_'.$departamentos->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen3;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_departamento' => $departamentos->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
      }}
      
   
   //array que guarda la foto 4
      $this->validate($request, array(
      'img4' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img4');
   
      if($image != ''){
         $nombreImagen = $departamentos->Id_departamento.'_'.$departamentos->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen4;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_departamento' => $departamentos->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
      }}
      
   
   //array que guarda la foto 5
      $this->validate($request, array(
      'img5' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img5');
   
      if($image != ''){
         $nombreImagen = $departamentos->Id_departamento.'_'.$departamentos->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen5;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_departamento' => $departamentos->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
      }}
      
   
   //array que guarda la foto 6
      $this->validate($request, array(
      'img6' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
      ));
      $image = $request->file('img6');
   
      if($image != ''){
         $nombreImagen = $departamentos->Id_departamento.'_'.$departamentos->Nombre_depa.'_'.rand(). '.' . $image->getClientOriginalExtension();
         $base64Img = $request->nuevaImagen6;
         $base_to_php = explode(',',$base64Img);
         $data = base64_decode($base_to_php[1]);
   //aviso         
   //en esta parte tendre que cambiarlo al momento de subirlo al host porque la ruta ya no seria local "intentar con uploads/locacion/"           
         $filepath = 'C:/xampp/htdocs/alohate/public/uploads/departamentos/'.$nombreImagen;
         $guardarImagen = file_put_contents($filepath, $data);
   
         if ($guardarImagen !== false) {
            DB::table('fotos_lugares')->insert(
            ['Id_departamento' => $departamentos->Id_departamento, 'Ruta_lugar' => $nombreImagen]);
      }}
      
       Alert::success('Exito', 'Se actualizo el departamento con exito. Ya puedes cerrar esta ventana');
       return redirect()->back();
   }catch(Exception $ex){
       Alert::error('Error', 'El departamento no se pudo actualizar. revisa que todo este en orden');
       return redirect()->back();
       }
   }
   
   

//funcion que destruye las imagenes en la pagina de editar activo
public function DestroyImgDepa( Request $request, $Id_foto_lugar ) {
   try{
$departamentos=DB::table('fotos_lugares')
->where('Id_foto_lugar', '=', $Id_foto_lugar )
->get();
//se hace un json encode y decode para transformar los datos en json 
$departamentos = json_decode(json_encode($departamentos));
         
foreach($departamentos as $departamento)
{
   if(!empty($departamento->Ruta_lugar)){
      File::delete('C:\xampp\htdocs\alohate\public\uploads\departamentos'.$departamento->Ruta_lugar);}           
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
public function ViewDesactDepa($Id_departamento){

   $departamento = DB::table('departamento')
   ->select('est.Nombre_estado','Id_departamento', 'Id_locacion','Id_colaborador',
   'Nombre_depa','Capacidad_personas', 'Deposito_garantia_dep', 'Precio_noche', 'Precio_semana', 'Precio_catorcedias', 
   'Precio_mes', 'Habitaciones_total', 'Encargado','Espacio_superficie','Nota','Descripcion')
//joins que me vinculan a otras tablas para hacer consultas 
  ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "departamento.Id_estado_ocupacion")
  ->where('departamento.Id_departamento', '=', $Id_departamento)
  ->get();
   
   return view('Departamentos.desactivardepa', compact('departamento'));
}


public function DesactivarDepa( $Id_departamento){
   try{

         $departamento = DB::table('departamento')
         ->where('Id_departamento', '=', $Id_departamento )
         ->update(['Id_estado_ocupacion' => 3]);

   Alert::success('Exito', 'Se ha desactivado el departamento con exito');
   return redirect()->back();

   }catch(Exception $ex){
   Alert::error('Error', 'El departamento no se pudo desactivar revisa que todo este en orden');
   return redirect()->back();
}  
   
}




//funcion que muestra los detalles de una habitacion libre
public function DetallesDepaLibre($Id_locacion, $Id_departamento ){

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

   $departamentos = DB::table('departamento')
    ->select('departamento.Id_departamento', 'departamento.Id_locacion','est.Nombre_estado', 'Id_colaborador',
    'Nombre_depa','Capacidad_personas', 'Deposito_garantia_dep', 'Precio_noche', 'Precio_semana', 
    'Precio_catorcedias', 'Precio_mes', 'Habitaciones_total', 
    'Encargado','Espacio_superficie','Nota','Descripcion',
    'Cobro_p_ext_mes_d','Cobro_p_ext_catorcena_d','Cobro_p_ext_noche_d',
    'Cobro_anticipo_mes_d','Cobro_anticipo_catorcena_d','Camas_juntas', 
    'bano.Bano_compartido', 'bano.Bano_medio', 'bano.Bano_completo', 'bano.Bano_completo_RL',
    'cama.Cama_individual', 'cama.Cama_matrimonial', 'cama.Litera_individual', 'cama.Litera_matrimonial',
    'cama.Litera_ind_mat','cama.Cama_kingsize', 'planta.Nombre_planta')
//joins que me vinculan a otras tablas para hacer consultas 
    ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "departamento.Id_estado_ocupacion")
    ->leftJoin("servicio_bano as bano", "bano.Id_departamento", "=", "departamento.Id_departamento")
    ->leftJoin("servicio_cama as cama", "cama.Id_departamento", "=", "departamento.Id_departamento")
    ->leftJoin("plantas_pisos as planta", "planta.Id_departamento", "=", "departamento.Id_departamento")
   ->where('departamento.Id_departamento', '=', $Id_departamento)
   ->get();


   switch($departamentos[0]->Nombre_estado){
   case 'Desocupada':

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
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
      // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
      // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
      // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
      ->where('departamento.Id_departamento', '=', $Id_departamento)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();


   $servicios = Servicios_estancia::get();

   $files = DB::table('fotos_lugares')
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $relacion_servicio =  DB::table('relacion_servicios') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_bano= DB::table('servicio_bano') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_cama= DB::table('servicio_cama')
   ->where('Id_departamento', '=', $Id_departamento)
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
   ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
   // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
   // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
   ->where('departamento.Id_departamento', '=', $Id_departamento)
   ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
   ->get();


/*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
$lugar_reservacion= DB::table('lugares_reservados')
->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
         'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
->where('Id_departamento', '=', $Id_departamento)
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


   if($departamentos[0]->Nombre_estado  == "Desocupada"){
      return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
   }else{
      if($departamentos[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
         return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($departamentos[0]->Nombre_estado == "Desactivada"){
            return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($departamentos[0]->Nombre_estado == "Rentada"){
               return view('Departamentos.detallesdepaocupado', compact('locacion', 'departamentos', 'monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
            }else{
               if($departamentos[0]->Nombre_estado == "Reservada"){
                  return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
               }else{
                  if($departamentos[0]->Nombre_estado == "Pago por confirmar"){
                     return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
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
      'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
      'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
      'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
      'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
      'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
      )
      ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
      // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
      // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
      // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
      ->where('departamento.Id_departamento', '=', $Id_departamento)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();


   $servicios = Servicios_estancia::get();

   $files = DB::table('fotos_lugares')
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $relacion_servicio =  DB::table('relacion_servicios') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_bano= DB::table('servicio_bano') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_cama= DB::table('servicio_cama')
   ->where('Id_departamento', '=', $Id_departamento)
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
   ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
   // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
   // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
   ->where('departamento.Id_departamento', '=', $Id_departamento)
   ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
   ->get();


/*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
   $lugar_reservacion= DB::table('lugares_reservados')
   ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
            'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
   ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
   ->where('Id_departamento', '=', $Id_departamento)
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


   if($departamentos[0]->Nombre_estado  == "Desocupada"){
      return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
   }else{
      if($departamentos[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
         return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($departamentos[0]->Nombre_estado == "Desactivada"){
            return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($departamentos[0]->Nombre_estado == "Rentada"){
               return view('Departamentos.detallesdepaocupado', compact('locacion', 'departamentos', 'monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
            }else{
               if($departamentos[0]->Nombre_estado == "Reservada"){
                  return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
               }else{
                  if($departamentos[0]->Nombre_estado == "Pago por confirmar"){
                     return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
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
      'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
      'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
      'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
      'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
      'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
      )
      ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
      // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
      // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
      // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
      ->where('departamento.Id_departamento', '=', $Id_departamento)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();


   $servicios = Servicios_estancia::get();

   $files = DB::table('fotos_lugares')
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $relacion_servicio =  DB::table('relacion_servicios') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_bano= DB::table('servicio_bano') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_cama= DB::table('servicio_cama')
   ->where('Id_departamento', '=', $Id_departamento)
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
   ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
   // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
   // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
   ->where('departamento.Id_departamento', '=', $Id_departamento)
   ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
   ->get();


/*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
$lugar_reservacion= DB::table('lugares_reservados')
->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
         'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
->where('Id_departamento', '=', $Id_departamento)
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


   if($departamentos[0]->Nombre_estado  == "Desocupada"){
      return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
   }else{
      if($departamentos[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
         return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($departamentos[0]->Nombre_estado == "Desactivada"){
            return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($departamentos[0]->Nombre_estado == "Rentada"){
               return view('Departamentos.detallesdepaocupado', compact('locacion', 'departamentos', 'monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
            }else{
               if($departamentos[0]->Nombre_estado == "Reservada"){
                  return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
               }else{
                  if($departamentos[0]->Nombre_estado == "Pago por confirmar"){
                     return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
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
      'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
      'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
      'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
      'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
      'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
      )
      ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
      // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
      // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
      // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
      ->where('departamento.Id_departamento', '=', $Id_departamento)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();


   $servicios = Servicios_estancia::get();

   $files = DB::table('fotos_lugares')
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $relacion_servicio =  DB::table('relacion_servicios') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_bano= DB::table('servicio_bano') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_cama= DB::table('servicio_cama')
   ->where('Id_departamento', '=', $Id_departamento)
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
   ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
   // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
   // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
   ->where('departamento.Id_departamento', '=', $Id_departamento)
   ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
   ->get();


/*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
$lugar_reservacion= DB::table('lugares_reservados')
->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
         'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
->where('Id_departamento', '=', $Id_departamento)
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


   if($departamentos[0]->Nombre_estado  == "Desocupada"){
      return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
   }else{
      if($departamentos[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
         return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($departamentos[0]->Nombre_estado == "Desactivada"){
            return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($departamentos[0]->Nombre_estado == "Rentada"){
               return view('Departamentos.detallesdepaocupado', compact('locacion', 'departamentos', 'monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
            }else{
               if($departamentos[0]->Nombre_estado == "Reservada"){
                  return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
               }else{
                  if($departamentos[0]->Nombre_estado == "Pago por confirmar"){
                     return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
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
      'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
      'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
      'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
      'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
      'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso'
      )
      ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
      // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
      // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
      // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
      ->where('departamento.Id_departamento', '=', $Id_departamento)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();


      $servicios = Servicios_estancia::get();

      $files = DB::table('fotos_lugares')
      ->where('Id_departamento', '=', $Id_departamento)
      ->get();

      $relacion_servicio =  DB::table('relacion_servicios') 
      ->where('Id_departamento', '=', $Id_departamento)
      ->get();

      $servicio_bano= DB::table('servicio_bano') 
      ->where('Id_departamento', '=', $Id_departamento)
      ->get();

      $servicio_cama= DB::table('servicio_cama')
      ->where('Id_departamento', '=', $Id_departamento)
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
      ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
      // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
      // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
      // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
      ->where('departamento.Id_departamento', '=', $Id_departamento)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();


      /*esta consulta me trae los datos de la tabla de los lugares reservados despues con un leftjoin hago la relacion 
      para que solo me traiga las reservaciones que sean de la casa la cual estoy seleccionando desde el sistema */
      $lugar_reservacion= DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion', 'Id_habitacion', 'Id_locacion',
               'Id_local', 'Id_departamento', 'Id_cliente', 'reserva.Start_date', 'reserva.End_date', 'reserva.Title' )
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->where('Id_departamento', '=', $Id_departamento)
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


   if($departamentos[0]->Nombre_estado  == "Desocupada"){
      return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
   }else{
      if($departamentos[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
         return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($departamentos[0]->Nombre_estado == "Desactivada"){
            return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($departamentos[0]->Nombre_estado == "Rentada"){
               return view('Departamentos.detallesdepaocupado', compact('locacion', 'departamentos', 'monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles'));
            }else{
               if($departamentos[0]->Nombre_estado == "Reservada"){
                  return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
               }else{
                  if($departamentos[0]->Nombre_estado == "Pago por confirmar"){
                     return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
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
         'departamento.Id_departamento', 'departamento.Id_locacion','departamento.Id_estado_ocupacion', 
         'departamento.Id_colaborador','departamento.Nombre_depa',
         'departamento.Capacidad_personas', 'departamento.Deposito_garantia_dep', 
         'departamento.Precio_noche', 'departamento.Precio_semana', 
         'departamento.Precio_catorcedias', 'departamento.Precio_mes', 
         'departamento.Habitaciones_total', 'departamento.Encargado',
         'departamento.Espacio_superficie','departamento.Nota',
         'departamento.Descripcion','departamento.Cobro_p_ext_mes_d',
         'departamento.Cobro_p_ext_catorcena_d','departamento.Cobro_p_ext_noche_d',
         'departamento.Cobro_anticipo_mes_d','departamento.Cobro_anticipo_catorcena_d',
         'departamento.Camas_juntas','loc.Nombre_locacion')
          ->leftJoin("estado_ocupacion as est", "est.Id_estado_ocupacion", "=", "lugares_reservados.Id_estado_ocupacion")
          ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
          ->leftJoin("locacion as loc", "loc.Id_locacion", "=", "departamento.Id_locacion")
          ->where('departamento.Id_departamento', '=', $Id_departamento)
          ->get();
   
      $Id_reservacion = $consulta_reserva[0]->Id_reservacion;

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
   // ->where('reserva.Id_reservacion', '=', $Id_reservacion)
   ->where('dep.Id_departamento', '=', $Id_departamento)
   ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
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
$monto_por_p_extras = 0;
//variable que me hace la suma total            
$suma_monto = $monto_por_dias + $detallereserva[0]->Deposito_garantia_dep + $detallereserva[0]->Monto_uso_cochera;

// return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservadep', compact('detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto'));
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

//return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservadep', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
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
        
//return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservadep', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
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
        
//return view('Reservaciones y rentas.Detalles_reservas_rentas.detallesreservadep', compact('detallereserva', 'diasredondeados', 'monto_por_p_extras', 'monto_por_dias', 'suma_monto'));
//return "es $redondeomes mes";
break;
}


      $lugar_cliente_reservado = DB::table('lugares_reservados')
      ->select('lugares_reservados.Id_lugares_reservados', 'lugares_reservados.Id_reservacion','lugares_reservados.Id_habitacion',
      'lugares_reservados.Id_locacion','lugares_reservados.Id_local', 'lugares_reservados.Id_departamento','lugares_reservados.Id_cliente',
      'cliente.Nombre','cliente.Apellido_paterno','cliente.Apellido_materno', 'cliente.Email',
      'cliente.Numero_celular','cliente.Ciudad','cliente.Estado','cliente.Pais',
      'cliente.Ref1_nombre','cliente.Ref2_nombre', 'cliente.Ref1_celular','cliente.Ref2_celular',
      'cliente.Ref1_parentesco','cliente.Ref2_parentesco','cliente.Motivo_visita',
      'cliente.Lugar_motivo_visita','cliente.Foto_cliente','cliente.INE_frente','cliente.INE_reverso',
      'reserva.Start_date','reserva.Total_de_personas',
      )
      ->leftJoin("cliente", "cliente.Id_cliente", "=", "lugares_reservados.Id_cliente")
      ->leftJoin("reservacion as reserva", "reserva.Id_reservacion", "=", "lugares_reservados.Id_reservacion")
      ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
      // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
      // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
      // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
      ->where('departamento.Id_departamento', '=', $Id_departamento)
      ->whereRaw('"'.date("Y-m-d").'" Between reserva.Start_date and reserva.End_date')
      ->get();


   $servicios = Servicios_estancia::get();

   $files = DB::table('fotos_lugares')
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $relacion_servicio =  DB::table('relacion_servicios') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_bano= DB::table('servicio_bano') 
   ->where('Id_departamento', '=', $Id_departamento)
   ->get();

   $servicio_cama= DB::table('servicio_cama')
   ->where('Id_departamento', '=', $Id_departamento)
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
   ->leftJoin("departamento", "departamento.Id_departamento", "=", "lugares_reservados.Id_departamento")
   // ->selectRaw("(SELECT COUNT(*) AS lugar1 FROM `lugares_reservados` 
   // LEFT JOIN departamento ON departamento.Id_departamento = lugares_reservados.Id_departamento
   // WHERE departamento.Id_departamento =  ".$Id_departamento.") as totalclientes")
   ->where('departamento.Id_departamento', '=', $Id_departamento)
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
->where('Id_departamento', '=', $Id_departamento)
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


   if($departamentos[0]->Nombre_estado  == "Desocupada"){
      return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
   }else{
      if($departamentos[0]->Nombre_estado == "En Mantenimiento/Limpieza"){
         return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
      }else{
         if($departamentos[0]->Nombre_estado == "Desactivada"){
            return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
         }else{
            if($departamentos[0]->Nombre_estado == "Rentada"){
               return view('Departamentos.detallesdepaocupado', compact('locacion', 'departamentos', 'monto_por_p_extras', 'detallereserva', 'diasredondeados', 'monto_por_dias', 'suma_monto', 'servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado', 'reglafiles', 'contratofiles'));
            }else{
               if($departamentos[0]->Nombre_estado == "Reservada"){
                  return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
               }else{
                  if($departamentos[0]->Nombre_estado == "Pago por confirmar"){
                     return view('Departamentos.detallesdepalibre', compact('locacion', 'departamentos','servicios','servicio_cama','servicio_bano','relacion_servicio', 'files', 'reservacion','lugar_cliente_reservado'));
                  }
               }
            }
         }

      }
   }
  

   break;
   }
  
}



}
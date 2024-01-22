<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocacionController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\UsuariosController;


//ruta para el menu
Route::get('menu', 'MenuController@VistaMenu')->name('menu');

//ruta para el home
Route::get('home', 'HomeController@Vistahome')->name('home');

//ruta para login
Route::get('/', 'LoginController@VistaLogin')->name('login');
Route::post('/LoginVerify', 'LoginController@LoginVerify')->name('login.LoginVerify');
Route::post('/cerrarSesion', 'LoginController@cerrarSesion')->name('login.cerrarSesion');
Route::post('/verificarCodigo', 'LoginController@verificarCodigo')->name('login.verificarCodigo');
Route::get('/vistaCodigo/{user}', 'LoginController@vistaCodigo')->name('login.vistaCodigo');
Route::post('/verificarUserPass', 'LoginController@verificarUserPass')->name('login.verificarUserPass');

//prueba de alertas
Route::get('view_alertas', 'LocacionController@ViewAlertas')->name('alertas');


//rutas de los servicios:
//ruta para guardar el registro de un nuevo servicio en casa entera
Route::post('store_servicio', 'LocacionController@StoreServicio')->name('storeservicio');
//ruta vista que redirije desde casa entera 
Route::get('view_servicio_entera', 'LocacionController@ViewServicioEntera')->name('viewservicio_entera');
//ruta vista que redirije desde casa por secciones 
Route::get('view_servicio_secciones', 'LocacionController@ViewServicioSecciones')->name('viewservicio_secciones');
//ruta para los detalles de la locacion
Route::get('detalles_loc/{locacion}', 'LocacionController@ViewDetalleLoc')->name('detalle_loc');


//rutas para locaciones:
//ruta general para las locaciones
Route::get('locacion', 'LocacionController@VistaLocaciones')->name('locacion');
//ruta para vista modal de las habitaciones de locaciones
Route::get('hab', 'Hab_LocController@VistaHabLoc')->name('hab_loc');



//rutas para las locaciones enteras: 
//ruta para la vista de editar las locaciones enteras
Route::get('editar_locacion/{locacion}', 'LocacionController@ViewEditarLoc')->name('view_editar_loc');
//ruta que edita los registros de la locacion entera
Route::put('update_loc/{locacion}/{servicio_bano}/{servicio_cama}', 'LocacionController@UpdateEntera')->name('update_loc');
//ruta para borrar/destruir las fotos en el campo editar locacion
Route::post('borrarimg/{Id_foto_lugar}','LocacionController@DestroyImg')->name('destroyimg');
//ruta para la vista de desactivar las locaciones
Route::get('view_desactivar_locacion/{locacion}', 'LocacionController@ViewDesactivarLoc')->name('view_desactivar_loc');
//ruta que desactiva la locacion
Route::put('desactivar_locacion/{locacion}', 'LocacionController@DesactivarLocacion')->name('desactivar_locacion');
//ruta para seleccionar si se quiere añadir una locacion entera o por secciones
Route::get('agregar_locacion', 'LocacionController@IntroLoc')->name('agregar_loc');
//ruta boton de vista para agregar casa entera
Route::get('locacion_entera', 'LocacionController@ViewEntera')->name('entera');
//ruta para guardar el registro de la casa entera
Route::post('store_entera', 'LocacionController@StoreEntera')->name('storeentera');


// ruta para las locaciones por secciones:
//ruta para la vista de agregar casa por secciones
Route::get('locacion_seccion', 'LocacionController@ViewSecciones')->name('secciones');
//ruta que guarda y redirije a la vista de intro habs
Route::post('store_seccion', 'LocacionController@StoreSecciones')->name('storeseccion');
//ruta que edita los registros de la locacion por secciones
Route::put('update_loc_secc/{locacion}', 'LocacionController@UpdateSecciones')->name('update_loc_secc');
//intro a agregar habs por secciones
Route::get('intro_habs/{idlocacion}', 'LocacionController@IntroHabs')->name('intro_habs');
//ruta boton que muestra el formulario de las habitaciones de locacion por seccion 
Route::get('View_habs/{idlocacion}', 'LocacionController@ViewHabs')->name('viewhabs');
//ruta que guarda los registros de las habs por secciones
Route::post('store_habs/{idlocacion}', 'LocacionController@HabsStore')->name('habsstore');
//ruta boton que muestra el formulario de los depas de locacion por seccion 
Route::get('view_depas/{idlocacion}', 'LocacionController@ViewDepas')->name('viewdepas');
//ruta que guarda los registros de los depas por secciones
Route::post('store_depas/{idlocacion}', 'LocacionController@DepasStore')->name('storedepas');
//ruta boton que muestra el formulario de los locales de locacion por seccion 
Route::get('view_locales/{idlocacion}', 'LocacionController@ViewLocales')->name('viewlocal');
//ruta que guarda los registros de los depas por secciones
Route::post('store_locs/{idlocacion}', 'LocacionController@LocsStore')->name('storeLocs');


//rutas de reservaciones para una casa
//ruta boton que muestra el form para agregar una reservacion de una casa con un cliente existente
Route::get('view_reservacasaoc/locacion/{Id_locacion}/myurl', 'ReservacionRentasController@ViewReservaCasaOC')->name('viewreservacasaoc');
//ruta para guardar el registro de una nueva reservacion para una casa con un cliente existente
Route::post('store_reservacasaoc/locacion/{Id_locacion}', 'ReservacionRentasController@StoreReservaCasaOC')->name('storereservacasaoc');
//ruta para la busqueda automatica de los clientes para una reserva de una casa
Route::post('view_reservacasaoc/locacion/{Id_locacion}/myurl', 'ReservacionRentasController@ShowClientesCasa');
//ruta para el form de hacer una reservacion con un nuevo cliente en una casa
Route::get('view_reserva_nc_casa/locacion/{Id_locacion}', 'ReservacionRentasController@ViewReservaCasaNC')->name('viewreservacasanc');
//ruta para guardar el registro de una nueva reservacion para una casa con un cliente nuevo
Route::post('store_reservacasanc/locacion/{Id_locacion}', 'ReservacionRentasController@StoreReservaCasaNC')->name('storereservacasanc');

//ruta boton que muestra la vista para los detalles de una reservacion de un depa
Route::get('view_detalles_reservacasa/reserva/{Id_reservacion}/casa/{Id_locacion}/lugar/{Id_lugares_reservados}', 'ReservacionRentasController@DetalleReservaCasa')->name('detallesreservacasa');

//ruta boton que muestra la vista para editar una reservacion de una casa
Route::get('view_editar_reservacasa/reserva/{Id_reservacion}/loc/{Id_locacion}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@EditarReservaCasa')->name('editarreservacasa');
//ruta que edita el registro de una reservacion de una casa
Route::put('update_reservacasa/{reservacion}/{lugar_reservado}/{Id_locacion}', 'ReservacionRentasController@UpdateReservaCasa')->name('update_reservacasa');
//ruta para la busqueda automatica de los clientes para editar una reserva de una casa
Route::post('view_editar_reservacasa/reserva/{Id_reservacion}/loc/{Id_locacion}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@ShowClientesEditCasa');

//ruta boton que muestra la vista para editar una reservacion de una casa con nuevo cliente
Route::get('view_editar_reservacasanc/reserva/{Id_reservacion}/loc/{Id_locacion}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@EditarReservaCasaNC')->name('editarreservacasanc');
//ruta que edita el registro de una reservacion de una casa con nuevo cliente
Route::put('update_reservacasanc/{reservacion}/{lugar_reservado}/{Id_locacion}', 'ReservacionRentasController@UpdateReservaCasaNC')->name('update_reservacasanc');















//rutas para habitaciones
//ruta boton que muestra las habitaciones de la casa
Route::get('view_habitaciones/{idlocacion}', 'HabitacionController@ViewHabitaciones')->name('habitaciones');
//ruta boton que muestra el formulario de agregar una hab
Route::get('View_hab/{idlocacion}', 'HabitacionController@ViewHab')->name('viewhab');
//ruta que guarda un nuevo registro de hab dentro de la vista de habs
Route::post('store_hab/{idlocacion}', 'HabitacionController@HabStore')->name('habstore');
//mensajes de habs llenas para el bton de agregar hab
Route::get('error_agregar_hab', 'HabitacionController@HabsLlenas')->name('habllena');
//ruta boton que muestra los detalles de la habitacion libre
Route::get('detalles_hab_libre/locacion/{idlocacion}/habitacion/{Id_habitacion}', 'HabitacionController@DetallesHabLibre')->name('detalleshablibre');
//ruta boton que muestra la vista para editar un registro de hab
Route::get('view_editar_hab/{Id_habitacion}', 'HabitacionController@ViewEditarHab')->name('view_editar_hab');
//ruta que edita el registro de la hab
Route::put('update_hab/{habitaciones}/servicio/{servicio_bano}/cama/{servicio_cama}/planta/{plantas_pisos}', 'HabitacionController@UpdateHab')->name('update_hab');
//ruta para borrar/destruir las fotos en el campo editar hab
Route::post('borrarimghab/{Id_foto_lugar}','HabitacionController@DestroyImgHab')->name('destroyimghab');
//ruta de la vista para desactivar la hab
Route::get('view_desactivar_hab/{Id_habitacion}', 'HabitacionController@ViewDesactHab')->name('view_desactivar_hab');
//ruta que desactiva la hab
Route::put('desactivar_hab/{habitaciones}', 'HabitacionController@DesactivarHab')->name('desactivar_hab');

//rutas de reservaciones para una hab
//ruta boton que muestra las habitaciones de la casa
Route::get('view_reservaciones_rentas', 'ReservacionRentasController@ViewReservacionRentas')->name('reservaciones_renta');

//ruta boton que muestra el form para agregar una reservacion de una habitacion con un cliente existente
Route::get('view_reservahaboc/locacion/{Id_locacion}/habitacion/{Id_habitacion}/myurl', 'ReservacionRentasController@ViewReservaHabOC')->name('viewreservahaboc');
//ruta para guardar el registro de una nueva reservacion para una habitacion con un cliente existente
Route::post('store_reservahaboc/locacion/{Id_locacion}/habitacion/{Id_habitacion}', 'ReservacionRentasController@StoreReservaHabOC')->name('storereservahaboc');
//ruta para la busqueda automatica de los clientes para una reserva de una habitacion
Route::post('view_reservahaboc/locacion/{Id_locacion}/habitacion/{Id_habitacion}/myurl', 'ReservacionRentasController@ShowClientesHab');
//ruta para el form de hacer una reservacion con un nuevo cliente en hab
Route::get('view_reserva_nc_hab/locacion/{Id_locacion}/habitacion/{Id_habitacion}', 'ReservacionRentasController@ViewReservaHabNC')->name('viewreservahabnc');
//ruta para guardar el registro de una nueva reservacion para una habitacion con un cliente nuevo
Route::post('store_reservahabnc/locacion/{Id_locacion}/habitacion/{Id_habitacion}', 'ReservacionRentasController@StoreReservaHabNC')->name('storereservahabnc');

//ruta boton que muestra la vista para los detalles de una reservacion de una hab
Route::get('view_detalles_reservahab/reserva/{Id_reservacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}', 'ReservacionRentasController@DetalleReservaHab')->name('detallesreservahab');
//ruta boton que muestra la vista para editar una reservacion de hab
Route::get('view_editar_reservahab/reserva/{Id_reservacion}/loc/{Id_locacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@EditarReservaHab')->name('editarreservahab');
//ruta para la busqueda automatica de los clientes para editar una reserva de una habitacion
Route::post('view_editar_reservahab/reserva/{Id_reservacion}/loc/{Id_locacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@ShowClientesEditHab');
//ruta que edita el registro de una reservacion de una hab
Route::put('update_reservahab/{reservacion}/{lugar_reservado}/{Id_locacion}/{Id_habitacion}', 'ReservacionRentasController@UpdateReservaHab')->name('update_reservahab');
//ruta boton que muestra la vista para editar una reservacion de hab con un nuevo cliente
Route::get('view_editar_reservahabnc/reserva/{Id_reservacion}/loc/{Id_locacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@EditarReservaHabNC')->name('editarreservahabnc');
//ruta que edita el registro de una reservacion de una hab co un nuevo cliente
Route::put('update_reservahabnc/{reservacion}/{lugar_reservado}/{Id_locacion}/{Id_habitacion}', 'ReservacionRentasController@UpdateReservaHabNC')->name('update_reservahabnc');

//eliminar
//ruta para la busqueda automatica de los clientes para una reserva de una habitacion
Route::get('view_delete_reserva_hab', 'ReservacionRentasController@ViewDeleteReservaHab')->name('viewdeletereservahab');;

//rutas de renta para una hab
//ruta para la busqueda automatica de los clientes
Route::post('view_rentar_hab_c1/reserva/{Id_reservacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@ShowClientesEditHab');
//ruta para el form de el primer cliente para pasar a rentar 
Route::get('view_rentar_hab_c1/reserva/{Id_reservacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@ViewCliente1Hab')->name('viewrentarc1hab');
//ruta que guarda el registro del primero cliente
Route::put('store_rentar_hab_c1/cliente/{cliente}/reserva/{Id_reservacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}', 'ReservacionRentasController@StoreRentarHabC')->name('storerentarhabc');

//ruta para la busqueda automatica de los clientes 
Route::post('view_rentar_hab_c2/reserva/{Id_reservacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@ShowClientesEditHab');
//ruta para el form de el segundo cliente para pasar a rentar 
Route::get('view_rentar_hab_c2/reserva/{Id_reservacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@ViewIntroHabC2')->name('viewintroc2hab');
//ruta que guarda el registro en el caso de que haya una segunda persona que se hospedara en el lugar
Route::post('store_rentar_hab_c2/reserva/{Id_reservacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}', 'ReservacionRentasController@StoreRentarHabC2')->name('storerentarhabc2');

//ruta para la vista del form que toma los datos del lugar para pasar a rentar
Route::get('view_rentar_hab/reserva/{Id_reservacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}', 'ReservacionRentasController@ViewRentarHab')->name('viewrentarhab');
//ruta que guarda el registro del form de los datos del lugar
Route::post('store_rentar_hab/reserva/{Id_reservacion}/hab/{Id_habitacion}/lugar/{Id_lugares_reservados}', 'ReservacionRentasController@StoreRentarHab')->name('storerentarhab');












//rutas para departamentos
//ruta boton que muestra los depas de la casa 
Route::get('view_departamentos/{idlocacion}', 'DepartamentoController@ViewDepartamentos')->name('departamentos');
//mensajes de error
Route::get('error_agregar_depa', 'DepartamentoController@DepasLlenos')->name('depasllenos');
//ruta boton que muestra el formulario de los depas
Route::get('View_depa/{idlocacion}', 'DepartamentoController@ViewDepa')->name('viewdepa');
//ruta que crea un nuevo registro de depa
Route::post('store_depa/{idlocacion}', 'DepartamentoController@DepaStore')->name('depastore');
//ruta de la vista para desactivar la depa
Route::get('view_desactivar_depa/{Id_departamento}', 'DepartamentoController@ViewDesactDepa')->name('view_desactivar_depa');
//ruta que desactiva la depa
Route::put('desactivar_depa/{departamento}', 'DepartamentoController@DesactivarDepa')->name('desactivar_depa');
//ruta boton que muestra los detalles del depa libre
Route::get('detalles_depa_libre/locacion/{idlocacion}/depa/{Id_departamento}', 'DepartamentoController@DetallesDepaLibre')->name('detallesdepalibre');
//ruta boton que muestra la vista para editar un registro de hab
Route::get('view_editar_depa/{Id_departamento}', 'DepartamentoController@ViewEditarDepa')->name('view_editar_depa');
//ruta que edita el registro de la hab
Route::put('update_depa/{departamentos}/servicio/{servicio_bano}/cama/{servicio_cama}/planta/{plantas_pisos}', 'DepartamentoController@UpdateDepa')->name('update_depa');

//ruta para borrar/destruir las fotos en el campo editar locacion
Route::post('borrar_img_depa/{Id_foto_lugar}','DepartamentoController@DestroyImgDepa')->name('destroyimgdepa');


//rutas de reservaciones para un depa
//ruta boton que muestra el form para agregar una reservacion de un depa con un cliente existente
Route::get('view_reservadepoc/locacion/{Id_locacion}/departamento/{Id_departamento}/myurl', 'ReservacionRentasController@ViewReservaDepOC')->name('viewreservadepoc');
//ruta para guardar el registro de una nueva reservacion para un depa con un cliente existente
Route::post('store_reservadepoc/locacion/{Id_locacion}/departamento/{Id_departamento}', 'ReservacionRentasController@StoreReservaDepOC')->name('storereservadepoc');
//ruta para la busqueda automatica de los clientes para una reserva de un depa
Route::post('view_reservadepoc/locacion/{Id_locacion}/departamento/{Id_departamento}/myurl', 'ReservacionRentasController@ShowClientesDep');
//ruta para el form de hacer una reservacion con un nuevo cliente en un depa
Route::get('view_reserva_nc_dep/locacion/{Id_locacion}/departamento/{Id_departamento}', 'ReservacionRentasController@ViewReservaDepNC')->name('viewreservadepnc');
//ruta para guardar el registro de una nueva reservacion para un depa con un cliente nuevo
Route::post('store_reservadepnc/locacion/{Id_locacion}/departamento/{Id_departamento}', 'ReservacionRentasController@StoreReservaDepNC')->name('storereservadepnc');

//ruta boton que muestra la vista para los detalles de una reservacion de un depa
Route::get('view_detalles_reservadep/reserva/{Id_reservacion}/dep/{Id_departamento}/lugar/{Id_lugares_reservados}', 'ReservacionRentasController@DetalleReservaDep')->name('detallesreservadep');
//ruta boton que muestra la vista para editar una reservacion de un depa
Route::get('view_editar_reservadep/reserva/{Id_reservacion}/loc/{Id_locacion}/dep/{Id_departamento}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@EditarReservaDep')->name('editarreservadep');
//ruta que edita el registro de una reservacion de un depa
Route::put('update_reservadep/{reservacion}/{lugar_reservado}/{Id_locacion}/{Id_departamento}', 'ReservacionRentasController@UpdateReservaDep')->name('update_reservadep');
//ruta para la busqueda automatica de los clientes para editar una reserva de undepa
Route::post('view_editar_reservadep/reserva/{Id_reservacion}/loc/{Id_locacion}/dep/{Id_departamento}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@ShowClientesEditDep');

//ruta boton que muestra la vista para editar una reservacion de un depa
Route::get('view_editar_reservadepnc/reserva/{Id_reservacion}/loc/{Id_locacion}/dep/{Id_departamento}/lugar/{Id_lugares_reservados}/myurl', 'ReservacionRentasController@EditarReservaDepNC')->name('editarreservadepnc');
//ruta que edita el registro de una reservacion de un depa
Route::put('update_reservadepnc/{reservacion}/{lugar_reservado}/{Id_locacion}/{Id_departamento}', 'ReservacionRentasController@UpdateReservaDepNC')->name('update_reservadepnc');

















//rutas para locales 
//ruta boton que muestra los locales de la casa
Route::get('view_locales_loc/{idlocacion}', 'LocalController@ViewLocalesLoc')->name('locales');
//mensajes de locales llenas para el bton de agregar local
Route::get('error_agregar_loc', 'LocalController@LocsLlenos')->name('loclleno');
//ruta boton que muestra el formulario para agregar un local
Route::get('view_local/{idlocacion}', 'LocalController@ViewLocal')->name('viewlocales');
//ruta que guarda los registros del local
Route::post('store_loc/{idlocacion}', 'LocalController@LocStore')->name('storeLoc');
//ruta de la vista para desactivar el local
Route::get('view_desactivar_local/{Id_local}', 'LocalController@ViewDesactLocal')->name('view_desactivar_local');
//ruta que desactiva el local
Route::put('desactivar_local/{local}', 'LocalController@DesactivarLocal')->name('desactivar_local');
//ruta boton que muestra los detalles del local libre
Route::get('detalles_local_libre/locacion/{idlocacion}/local/{Id_local}', 'LocalController@DetallesLocalLibre')->name('detalleslocallibre');
//ruta boton que muestra la vista para editar un registro de local
Route::get('view_editar_local/{idlocacion}', 'LocalController@ViewEditarLocal')->name('view_editar_local');
//ruta que edita el registro del  local
Route::put('update_local/{locales}/servicio/{servicio_bano}/planta/{plantas_pisos}', 'LocalController@UpdateLocal')->name('update_local');
//ruta para borrar/destruir las fotos en el campo editar local
Route::post('borrarimglocal/{Id_foto_lugar}','LocalController@DestroyImgLocal')->name('destroyimglocal');










Route::controller(UsuariosController::class)->group(function (){
    Route::get('user', 'index')->name('user.index');
    Route::get('user/create', 'create')->name('user.create');
    Route::post('user/store', 'store')->name('user.store');
    Route::get('user/{id}', 'show')->name('user.show');
    Route::get('user/edit/{id}', 'edit')->name('user.edit');
    Route::post('user/update/{id}', 'update')->name('user.update');
    Route::post('user/destroy/{id}', 'destroy')->name('user.destroy');    
});




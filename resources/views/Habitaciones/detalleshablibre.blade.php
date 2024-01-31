      <!--=============== CSS local===============-->
      <link rel="stylesheet" href="{{ asset('assets/detalles_hab_libre.css') }}" >
      <!--=============== CSS web ===============-->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">      
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
<!--=============== ESTILOS ===============-->
      <style>
         .modal-header{
         background-color: #fd8a50;
         border: 0;
         
         }    
         .tamaño_lg{
               height: 90%;
         }
   
         .tamaño_sm{
               height: 50%;
         }
   
         .modal-title{
               color: white;
         }
   
        
         .modal-lg {
               max-width: 90%;
               width: 70%;
         }
               
         .modal-sm {
               max-width: 70%;
               width: auto;
                     
         }
   
         </style>

@extends('layouts.menu_layout')
@section('MenuPrincipal')

<!--=============== ENCABEZADO ===============-->

   <header class="encabezado">
      <div class="overlay">
         <h1>Detalles De La Habitacion</h1>
         <br>
         <a href="{{route('habitaciones', $locacion[0]->Id_locacion)}}" type="button" class="boton">Regresar</a>
      </div>
   </header>
   


<!--=============== TITULO DE NOMBRE Y TIPO DE RENTA===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Nombre De La Habitacion:</h5>
              <div class="gris">
               <h6> {{$habitaciones[0]->Nombre_hab}}</h6>
              </div>
            </div>

            <div class="centrar_texto">
               <p><h5>Locacion A La Que Pertenece:
               </h5>
              <div class="gris">
               <h6>Locacion {{$locacion[0]->Nombre_locacion}}</h6>
              </div>
            </div>

            <div class="centrar_texto">
                <p><h5>Nota</h5>
               <div class="gris">
               <h6> {{$habitaciones[0]->Nota}}</h6>
               </div>
             </div>
       
         </div>
   </div>
</div>

<!--=============== TABLA CAP DE PERSONAS, ESTADO DE OCUPACION Y PORCENTAJE DE OCUPACION ===============-->    
<div class="interno_padre_l">
   <div class="interno_hijo_l">
         <div class="interno_l">
          <div class="container_tabla">
              <table class="table table-striped table-hover">
                     <thead>
                           <tr>
                                 <th>Capacidad De Personas</th>
                                 <th>Estatus De La Hab</th>
                                 <th>Planta</th>
                           </tr>
                     </thead>
                     <tbody>
                           <tr>
                                 <td data-label="Capacidad De Personas"> {{$habitaciones[0]->Capacidad_personas}}</td>
                                 <td data-label="Estatus De La Hab">
                                    @if($habitaciones[0]->Nombre_estado == "En Mantenimiento/Limpieza")
                                    <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$habitaciones[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($habitaciones[0]->Nombre_estado == "Desocupada")
                                    <div class="gris"><h6 style="color: mediumseagreen">{{$habitaciones[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($habitaciones[0]->Nombre_estado == "Reservada")
                                    <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$habitaciones[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($habitaciones[0]->Nombre_estado == "Desactivada")
                                    <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$habitaciones[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($habitaciones[0]->Nombre_estado == "Rentada")
                                    <div class="gris"><h6 style="color: rgb(33, 36, 207)">{{$habitaciones[0]->Nombre_estado}}</h6></div>
                                    @endif
                                    @if($habitaciones[0]->Nombre_estado == "Pago por confirmar")
                                    <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$habitaciones[0]->Nombre_estado}}</h6></div>
                                    @endif
                                 </td>
                                 <td data-label="Planta">{{$habitaciones[0]->Nombre_planta}} </td>   
                           </tr>
                     </tbody>
              </table>
             
          </div>
         </div>
   </div>
</div>


<!--=============== PRECIOS ===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Precios</h5>
                  <div class="gris">
                     <h6>Precio Por Noche: ${{$habitaciones[0]->Precio_noche}}</h6>
                     <h6>Precio Por Semana: ${{$habitaciones[0]->Precio_semana}}</h6>
                     <h6>Precio Por Catorcena: ${{$habitaciones[0]->Precio_catorcedias}}</h6>
                     <h6>Precio Por Mes: ${{$habitaciones[0]->Precio_mes}}</h6>
                     <h6>Deposito De Garantia: ${{$habitaciones[0]->Deposito_garantia_hab}}</h6>
                     <h6>Monto De Apartado(-2semanas): ${{$habitaciones[0]->Cobro_anticipo_catorcena_h}}MXN</h6>
                     <h6>Monto De Apartado(+3semanas): ${{$habitaciones[0]->Cobro_anticipo_mes_h}}MXN</h6>
                  </div>
               </p>
            </div>
         </div>
   </div>
</div>

<!--=============== camas ===============-->
   <div class="seccion_padre_b">
      <div class="seccion_hijo_t"> 
            <div class="seccion_interno_1">
               <div class="centrar_texto">
                  <p><h5>Camas</h5></p>
                     <div class="gris">
                           @if($habitaciones[0]->Cama_individual != "0")
                  
                           @else
                           <h6>Camas Individuales: {{$habitaciones[0]->Cama_individual}} </h6>
                           @endif
                           @if($habitaciones[0]->Cama_matrimonial < "0")
                  
                           @else
                           <h6>Camas Matrimoniales: {{$habitaciones[0]->Cama_matrimonial}} </h6>
                           @endif
                           @if($habitaciones[0]->Litera_individual < "0")
                  
                           @else
                           <h6>Literas Individuales: {{$habitaciones[0]->Litera_individual}} </h6>
                           @endif
                           @if($habitaciones[0]->Litera_matrimonial < "0")
                  
                           @else
                           <h6>Literas Matrimoniales: {{$habitaciones[0]->Litera_matrimonial}} </h6>
                           @endif
                           @if($habitaciones[0]->Litera_ind_mat < "0")
                  
                           @else
                           <h6>Literas Ind/Mat: {{$habitaciones[0]->Litera_ind_mat}} </h6>
                           @endif
                           @if($habitaciones[0]->Cama_kingsize < "0")
                  
                           @else
                           <h6>Camas Kingsizes: {{$habitaciones[0]->Cama_kingsize}} </h6>
                           @endif
                  
                     </div>
               </div>
            </div>
      </div>
   </div>
      
   
   
   
   <!--=============== baños===============-->
   <div class="seccion_padre_b">
      <div class="seccion_hijo_t"> 
            <div class="seccion_interno_1">
               <div class="centrar_texto">
                  <p><h5>Baños</h5>
                     <div class="gris">
                           @if($habitaciones[0]->Bano_compartido < "0")
                  
                           @else
                           <h6>Baños Compartidos: {{$habitaciones[0]->Bano_compartido}} </h6>
                           @endif
                           @if($habitaciones[0]->Bano_medio < "0")
                           
                           @else
                           <h6>Medios Baños: {{$habitaciones[0]->Bano_medio}} </h6>
                           @endif
                           @if($habitaciones[0]->Bano_completo < "0")
                           
                           @else
                           <h6>Baños Completos: {{$habitaciones[0]->Bano_completo}} </h6>
                           @endif
                           @if($habitaciones[0]->Bano_completo_RL < "0")
                           
                           @else
                           <h6>Baños Completos Con Regadera Y Lavamanos: {{$habitaciones[0]->Bano_completo_RL}} </h6>
                           @endif
                           
                     </div>
               </div>
            </div>
      </div>
   </div>
   
   
   

<!--=============== UBICACION===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Ubicacion</h5>
                  <div class="gris">
                        <h6>Direccion: {{$locacion[0]->Calle}} #{{$locacion[0]->Numero_ext}} {{$locacion[0]->Colonia}}</h6>
                        <h6>Zona En Donde Se Encuentra: {{$locacion[0]->Zona_ciudad}} </h6>
                        <h6>Link De Google Maps: <a href="{{$locacion[0]->Ubi_google_maps}}">Enlace</a></h6>
                  </div>
               </p>
            </div>
         </div>
   </div>
</div>
   
<!--=============== DESCRIPCION ===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Descripcion</h5>
                  <div class="gris">
                     <h6>{{$habitaciones[0]->Descripcion}}</h6>
                  </div>
               </p>
            </div>
         </div>
   </div>
</div>



<!--=============== SERVICIOS ===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
              <p><h5>Servicios</h5>
               <i id="despliegue_servicios" class="fa-solid fa-chevron-down" onclick="presentar_servicios()"></i>
               <i id="ocultar_servicios" class="fa-solid fa-chevron-up" onclick="esconder_servicios()"></i>
            </p>
            </div>
            <div class="detalle_servicios" id="detalle_servicios">
                  <div class="gris">
                     <div class="centrar_texto">
<!--sin servicio -->
                  <br>
                  <div class="subtitulo">
                     <label> Sin Servicios </label>
                     <br>
                     <i id="despliegue_sinservicio" class="fa-solid fa-chevron-down" onclick="presentar_sinservicio()"></i>
                     <i id="ocultar_sinservicio" class="fa-solid fa-chevron-up" onclick="esconder_sinservicio()"></i>
                  </div>
                  <br>
                  <div class="detalle_sinservicio" id="detalle_sinservicio">
                     <div class="input-group">
                        @foreach($servicios as $servicio)
                              @if($servicio -> Seccion_servicio == "Sin Servicios")
                                 <div class="centrar">
                                    <label for="{{$servicio->Nombre_servicio}}" class="option_item">
                                       <input type="checkbox" class="checkbox"
                                       @foreach($relacion_servicio as $relacion)
                                             @if($relacion->Id_servicios_estancia == $servicio->Id_servicios_estancia)
                                                checked 
                                             @endif
                                       @endforeach
                                       name="arregloServicios[]" id="{{$servicio->Nombre_servicio}}" value="{{$servicio->Id_servicios_estancia}}" disabled="disabled">
                                    <div class="option_inner seleccionar{{rand(1,3)}}">
                                          <div class="tickmark"></div>
                                          <div style="display: none">{{$servicio->Seccion_servicio}}</div>
                                          <div class="icon"><img src="{{asset($servicio->Ruta_servicio)}}" alt="" class="tamano_icono"></div>
                                          <div class="name">{{$servicio->Nombre_servicio}}</div>
                                    </div>
                                    </label>
                                 </div>
                              @endif
                        @endforeach
                     </div>
                  </div>
<!--Cocina -->
                     <br>
                     <div class="subtitulo">
                        <label> Cocina </label>
                        <br>
                        <i id="despliegue_cocina" class="fa-solid fa-chevron-down" onclick="presentar_cocina()"></i>
                        <i id="ocultar_cocina" class="fa-solid fa-chevron-up" onclick="esconder_cocina()"></i>
                     </div>
                  <br>
                    <div class="detalle_cocina" id="detalle_cocina">
                        <div class="input-group">
                            @foreach($servicios as $servicio)
                                 @if($servicio -> Seccion_servicio == "Cocina")
                                     <div class="centrar">
                                         <label for="{{$servicio->Nombre_servicio}}" class="option_item">
                                            <input type="checkbox" class="checkbox"
                                            @foreach($relacion_servicio as $relacion)
                                                @if($relacion->Id_servicios_estancia == $servicio->Id_servicios_estancia)
                                                    checked 
                                                @endif
                                            @endforeach
                                            name="arregloServicios[]" id="{{$servicio->Nombre_servicio}}" value="{{$servicio->Id_servicios_estancia}}" disabled="disabled">
                                         <div class="option_inner seleccionar{{rand(1,3)}}">
                                             <div class="tickmark"></div>
                                             <div style="display: none">{{$servicio->Seccion_servicio}}</div>
                                             <div class="icon"><img src="{{asset($servicio->Ruta_servicio)}}" alt="" class="tamano_icono"></div>
                                             <div class="name">{{$servicio->Nombre_servicio}}</div>
                                         </div>
                                         </label>
                                     </div>
                                 @endif
                             @endforeach
                         </div>
                    </div>
<!--Lavanderia -->
                    <div class="subtitulo">
                        <label> Lavanderia </label>
                        <br>
                            <i id="despliegue_lavanderia" class="fa-solid fa-chevron-down" onclick="presentar_lavanderia()"></i>
                            <i id="ocultar_lavanderia" class="fa-solid fa-chevron-up" onclick="esconder_lavanderia()"></i>   
                    </div>
                  <br>
                    <div class="detalle_lavanderia" id="detalle_lavanderia">
                        
                            <div class="input-group">
                               @foreach($servicios as $servicio)
                                    @if($servicio -> Seccion_servicio == "Lavanderia")
                                        <div class="centrar">
                                            <label for="{{$servicio->Nombre_servicio}}" class="option_item">
                                                <input type="checkbox" class="checkbox"
                                                @foreach($relacion_servicio as $relacion)
                                                    @if($relacion->Id_servicios_estancia == $servicio->Id_servicios_estancia)
                                                        checked
                                                    @endif
                                                @endforeach
                                                name="arregloServicios[]" id="{{$servicio->Nombre_servicio}}" value="{{$servicio->Id_servicios_estancia}}" disabled="disabled">
                                            <div class="option_inner seleccionar{{rand(1,3)}}">
                                                <div class="tickmark"></div>
                                                <div style="display: none">{{$servicio->Seccion_servicio}}</div>
                                                <div class="icon"><img src="{{asset($servicio->Ruta_servicio)}}" alt="" class="tamano_icono"></div>
                                                <div class="name">{{$servicio->Nombre_servicio}}</div>
                                            </div>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
    
                    </div>
    

    
<!--servicios extras-->
                    <div class="subtitulo">
                        <label> Servicios Extras </label>
                        <br>
                            <i id="despliegue_otro_s" class="fa-solid fa-chevron-down" onclick="presentar_otro_s()"></i>
                            <i id="ocultar_otro_s" class="fa-solid fa-chevron-up" onclick="esconder_otro_s()"></i>   
                    </div>
                  <br>
                    <div class="detalle_otro_s" id="detalle_otro_s">
    
                         <div class="input-group">
                               @foreach($servicios as $servicio)
                                    @if($servicio -> Seccion_servicio == "Servicios Extras")
                                        <div class="centrar">
                                            <label for="{{$servicio->Nombre_servicio}}" class="option_item">
                                                <input type="checkbox" class="checkbox"
                                            @foreach($relacion_servicio as $relacion)
                                                @if($relacion->Id_servicios_estancia == $servicio->Id_servicios_estancia)
                                                    checked
                                                @endif
                                            @endforeach
                                                 name="arregloServicios[]" id="{{$servicio->Nombre_servicio}}" value="{{$servicio->Id_servicios_estancia}}" disabled="disabled">
                                            <div class="option_inner seleccionar{{rand(1,3)}}">
                                                <div class="tickmark"></div>
                                                <div style="display: none">{{$servicio->Seccion_servicio}}</div>
                                                <div class="icon"><img src="{{asset($servicio->Ruta_servicio)}}" alt="" class="tamano_icono"></div>
                                                <div class="name">{{$servicio->Nombre_servicio}}</div>
                                            </div>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                        </div>
                    </div>
                     </div>
                  </div>
                  </div>
         </div>
   </div>
</div>



<!--=============== CALENDARIO DE RESERVAS  ===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Calendario De Reservaciones</h5>
                  <div class="gris">
                     <div id='calendar'></div>
                  </div>
               </p>
            </div>
         </div>
   </div>
</div>


<!--=============== BOTONES DE ACCION  ===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Botones De Accion</h5>
                  <div class="gris">
                     @if($habitaciones[0]->Nombre_estado == "En Mantenimiento/Limpieza")
                     <button class="btn btn-primary clickForm" href="{{route('view_editar_hab', $habitaciones[0]->Id_habitacion)}}">Editar</button>
                     <button class="btn btn-danger clickForm" href="{{route('view_desactivar_hab', $habitaciones[0]->Id_habitacion)}}" >Desactivar</button>
                     @endif
                     @if($habitaciones[0]->Nombre_estado == "Desocupada")
                     <button class="btn btn-secondary clickForm" href="{{route('viewreservahaboc', [$locacion[0]->Id_locacion, $habitaciones[0]->Id_habitacion])}}">Reservar</button>
                     <button class="btn btn-primary clickForm" href="{{route('view_editar_hab', $habitaciones[0]->Id_habitacion)}}">Editar</button>
                     <button class="btn btn-danger clickForm" href="{{route('view_desactivar_hab', $habitaciones[0]->Id_habitacion)}}" >Desactivar</button>
                     <button class="btn btn-success">Agendar Cita</button>
                     <a href="{{route('limpieza.create')}}?tipoLocacion=Habitación&id={{$habitaciones[0]->Id_habitacion}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
                     @endif

                     @if($habitaciones[0]->Nombre_estado == "Reservada")
                     <button class="btn btn-secondary clickForm" href="{{route('viewreservahaboc', [$locacion[0]->Id_locacion, $habitaciones[0]->Id_habitacion])}}">Reservar</button>
                     <button class="btn btn-primary clickForm" href="{{route('view_editar_hab', $habitaciones[0]->Id_habitacion)}}">Editar</button>
                     <a href="{{route('limpieza.create')}}?tipoLocacion=Habitación&id={{$habitaciones[0]->Id_habitacion}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
                     @endif

                     @if($habitaciones[0]->Nombre_estado == "Pago por confirmar")
                     <button class="btn btn-secondary clickForm" href="{{route('viewreservahaboc', [$locacion[0]->Id_locacion, $habitaciones[0]->Id_habitacion])}}">Reservar</button>
                     <button class="btn btn-primary clickForm" href="{{route('view_editar_hab', $habitaciones[0]->Id_habitacion)}}">Editar</button>
                     <a href="{{route('limpieza.create')}}?tipoLocacion=Habitación&id={{$habitaciones[0]->Id_habitacion}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
                     @endif

                     @if($habitaciones[0]->Nombre_estado == "Desactivada")
                     <button class="btn btn-primary clickForm" href="{{route('view_editar_hab', $habitaciones[0]->Id_habitacion)}}">Editar</button>
                     <a href="{{route('limpieza.create')}}?tipoLocacion=Habitación&id={{$habitaciones[0]->Id_habitacion}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
                     @endif

                     @if($habitaciones[0]->Nombre_estado == "Rentada")
                     <button class="btn btn-primary clickForm" href="{{route('view_editar_hab', $habitaciones[0]->Id_habitacion)}}">Editar</button>
                     <a href="{{route('limpieza.create')}}?tipoLocacion=Habitación&id={{$habitaciones[0]->Id_habitacion}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
                     @endif
                  </div>
               </p>
            </div>
         </div>
   </div>
</div>

<!--=============== COTIZACION  ===============-->
<form action="">
   <div class="seccion_padre_b">
      <div class="seccion_hijo_t"> 
            <div class="seccion_interno_1">
               <div class="centrar_texto">
                  <p><p><h5>Cotizacion</h5></p>
                  <i id="despliegue_cotizacion" class="fa-solid fa-chevron-down" onclick="presentar_cotizacion()"></i>
                  <i id="ocultar_cotizacion" class="fa-solid fa-chevron-up" onclick="esconder_cotizacion()"></i>
                  </p>
               </div>
            <div class="detalle_cotizacion" id="detalle_cotizacion">
               <div class="centrar_texto">
                  
                     <div class="centrar_texto">
                     <p>cotiza para compartir la informacion con el cliente</p>
                     <p>indica los días que quieras apartar</p>
                     </div>
                     <div class="gris">
                        DE: <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio">
                        A: <input type="date" class="form-control" name="fecha_termino" id="fecha_termino">
                     </div>
                     <div class="centrar_texto">
                        <p class="gris">
                        Se aplicara un cobro por persona extra que utilice el lugar.
                        ¿cuantas personas extras habra?
                        </p>
                     </div>
                     <div class="gris">
                        <input type="number" class="form-control" placeholder="#" name="personas_extras" id="personas_extras">
                     </div>
                     <button type="submit" class="btn btn-success"> Cotizar</button>
                  </p>
               </div>
               <div class="centrar_texto">
                  <p>
                     <h6>Cobro Total Por Servicio</h6>
                     <br>
                     <div class="centrar_texto">
                        <h6>Noches</h6>
                        <h6>Personas Extras</h6>
                        <H6>Monto De Garantia</H6>
                        <h6>Total:</h6>
                        <h6>Monto De Anticipo:</h6>
                        <br>
                        <button class="btn btn-warning">Compartir Informacion</button>
                     </div>
                  </p>
               </div>
            </div>
         </div>
      </div>
   </div>
</form>



<!--=============== CARRUSEL DE FOTOS===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_f"> 
         <div class="seccion_interno_2">
            <div class="carrusel_externo">
               <div class="carrusel_interno">
                  
                  <div class="content_titulo">
                     <h5> Fotos De La Habitacion</h5>
                  </div>
                     
                     <div id="carouselExample" class="carousel slide">
                       <div class="carousel-inner">
                     
                         @forelse ($files as $file)
<!-- se realiza un if para poder extraer las fotos y que se acomoden al carrusel  -->
                         <div id="contenedor" class="carousel-item @if ($loop->index==0) active @endif">
                             <img class="imagen" src="{{asset('uploads/habitaciones/').'/'.$file->Ruta_lugar  }}" >
                         </div>
                     
                         @empty
                         @endforelse
                         
                         
                       </div>
                       <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev" >
                         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                         <span class="visually-hidden">Previous</span>
                       </button>
                       <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                         <span class="carousel-control-next-icon" aria-hidden="true"></span>
                         <span class="visually-hidden">Next</span>
                       </button>
                     </div>
                     

               </div>
            </div>
         </div>
      </div>
   </div>



<br>

  <!-- Modal para agregar locaciones -->
  <div class="modal fade" id="agregar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="margin-left:12px;">
   <div class="modal-dialog modal-lg">
     <div class="modal-content tamaño_lg">
       <div class="modal-header">
         <h2 class="modal-title fs-5" id="staticBackdropLabel">Agregar Locacion</h2>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       
       <div class="modal-body mb-0 p-0">
         <div class="embed-responsive z-depth-1-half">
             <iframe class="embed-responsive-item" src="" allowfullscreen style="height: 100%; width: 100%;"></iframe>
         </div>
        </div>
 
       <div class="modal-footer">

       </div>
     </div>
   </div>
 </div>



<!-- Modal base para cuando se usa la clase ClickForm -->
<div class="modal fade" id="modalPublic" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-left:12px;">
   <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content" style="height:90%;">

           <div class="modal-header">
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>

           <div class="modal-body mb-0 p-0">
               <div class="embed-responsive embed-responsive-21by9" id="aparecerModal">                            
               </div>
           </div>

       </div>
   </div>
</div>


<!-- Modal base pequeño para cuando se usa la clase ClickForm -->
<div class="modal fade" id="modalPublic" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-left:12px;">
   <div class="modal-dialog modal-sm" role="document">
       <div class="modal-content" style="height:90%;">

          <div class="modal-header">
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>

           <div class="modal-body mb-0 p-0">
               <div class="embed-responsive embed-responsive-21by9" id="Modalpequeño">                            
               </div>
           </div>

       </div>
   </div>
</div>


<!--=============== scripts ===============-->

<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ url('js/detalles_habitacion.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>


<script>
//codigo de javascript que abre los modals de fotos y eliminar registros
   $('.clickForm').on('click', function (index) {
      if ($(this).attr('href') == $('#foto').attr('src')) {
         $('#modalPublic').modal('show');
      }else{
         $('#foto').remove();
         $('#aparecerModal').append('<iframe id="foto" class="embed-responsive-item" src="'+$(this).attr('href')+'" allowfullscreen style="height: 100%; width: 100%;"></iframe>');
         $('#modalPublic').modal('show');
      }
});


//funcion para mostrar el calendario y sus configuraciones
document.addEventListener('DOMContentLoaded', function() {
     const calendarEl = document.getElementById('calendar');
     const calendar = new FullCalendar.Calendar(calendarEl, {
      //muestra el calendario
      initialView: 'dayGridMonth',
      //lo cambia al idioma español
       locale: 'es',
      //mostrar la info en el calendario usando la variable recibida del controlador con los datos en formato json
      events: @json($reservacion),
      eventColor: '#1760BD',
     });
     //pinta el calendario
     calendar.render();
   });


</script>

@endsection
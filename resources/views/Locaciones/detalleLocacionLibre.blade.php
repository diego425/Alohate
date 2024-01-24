      <!--=============== CSS local===============-->
      <link rel="stylesheet" href="{{ asset('assets/detalles_locacion_style.css') }}" >
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
         <h1>Detalles De La Casa</h1>
         <br>
         <a href="{{route('locacion') }}" type="button" class="boton">Regresar</a>
      </div>
   </header>
   


<!--=============== TITULO DE NOMBRE Y TIPO DE RENTA===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Nombre De La Casa:</h5>
              <div class="gris">
               <h6>{{$locacion[0]->Nombre_locacion}}</h6></p>
              </div>
            </div>

            <div class="centrar_texto">
               <p><h5>Como Se Renta La Casa:</h5>
              <div class="gris">
               <h6>{{$locacion[0]->Tipo_renta}}</h6></p>
              </div>
            </div>

            <div class="centrar_texto">
               <p><h5>Nota</h5>
              <div class="gris">
               <h6>{{$locacion[0]->Nota}}</h6></p>
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
                                 <th>Estatus De La Casa</th>
                                 <th>% De Ocupacion</th>
                           </tr>
                     </thead>
                     <tbody>
                           <tr>
                                 <td data-label="Capacidad De Personas">{{$locacion[0]->Capacidad_personas}}</td>
                                 <td data-label="Estatus De La Casa">
                                    @if($locacion[0]->Nombre_estado == "En Mantenimiento/Limpieza")
                                    <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$locacion[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locacion[0]->Nombre_estado == "Desocupada")
                                    <div class="gris"><h6 style="color: mediumseagreen">{{$locacion[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locacion[0]->Nombre_estado == "Reservada")
                                    <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$locacion[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locacion[0]->Nombre_estado == "Desactivada")
                                    <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$locacion[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locacion[0]->Nombre_estado == "Rentada")
                                    <div class="gris"><h6 style="color: rgb(105, 7, 162)">{{$locacion[0]->Nombre_estado}}</h6></div>
                                    @endif
                                    
                                    @if($locacion[0]->Nombre_estado == "Pago por confirmar")
                                    <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$locacion[0]->Nombre_estado}}</h6></div>
                                    @endif
   
                                 </td>
                                 <td data-label="% De Ocupacion">en proceso</td>   
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
                     <h6>Precio Por Noche: ${{$locacion[0]->Precio_noche}}</h6>
                     <h6>Precio Por Semana: ${{$locacion[0]->Precio_semana}}</h6>
                     <h6>Precio Por Catorcena: ${{$locacion[0]->Precio_catorcedias}}</h6>
                     <h6>Precio Por Mes: ${{$locacion[0]->Precio_mes}}</h6>
                     <h6>Deposito De Garantia: ${{$locacion[0]->Deposito_garantia_casa}}</h6>
                     <h6>Monto De Apartado(-2semanas): ${{$locacion[0]->Cobro_anticipo_catorcena_c}}MXN</h6>
                     <h6>Monto De Apartado(+3semanas): ${{$locacion[0]->Cobro_anticipo_mes_c}}MXN</h6>
                  </div>
               </p>
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
                        <h6>Calle {{$locacion[0]->Calle}} # {{$locacion[0]->Numero_ext}} Colonia {{$locacion[0]->Colonia}}</h6>
                        <h6>Zona En Donde Se Encuentra: {{$locacion[0]->Zona_ciudad}}</h6>
                        <h6>Link De Google Maps: <a href="{{$locacion[0]->Ubi_google_maps}}">Enlace</a></h6>
                  </div>
               </p>
            </div>
         </div>
   </div>
</div>
   
<!--=============== DESCRIPCION===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Descripcion</h5>
                  <div class="gris">
                     <h6>{{$locacion[0]->Descripcion}}</h6>
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
               <h5>Servicios</h5>
               
               <i id="despliegue_servicios" class="fa-solid fa-chevron-down" onclick="presentar_servicios()"></i>
               <i id="ocultar_servicios" class="fa-solid fa-chevron-up" onclick="esconder_servicios()"></i>
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
    
<!--estacionamiento-->
                    <div class="subtitulo">
                        <label> Estacionamiento </label>
                        <br>
                            <i id="despliegue_estacionamiento" class="fa-solid fa-chevron-down" onclick="presentar_estacionamiento()"></i>
                            <i id="ocultar_estacionamiento" class="fa-solid fa-chevron-up" onclick="esconder_estacionamiento()"></i>   
                    </div>
                  <br>
                    <div class="detalle_estacionamiento" id="detalle_estacionamiento">
                      
                        <div class="input-group">
                            @foreach($servicios as $servicio)
                                 @if($servicio -> Seccion_servicio == "Estacionamiento")
                                     <div class="centrar">
                                         <label for="{{$servicio->Nombre_servicio}}" class="option_item">
                                            <input type="checkbox" class="checkbox"
                                            @foreach($relacion_servicio as $relacion)
                                                @if($relacion->Id_servicios_estancia == $servicio->Id_servicios_estancia)
                                                    checked
                                                @endif
                                            @endforeach
                                            name="arregloServicios[]" id="{{$servicio->Nombre_servicio}}" value="{{$servicio->Id_servicios_estancia}}" onclick="activar_cochera()" disabled="disabled">
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
                           <br>
                        <div class="centrar_texto">
                            <p><label for="num_cochera">¿Cuantos Espacios De Cochera Tiene?</label></p>
                             @if($locacion[0]->Total_cocheras == 0)
                             <p>No Cuenta Con Espacios De Cochera</p>
                             @else
                             <p>{{$locacion[0]->Total_cocheras}}</p>
                             @endif
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

<!--=============== GESTION DE HABS/DEPAS/LOCS  ===============-->
<!--=============== AQUI TENDRE QUE HACER UNA FUNCION PARA QUE DETECTE SI LA CASA TIENE HABS/DEPAS Y LOCS  ===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Gestion De</h5>
                  <div class="gris">
                     @if($locacion[0]->Tipo_renta == "Entera")
                     <button  class="btn btn-primary" disabled>Habitaciones</button>
                     <button  class="btn btn-secondary" disabled>Departamentos</button>
                     <button  class="btn btn-success" disabled>Locales</button>
                    
                     @else($locacion[0]->Tipo_renta == "Secciones")
                     <a href="{{route('habitaciones', $locacion[0]->Id_locacion)}}" class="btn btn-primary">Habitaciones</a>
                     <a href="{{route('departamentos', $locacion[0]->Id_locacion)}}" class="btn btn-secondary">Departamentos</a>
                     <a href="{{route('locales', $locacion[0]->Id_locacion)}}" class="btn btn-success">Locales</a>
                    
                     @endif
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
                     @if($locacion[0]->Tipo_renta == "Entera")
                     <button class="btn btn-primary clickForm" href="{{route('viewreservacasaoc', $locacion[0]->Id_locacion)}}">Reservar</button>
                     <button class="btn btn-secondary">Rentar</button>
                     <button class="btn btn-primary clickForm" href="{{route('view_editar_loc', $locacion[0]->Id_locacion)}}">Editar</button>
                     <button class="btn btn-danger clickForm" href="{{route('view_desactivar_loc', $locacion[0]->Id_locacion)}}" >Desactivar</button>
                     <button class="btn btn-success">Agendar Cita</button>
                     <a href="{{route('limpieza.create')}}?tipoLocacion=Entera&id={{$locacion[0]->Id_locacion}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
                    
                     @else($locacion[0]->Tipo_renta == "Secciones")
                     <button class="btn btn-primary clickForm" href="{{route('view_editar_loc', $locacion[0]->Id_locacion)}}">Editar</button>
                     <button class="btn btn-danger clickForm" href="{{route('view_desactivar_loc', $locacion[0]->Id_locacion)}}" >Desactivar</button>
                     <a href="{{route('limpieza.create')}}?tipoLocacion=Entera&id={{$locacion[0]->Id_locacion}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
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
                     <h6> Fotos De La Casa</h6>
                  </div>
                     
                     <div id="carouselExample" class="carousel slide">
                       <div class="carousel-inner">
                     
                         @forelse ($files as $file)
<!-- se realiza un if para poder extraer las fotos y que se acomoden al carrusel  -->
                         <div id="contenedor" class="carousel-item @if ($loop->index==0) active @endif">
                             <img class="imagen" src="{{asset('uploads/locacion/').'/'.$file->Ruta_lugar  }}" >
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
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ url('js/detalles_locacion.js')}}"></script>
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
      eventColor: '#000000',
     });
     //pinta el calendario
     calendar.render();
   });


</script>

@endsection
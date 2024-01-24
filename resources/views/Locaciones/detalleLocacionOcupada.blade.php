<!--=============== CSS local===============-->
<link rel="stylesheet" href="{{ asset('assets/detalles_locacion_ocupada_style.css') }}" >
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
            <div class="centrar_texto">
               <p><h5>Nombre De La Casa:</h5></p>
               <div class="gris">
               <p><h6>{{$locacion[0]->Nombre_locacion}}</h6></p>
              </div>
            </div>

            <div class="centrar_texto">
               <p><h5>Como Se Renta La Casa:</h5></p>
              <div class="gris">
                <p><h6>{{$locacion[0]->Tipo_renta}}</h6></p>
              </div>
            </div>

            <div class="centrar_texto">
                <p><h5>Nota</h5></p>
               <div class="gris">
                 <p><h6>{{$locacion[0]->Nota}}</h6></p>
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
                                 <td data-label="Capacidad De Personas"><h6>{{$locacion[0]->Capacidad_personas}}</h6></td>
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
                                 <td data-label="% De Ocupacion"><h6>en proceso</h6></td>   
                           </tr>
                     </tbody>
              </table>
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
                         <h6>Direccion: Calle {{$locacion[0]->Calle}} # {{$locacion[0]->Numero_ext}} Colonia {{$locacion[0]->Colonia}}</h6>
                         <h6>Zona En Donde Se Encuentra: {{$locacion[0]->Zona_ciudad}}</h6>
                         <h6>Link De Google Maps: <a href="{{$locacion[0]->Ubi_google_maps}}">Enlace</a></h6>
                   </div>
                </p>
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

<!--=============== DESCRIPCION===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
            <div class="centrar_texto">
               <p><h5>Descripcion</h5></p>
               <p>
                  <div class="gris">
                     <h6>{{$locacion[0]->Descripcion}}</h6>
                  </div>
               </p>
            </div>
   </div>
</div>

<!--=============== SERVICIOS ===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
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
                <br>
                
<!--Cocina -->
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




<!--=============== cuerpo tabla para los clientes que esten en la casa
   aqui tendre que hacer una funcion con un if.
   si el lugar tiene mas de un huesped se muestra la tabla con un foreach 
   sino, se muestra el otro contenido para solo mostrar 1 huesped
===============-->

@if($lugar_cliente_reservado[0]->totalclientes <= "1")


<!--=============== DATOS DEL CLIENTE  ===============-->
<div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
             <div class="centrar_texto">
                <div>
                   <p><h5>Datos Del Cliente</h5>
                   <i id="despliegue_cliente" class="fa-solid fa-chevron-down" onclick="presentar_cliente()"></i>
                   <i id="ocultar_cliente" class="fa-solid fa-chevron-up" onclick="esconder_cliente()"></i>
                   </p>
                </div>
             </div>
          <div class="detalle_cliente" id="detalle_cliente">  
                 <div class="centrar_texto">
                     <p><h5>Nombre Del Cliente:</h5></p>
                         <div class="gris">
                             <p><h6>{{$lugar_cliente_reservado[0]->Nombre}} {{$lugar_cliente_reservado[0]->Apellido_paterno}} {{$lugar_cliente_reservado[0]->Apellido_materno}}</h6></p>
                         </div>
                 </div>
                 <div class="centrar_texto">
                     <p><h5>Numero De Celular:</h5></p>
                         <div class="gris">
                             <p><h6>{{$lugar_cliente_reservado[0]->Numero_celular}}</h6></p>
                         </div>
                 </div>
                 <div class="centrar_texto">
                     <p><h5>Email:</h5></p>
                         <div class="gris">
                             <p><h6>{{$lugar_cliente_reservado[0]->Email}}</h6></p>
                         </div>
                 </div>
                 <div class="centrar_texto">
                     <p><h5>Estado De Procedencia:</h5></p>
                         <div class="gris">
                             <p><h6>{{$lugar_cliente_reservado[0]->Estado}}</h6></p>
                         </div>
                 </div>
                 <div class="centrar_texto">
                     <p><h5>Ciudad De Procedencia:</h5></p>
                         <div class="gris">
                             <p><h6>{{$lugar_cliente_reservado[0]->Ciudad}}</h6></p>
                         </div>
                 </div>
                 <div class="centrar_texto">
                     <p><h5>Pais De Procedencia:</h5></p>
                         <div class="gris">
                             <p><h6>{{$lugar_cliente_reservado[0]->Pais}}</h6></p>
                         </div>
                 </div>
                 <div class="centrar_texto">
                     <p><h5>Motivo De La Visita:</h5></p>
                         <div class="gris">
                             <p><h6>{{$lugar_cliente_reservado[0]->Motivo_visita}}</h6></p>
                         </div>
                 </div>
                 <div class="centrar_texto">
                     <p><h5>Institucion O Empresa De Procedencia:</h5></p>
                         <div class="gris">
                             <p><h6>{{$lugar_cliente_reservado[0]->Lugar_motivo_visita}}</h6></p>
                         </div>
                 </div>
                 <div class="centrar_texto">
                   <p><h5>Foto Del Cliente</h5></p>
                       <div class="gris">
                           <p><h6>{{$lugar_cliente_reservado[0]->Foto_cliente}}</h6></p>
                       </div>
                 </div>
                 <div class="centrar_texto">
                   <p><h5>Fotos De La INE</h5></p>
                       <div class="gris">
                           <p><h6>{{$lugar_cliente_reservado[0]->INE_frente}}</h6></p>
                           <p><h6>{{$lugar_cliente_reservado[0]->INE_reverso}}</h6></p>
                       </div>
               </div>
          </div>
    </div>
 </div>
 
 
 
 <!--=============== CONTACTOS DE EMERGENCIA ===============-->
 <div class="seccion_padre_b">
     <div class="seccion_hijo_t"> 
              <div class="centrar_texto">
                 <div>
                   <p><h5>Contactos De Emergencia</h5></p>
                   <i id="despliegue_emergencia" class="fa-solid fa-chevron-down" onclick="presentar_emergencia()"></i>
                   <i id="ocultar_emergencia" class="fa-solid fa-chevron-up" onclick="esconder_emergencia()"></i>
                   </p>
                </div>
              </div>
          <div class="detalle_emergencia" id="detalle_emergencia">  
 <!--=============== Persona 1 ===============-->
              <div class="centrar_texto">
                 <p><h5>Nombre De La Persona 1:</h5></p>
                     <div class="gris">
                         <p> <h6>{{$lugar_cliente_reservado[0]->Ref1_nombre}}</h6></p>
                     </div>
             </div>
 
             <div class="centrar_texto">
                 <p><h5>Numero De Celular:</h5></p>
                     <div class="gris">
                         <p> <h6>{{$lugar_cliente_reservado[0]->Ref1_celular}}</h6></p>
                     </div>
             </div>
             <div class="centrar_texto">
                 <p><h5>Parentesco</h5></p>
                     <div class="gris">
                         <p> <h6>{{$lugar_cliente_reservado[0]->Ref1_parentesco}}</h6></p>
                     </div>
             </div>
             <div class="centrar_texto">
                 <button class="btn btn-primary">Mensaje</button>
                 <button class="btn btn-secondary">Llamar</button>
             </div>
 
 <!--=============== Persona 2 ===============-->
             <div class="centrar_texto">
                 <p><h5>Nombre De La Persona 2:</h5></p>
                     <div class="gris">
                         <p> <h6>{{$lugar_cliente_reservado[0]->Ref2_nombre}}</h6></p>
                     </div>
             </div>
 
             <div class="centrar_texto">
                 <p><h5>Numero De Celular:</h5></p>
                     <div class="gris">
                         <p> <h6>{{$lugar_cliente_reservado[0]->Ref2_celular}}</h6></p>
                     </div>
             </div>
             <div class="centrar_texto">
                 <p><h5>Parentesco</h5></p>
                     <div class="gris">
                         <p> <h6>{{$lugar_cliente_reservado[0]->Ref2_parentesco}}</h6></p>
                     </div>
             </div>
             <div class="centrar_texto">
                 <button class="btn btn-primary"> Mensaje</button>
                 <button class="btn btn-secondary">Llamar</button>
             </div>
          </div>
     </div>
  </div>
  

@else($lugar_cliente_reservado[0]->totalclientes >= "2")
<div class="interno_padre_C">
    <div class="interno_hijo_C">
          <div class="interno_C">
             <div class="centrar_texto">
                <div>
                   <p><h5>Datos De Los Clientes</h5>
                   <i id="despliegue_clientes" class="fa-solid fa-chevron-down" onclick="presentar_clientes()"></i>
                   <i id="ocultar_clientes" class="fa-solid fa-chevron-up" onclick="esconder_clientes()"></i>
                   </p>
                </div>
             </div>
       <div class="detalle_clientes" id="detalle_clientes">
           <div class="container_tabla_C">
               <table class="table table-striped table-hover">
                @foreach($lugar_cliente_reservado as $lugar_cliente)
                      <thead>
                            <tr>
                                  <th>NOMBRE</th>
                                  <th>NUM. CELULAR</th>
                                  <th>EMAIL</th>
                                  <th>ESTADO PROC.</th>
                                  <th>CIUDAD PROC.</th>
                                  <th>PAIS PROC.</th>
                                  <th>MOTIVO DE VISITA</th>
                                  <th>INSTITUCION O EMPRESA</th>
                                  <th>FOTOS DE LA INE</th>
                                  <th>FOTO DEL CLIENTE</th>
                            </tr>
                      </thead>
                      <tbody>
                            <tr>
                                  <td data-label="NOMBRE">{{$lugar_cliente->Nombre}} {{$lugar_cliente->Apellido_paterno}} {{$lugar_cliente->Apellido_materno}}</td>
                                  <td data-label="NUM. CELULAR">{{$lugar_cliente->Numero_celular}}</td>
                                  <td data-label="EMAIL">{{$lugar_cliente->Email}}</td>
                                  <td data-label="ESTADO PROC.">{{$lugar_cliente->Estado}}</td>
                                  <td data-label="CIUDAD PROC.">{{$lugar_cliente->Ciudad}}</td>
                                  <td data-label="PAIS PROC.">{{$lugar_cliente->Pais}}</td>
                                  <td data-label="MOTIVO DE VISITA">{{$lugar_cliente->Motivo_visita}}</td>
                                  <td data-label="INSTITUCION O EMPRESA">{{$lugar_cliente->Lugar_motivo_visita}}</td>
                                  <td data-label="FOTOS DE LA INE">...</td>
                                  <td data-label="FOTO DEL CLIENTE">...</td>
                            </tr>
                           
                      </tbody>
                 @endforeach     
               </table>
          </div>
          </div>
       </div>
    </div>
 </div>


  <!--=============== CONTACTOS DE EMERGENCIA ===============-->
  <div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
             <div class="centrar_texto">
                <div>
                  <p><h5>Contactos De Emergencia</h5></p>
                  <i id="despliegue_emergencia" class="fa-solid fa-chevron-down" onclick="presentar_emergencia()"></i>
                  <i id="ocultar_emergencia" class="fa-solid fa-chevron-up" onclick="esconder_emergencia()"></i>
                  </p>
               </div>
             </div>
         <div class="detalle_emergencia" id="detalle_emergencia">  
<!--=============== Persona 1 ===============-->
             <div class="centrar_texto">
                <p><h5>Nombre De La Persona 1:</h5></p>
                    <div class="gris">
                        <p> <h6>{{$lugar_cliente_reservado[0]->Ref1_nombre}}</h6></p>
                    </div>
            </div>

            <div class="centrar_texto">
                <p><h5>Numero De Celular:</h5></p>
                    <div class="gris">
                        <p> <h6>{{$lugar_cliente_reservado[0]->Ref1_celular}}</h6></p>
                    </div>
            </div>
            <div class="centrar_texto">
                <p><h5>Parentesco</h5></p>
                    <div class="gris">
                        <p> <h6>{{$lugar_cliente_reservado[0]->Ref1_parentesco}}</h6></p>
                    </div>
            </div>
            <div class="centrar_texto">
                <button class="btn btn-primary">Mensaje</button>
                <button class="btn btn-secondary">Llamar</button>
            </div>

<!--=============== Persona 2 ===============-->
            <div class="centrar_texto">
                <p><h5>Nombre De La Persona 2:</h5></p>
                    <div class="gris">
                        <p> <h6>{{$lugar_cliente_reservado[0]->Ref2_nombre}}</h6></p>
                    </div>
            </div>

            <div class="centrar_texto">
                <p><h5>Numero De Celular:</h5></p>
                    <div class="gris">
                        <p> <h6>{{$lugar_cliente_reservado[0]->Ref2_celular}}</h6></p>
                    </div>
            </div>
            <div class="centrar_texto">
                <p><h5>Parentesco</h5></p>
                    <div class="gris">
                        <p> <h6>{{$lugar_cliente_reservado[0]->Ref2_parentesco}}</h6></p>
                    </div>
            </div>
            <div class="centrar_texto">
                <button class="btn btn-primary"> Mensaje</button>
                <button class="btn btn-secondary">Llamar</button>
            </div>
         </div>
    </div>
 </div>
 
@endif


<!--=============== DOCUMENTOS DE ESTANCIA===============-->
<div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
             <div class="centrar_texto">
                <div>
                  <p><h5>Documentos De Estancia</h5></p>
                  <i id="despliegue_doc_est" class="fa-solid fa-chevron-down" onclick="presentar_doc_est()"></i>
                  <i id="ocultar_doc_est" class="fa-solid fa-chevron-up" onclick="esconder_doc_est()"></i>
                  </p>
               </div>
               <div class="detalle_doc_est" id="detalle_doc_est">  
                <p>
                   <div class="gris">
                      <h6>...</h6>
                   </div>
                </p>
             </div>
            </div>
    </div>
 </div>
 
<!--=============== FOTOS DEL REGLAMENTO===============-->
<div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
             <div class="centrar_texto">
                <div>
                  <p><h5>Fotos Del Reglamento</h5></p>
                  <i id="despliegue_reglamento" class="fa-solid fa-chevron-down" onclick="presentar_reglamento()"></i>
                  <i id="ocultar_reglamento" class="fa-solid fa-chevron-up" onclick="esconder_reglamento()"></i>
                  </p>
               </div>
            </div> 
            <div class="detalle_reglamento" id="detalle_reglamento">  
               <p>
                   <div class="gris">
                      <h6>...</h6>
                   </div>
                </p>
             
             <div class="centrar_texto">
                <p><h5>Fotos Del Aviso De Privacidad</h5></p>
                <p>
                   <div class="gris">
                      <h6>...</h6>
                   </div>
                </p>
             </div>
            </div>
    </div>
 </div>
 
 <!--=============== DATOS DE ALOJAMIENTO ===============-->
<div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
             <div class="centrar_texto">
               <div>
                  <p><h5>Datos De Alojamiento</h5></p>
                  <i id="despliegue_alojamiento" class="fa-solid fa-chevron-down" onclick="presentar_alojamiento()"></i>
                  <i id="ocultar_alojamiento" class="fa-solid fa-chevron-up" onclick="esconder_alojamiento()"></i>
                  </p>
               </div>
             </div>
         <div class="detalle_alojamiento" id="detalle_alojamiento">  
             <div class="centrar_texto">
                <p><h5>Fecha De Entrada</h5></p>
                <p>
                   <div class="gris">
                      <h6>...</h6>
                   </div>
                </p>
             </div>
             <div class="centrar_texto">
                <p><h5>Fecha De Salida</h5></p>
                <p>
                   <div class="gris">
                      <h6>...</h6>
                   </div>
                </p>
             </div>
             <div class="centrar_texto">
                <p><h5>Numero De Personas</h5></p>
                <p>
                   <div class="gris">
                      <h6>...</h6>
                   </div>
                </p>
             </div>
          </div>
    </div>
 </div>

 <!--=============== TABLA DE COBRO ===============-->    
<div class="interno_padre_l">
    <div class="interno_hijo_l">
          <div class="interno_l">
            <div class="centrar_texto">
                <div>
                  <p><h5>Cobro Del Servicio</h5></p>
                  <i id="despliegue_cobro" class="fa-solid fa-chevron-down" onclick="presentar_cobro()"></i>
                  <i id="ocultar_cobro" class="fa-solid fa-chevron-up" onclick="esconder_cobro()"></i>
                  </p>
               </div>
             </div>
         <div class="detalle_cobro" id="detalle_cobro">
           <div class="container_tabla">
               <table class="table table-striped table-hover">
                      <thead>
                            <tr>
                                  <th>Tiempo Estancia</th>
                                  <th>Personas Extras</th>
                                  <th>Deposito De G.</th>
                                  <th>Cobro Total</th>
                            </tr>
                      </thead>
                      <tbody>
                            <tr>
                                  <td data-label="Tiempo Estancia">en proceso</td>
                                  <td data-label="Personas Extras">en proceso</td>
                                  <td data-label="Deposito De G.">en proceso</td>   
                                  <td data-label="Cobro Total">en proceso</td>   
                            </tr>
                      </tbody>
               </table>
           </div>
         </div>
       </div>
    </div>
 </div>
 
 
 <!--=============== EXTENDER ESTANCIA===============-->
<div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
             <div class="centrar_texto">
                <p><h5>Extender Estancia</h5></p>
                <p><h6>Si el cliente desea quedarse mas dias, puedes modificar el tiempo de estancia aqui</h6></p>
                  <div class="detalles_tiempoest">
                     <p> <h6>¿Quieres Modificar El Tiempo De Estancia?</h6> </p>
                     <input type="checkbox" class="tiempo_estancia" id="extender_t"  onclick="javascript:activar_ext_t('PC');">
                  </div>
                 <br>
                  <div id="ext_t">
                        <form action="">
                              <p><h6>Nueva Fecha De Salida</h6>
                              <input type="date" name="fecha_salida" id="fecha_salida" class="form-control">
                              </p>

                              <p><h6>Total A Pagar</h6></p>
                              <h6>...</h6>
                           <br>
                              <button type="submit" class="btn btn-success"> Guardar y Generar Nuevo Contrato</button>
                        </form>
                  </div>

             </div>
    </div>
 </div>
 

 <!--=============== DATOS DEL CONTRATO ===============-->
<div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
            <div class="centrar_texto">
                <div>
                  <p><h5>Datos Del Contrato</h5></p>
                  <i id="despliegue_contrato" class="fa-solid fa-chevron-down" onclick="presentar_contrato()"></i>
                  <i id="ocultar_contrato" class="fa-solid fa-chevron-up" onclick="esconder_contrato()"></i>
                  </p>
               </div>
            </div>
                <p>
                  <div class="detalle_contrato" id="detalle_contrato">
                   <div class="gris">
                      <h6>...</h6>
                   </div>
                  </div>
                </p>
    </div>
 </div>
 

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
 
 
<!--=============== BOTONES DE ACCION  ===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
            <div class="centrar_texto">
               <p><h5>Botones De Accion</h5></p>
                <p>
                  <div class="gris">
                     <button class="btn btn-success">Enviar Whatsapp <i class="fa fa-whatsapp" aria-hidden="true"></i></button>
                     <button class="btn btn-secondary">Llamar</button>
                     <button class="btn btn-primary">Registro De Pago</button>
                     <button class="btn btn-danger">Generar Contrato</button>
                     <button class="btn btn-info">Imprimir Contrato</button>
                     <button class="btn btn-warning">Terminar Estancia</button>
                  </div>
               </p>
            </div>

            <div class="centrar_texto">
                <p><h5>Botones De Accion Para La Casa</h5>
                   <div class="gris">
                      <button class="btn btn-primary clickForm" href="{{route('view_editar_loc', $locacion[0]->Id_locacion)}}">Editar Casa</button>
                      <a href="{{route('limpieza.create')}}?tipoLocacion=Entera&id={{$locacion[0]->Id_locacion}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
                   </div>
                </p>
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

</script>

  <!-- funcion de javascript que ayuda a los check box a poder mostrar la informacion segun el boton que se seleccione-->
  <script>
   function activar_ext_t(dato){
       switch (dato) {
           case "PC":
               if (document.getElementById('extender_t').checked) {
                   document.getElementById("ext_t").style.display = "block";
               } else{
                   document.getElementById("ext_t").style.display = "none";
               }
               
       }
   }

</script>

@endsection





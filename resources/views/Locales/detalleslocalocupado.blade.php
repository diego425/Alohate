<!--=============== CSS local===============-->
<link rel="stylesheet" href="{{ asset('assets/detalles_local_ocupado.css') }}" >
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
<!-- libreria para usar las alertas-->
@include('sweetalert::alert')
<!--=============== ENCABEZADO ===============-->

   <header class="encabezado">
      <div class="overlay">
         <h1>Detalles Del Local</h1>
         <br>
         <a href="{{route('locales', $locacion[0]->Id_locacion)}}" type="button" class="boton">Regresar</a>
      </div>
   </header>
   


<!--=============== TITULO DE NOMBRE Y TIPO DE RENTA===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Nombre Del Local:</h5>
              <div class="gris">
              <h6>{{$locales[0]->Nombre_local}}</h6>
              </div>
            </div>

            <div class="centrar_texto">
               <p><h5>Locacion A La Que Pertenece:
               </h5>
              <div class="gris">
               <h6>{{$locacion[0]->Nombre_locacion}}</h6>
              </div>
            </div>

            <div class="centrar_texto">
                <p><h5>Nota</h5>
               <div class="gris">
               <h6>{{$locales[0]->Nota}}</h6>
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
                                 <th>Superficie</th>
                                 <th>Estatus De La Hab</th>
                                 <th>Planta</th>
                           </tr>
                     </thead>
                     <tbody>
                           <tr>
                                 <td data-label="Superficie"><h6>{{$locales[0]->Espacio_superficie}} m2</h6></td>
                                 <td data-label="Estatus De La Hab">
                                    @if($locales[0]->Nombre_estado == "En Mantenimiento/Limpieza")
                                    <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$locales[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locales[0]->Nombre_estado == "Desocupada")
                                    <div class="gris"><h6 style="color: mediumseagreen">{{$locales[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locales[0]->Nombre_estado == "Reservada")
                                    <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$locales[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locales[0]->Nombre_estado == "Desactivada")
                                    <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$locales[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locales[0]->Nombre_estado == "Rentada")
                                    <div class="gris"><h6 style="color: rgb(33, 36, 207)">{{$locales[0]->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locales[0]->Nombre_estado == "Cotizada")
                                    <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$locales[0]->Nombre_estado}}</h6></div>
                                    @endif
                                 </td>
                                 <td data-label="Planta"><h6>{{$locales[0]->Nombre_planta}}</h6> </td>   
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
                     <h6>Precio De Renta: ${{$locales[0]->Precio_renta}} Anual</h6>
                     <h6>Deposito De Garantia: ${{$locales[0]->Deposito_garantia_local}}</h6>
                  </div>
               </p>
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
                         <h6>
                           @if($locales[0]->Bano_compartido < "0")
                  
                           @else
                           <h6>Baños Compartidos: {{$locales[0]->Bano_compartido}} </h6>
                           @endif
                           @if($locales[0]->Bano_medio < "0")
                           
                           @else
                           <h6>Medios Baños: {{$locales[0]->Bano_medio}} </h6>
                           @endif
                           @if($locales[0]->Bano_completo < "0")
                           
                           @else
                           <h6>Baños Completos: {{$locales[0]->Bano_completo}} </h6>
                           @endif
                           @if($locales[0]->Bano_completo_RL < "0")
                           
                           @else
                           <h6>Baños Completos Con Regadera Y Lavamanos: {{$locales[0]->Bano_completo_RL}} </h6>
                           @endif
                           
                         </h6>
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
                     <h6>{{$locales[0]->Descripcion}}</h6>
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





<!--=============== cuerpo tabla para los clientes que esten en la casa
   aqui tendre que hacer una funcion con un if.
   si el lugar tiene mas de un huesped se muestra la tabla con un foreach 
   sino, se muestra el otro contenido para solo mostrar 1 huesped
===============-->

@if($lugar_cliente_reservado[0]->Total_de_personas <= "1")


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
                <a href="https://wa.me/{{$lugar_cliente_reservado[0]->Ref1_celular}}" target="_blank" class="btn btn-success">Enviar Whatsapp <i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                <a href="tel:{{$lugar_cliente_reservado[0]->Ref1_celular}}" class="btn btn-secondary" onclick="return(navigator.userAgent.match(/ Android | iPhone | movile /i)) != null;">Llamar</a>
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
                <a href="https://wa.me/{{$lugar_cliente_reservado[0]->Ref2_celular}}" target="_blank" class="btn btn-success">Enviar Whatsapp <i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                 <a href="tel:{{$lugar_cliente_reservado[0]->Ref2_celular}}" class="btn btn-secondary" onclick="return(navigator.userAgent.match(/ Android | iPhone | movile /i)) != null;">Llamar</a>
             </div>
          </div>
     </div>
  </div>
  

@else($lugar_cliente_reservado[0]->Total_de_personas >= "2")
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
                            </tr>
                      </thead>
                      <tbody>
                        @foreach($lugar_cliente_reservado as $lugar_cliente)
                            <tr>
                                  <td data-label="NOMBRE">{{$lugar_cliente->Nombre}} {{$lugar_cliente->Apellido_paterno}} {{$lugar_cliente->Apellido_materno}}</td>
                                  <td data-label="NUM. CELULAR">{{$lugar_cliente->Numero_celular}}</td>
                                  <td data-label="EMAIL">{{$lugar_cliente->Email}}</td>
                                  <td data-label="ESTADO PROC.">{{$lugar_cliente->Estado}}</td>
                                  <td data-label="CIUDAD PROC.">{{$lugar_cliente->Ciudad}}</td>
                                  <td data-label="PAIS PROC.">{{$lugar_cliente->Pais}}</td>
                                  <td data-label="MOTIVO DE VISITA">{{$lugar_cliente->Motivo_visita}}</td>
                                  <td data-label="INSTITUCION O EMPRESA">{{$lugar_cliente->Lugar_motivo_visita}}</td>
                            </tr>
                           
                        @endforeach     
                      </tbody>
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
                <a href="https://wa.me/{{$lugar_cliente_reservado[0]->Ref1_celular}}" target="_blank" class="btn btn-success">Enviar Whatsapp <i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                <a href="tel:{{$lugar_cliente_reservado[0]->Ref1_celular}}" class="btn btn-secondary" onclick="return(navigator.userAgent.match(/ Android | iPhone | movile /i)) != null;">Llamar</a>
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
                <a href="https://wa.me/{{$lugar_cliente_reservado[0]->Ref2_celular}}" target="_blank" class="btn btn-success">Enviar Whatsapp <i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                <a href="tel:{{$lugar_cliente_reservado[0]->Ref2_celular}}" class="btn btn-secondary" onclick="return(navigator.userAgent.match(/ Android | iPhone | movile /i)) != null;">Llamar</a>
            </div>
         </div>
    </div>
 </div>
 
@endif

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
<!--=============== FOTO REGLAMENTO===============-->
                      <div class="seccion_padre_b">
                        <div class="seccion_interno_2">
                            <div id="contenedor" class="rounded mx-auto d-block">
                                <img class="imagen" src="{{asset('uploads/reglamentos_avisos/').'/'.$reglafiles[0]->Foto_reglamento  }}" >
                            </div>
                        </div>
                    </div>
                   </div>
                </p>
             
             <div class="centrar_texto">
                <p><h5>Fotos Del Aviso De Privacidad</h5></p>
                <p>
                   <div class="gris">
<!--===============FOTO AVISO DE PRIV===============-->
                      <div class="seccion_padre_b">
                        <div class="seccion_interno_2">
                            <div id="contenedor" class="rounded mx-auto d-block">
                                <img class="imagen" src="{{asset('uploads/reglamentos_avisos/').'/'.$reglafiles[0]->Foto_aviso_privacidad  }}" >
                            </div>
                        </div>
                    </div>
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
<!--=============== TABLA DATOS DE ALOJAMIENTO ===============-->    
          <div class="interno_l">
           <div class="container_tabla">
               <table class="table table-striped table-hover">
                      <thead>
                            <tr>
                               <th>Fecha De Entrada</th>
                               <th>Fecha De Salida</th>
                               <th>Personas Extras</th>
                               <th>Nombre Del Lugar</th>
                               <th>Estatus</th>
                               <th>Tipo De Cobro</th>
                               <th>No. De Cocheras Que Usara</th>
                            </tr>
                      </thead>
                      <tbody>
                            <tr>
                               <td data-label="Fecha De Entrada">...</td>
                               <td data-label="Fecha De Salida">...</td> 
                               <td data-label="Personas Extras">0</td> 
                               <td data-label="Nombre Del Lugar">local: {{$detallereserva[0]->Nombre_local}}</td>
                               <td data-label="Estatus">
                                     @if($detallereserva[0]->Nombre_estado == "Ocupada")
                                     <h6 style="color:  rgb(179, 60, 60)">{{$detallereserva[0]->Nombre_estado}}</h6>
                                     @endif
 
                                     @if($detallereserva[0]->Nombre_estado == "Desocupada")
                                     <h6 style="color: mediumseagreen">{{$detallereserva[0]->Nombre_estado}}</h6>
                                     @endif
 
                                     @if($detallereserva[0]->Nombre_estado == "Reservada")
                                     <h6 style="color: rgb(0, 140, 210)">{{$detallereserva[0]->Nombre_estado}}</h6>
                                     @endif
 
                                     @if($detallereserva[0]->Nombre_estado == "Desactivada")
                                     <h6 style="color: rgb(207, 33, 204)">{{$detallereserva[0]->Nombre_estado}}</h6>
                                     @endif
 
                                     @if($detallereserva[0]->Nombre_estado == "Rentada")
                                     <h6 style="color: rgb(33, 36, 207)">{{$detallereserva[0]->Nombre_estado}}</h6>
                                     @endif
 
                                     @if($detallereserva[0]->Nombre_estado == "Pago por confirmar")
                                     <h6 style="color: rgb(142, 122, 7)">{{$detallereserva[0]->Nombre_estado}}</h6>
                                     @endif</td>
 
                               <td data-label="Tipo De Cobro">Por: {{$detallereserva[0]->Tipo_de_cobro}}</td>
                               <td data-label="No. De Cocheras Que Usara"><i class="fa-solid fa-car-side"></i>
                                  @if($detallereserva[0]->Espacios_cochera == "")
                                  0
                                  @else
                                  {{$detallereserva[0]->Espacios_cochera}}
                                  @endif
                               </td>
 
                            </tr>
                      </tbody>
               </table>
           </div>
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
            <div class="interno_l">
                <div class="container_tabla">
                  <div class="centrar_texto">
                      <p><h5>Detalles Y Costo Total</h5></p>
                   </div>
                    <table class="table table-striped table-hover">
                           <thead>
                                 <tr>
                                      <th>Total De Noches:  </th>
                                      <th>Personas Extras: </th>
                                      <th>Monto De Garantia</th>
                                      <th>Monto Por Uso De Cocheras</th>
                                      <th style="color: red">Total A Pagar</th>
                                      @if($detallereserva[0]->Tipo_de_cobro == "Noche")  
                                      @endif
                                      @if($detallereserva[0]->Tipo_de_cobro == "Semana")
                                      <th style="color: rgb(38, 0, 255)">Monto De Anticipo</th>  
                                      @endif
                                      @if($detallereserva[0]->Tipo_de_cobro == "Catorcena")
                                      <th style="color: rgb(38, 0, 255)">Monto De Anticipo</th>
                                      @endif
                                      @if($detallereserva[0]->Tipo_de_cobro == "Mes")
                                      <th style="color: rgb(38, 0, 255)">Monto De Anticipo</th> 
                                      @endif
                                 </tr>
                           </thead>
                           <tbody>
                             
                                 <tr>
                                      <td data-label="Total De Noches: ">$</td>
                                      <td data-label="Personas Extras: 0">
                                         {{-- @if($detallereserva[0]->Tipo_de_cobro == "Noche")  
                                         $0
                                         @endif
                                         @if($detallereserva[0]->Tipo_de_cobro == "Semana")
                                         ${{$monto_por_p_extras}}
                                         @endif
                                         @if($detallereserva[0]->Tipo_de_cobro == "Catorcena")
                                         ${{$monto_por_p_extras}}
                                         @endif
                                         @if($detallereserva[0]->Tipo_de_cobro == "Mes")
                                         ${{$monto_por_p_extras}}
                                         @endif --}}
                                         0
                                      </td>
                                      <td data-label="Monto De Garantia">$</td>
                                      <td data-label="Monto Por Uso De Cocheras">
                                         @if($detallereserva[0]->Espacios_cochera == "")
                                         $0
                                         @else
                                         ${{$detallereserva[0]->Monto_uso_cochera}}
                                         @endif
                                      </td>
                                      <td data-label="Total A Pagar">$</td> 
                                      {{-- @if($detallereserva[0]->Tipo_de_cobro == "Noche")  
                                      @endif
                                      @if($detallereserva[0]->Tipo_de_cobro == "Semana")
                                      <td data-label="Monto De Anticipo">${{$detallereserva[0]->Cobro_anticipo_catorcena_d}}</td>   
                                      @endif
                                      @if($detallereserva[0]->Tipo_de_cobro == "Catorcena")
                                      <td data-label="Monto De Anticipo">${{$detallereserva[0]->Cobro_anticipo_catorcena_d}}</td>   
                                      @endif
                                      @if($detallereserva[0]->Tipo_de_cobro == "Mes")
                                      <td data-label="Monto De Anticipo">${{$detallereserva[0]->Cobro_anticipo_mes_d}}</td>   
                                      @endif --}}
                                 </tr>
                           </tbody>
                    </table>
                </div>
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
                     <input type="checkbox" class="tiempo_estancia" id="extender_t"  onclick="javascript:activar_ext_t('PC');" style="width: 20px; height:20px;">
                  </div>
                 <br>
                  <div id="ext_t">
                        {{-- <form action="{{route('updatesalidadep',[$detallereserva[0]->Id_reservacion, $departamentos[0]->Id_departamento])}}" class="form" method="POST" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                              <p><h6>Nueva Fecha De Salida</h6></p>
                              <div class="centrar">
                                <input type="date" name="up_fecha_salida" id="up_fecha_salida" class="form-control" style="width: 250px; height:45px;">
                              </div>
                           <br>
                              <button type="submit" class="btn btn-success">Guardar</button>
                        </form> --}}
                  </div>

             </div>
    </div>
 </div>
 

 <!--=============== DATOS DEL CONTRATO ===============-->
@if($contratofiles[0]->Tipo_contrato == "")

@else
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
                   
                    <div class="centrar_texto">
                        <p><h5>Datos Del Contrato</h5></p>
                     </div>
                    <div class="gris">
                        <div class="centrar_texto">
                            <p><h6>Fechas</h6></p>
                            <p>De Inicio: {{$contratofiles[0]->Fecha_inicio}}</p>
                            <p>De Termino: {{$contratofiles[0]->Fecha_termino}}</p>
                            
                            <p><h6>Tipo De Contrato</h6></p>
                            <p>{{$contratofiles[0]->Tipo_contrato}}</p>
                        </div>
                    
                    <!--=============== FOTO REGLAMENTO===============-->
                    <div class="centrar_texto">
                        <p><h5>Foto Del Contrato</h5></p>
                     </div>
                    <div class="seccion_padre_b">
                        <div class="seccion_interno_2">
                            <div id="contenedor" class="rounded mx-auto d-block">
                                <img class="imagen" src="{{asset('uploads/contratos/').'/'.$contratofiles[0]->Foto_contrato  }}" >
                            </div>
                        </div>
                    </div>
                    </div>
                  </div>
                </p>
    </div>
 </div>
 
@endif

 <!--=============== CARRUSEL DE FOTOS===============-->
<div class="seccion_padre_b">
    <div class="seccion_hijo_f"> 
          <div class="seccion_interno_2">
             <div class="carrusel_externo">
                <div class="carrusel_interno">
                   
                   <div class="content_titulo">
                      <h5> Fotos Del Local</h5>
                   </div>
                      
                      <div id="carouselExample" class="carousel slide">
                        <div class="carousel-inner">
                      
                          @forelse ($files as $file)
 <!-- se realiza un if para poder extraer las fotos y que se acomoden al carrusel  -->
                          <div id="contenedor" class="carousel-item @if ($loop->index==0) active @endif">
                              <img class="imagen" src="{{asset('uploads/locales/').'/'.$file->Ruta_lugar  }}" >
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
                     <a href="https://wa.me/{{$lugar_cliente_reservado[0]->Numero_celular}}" target="_blank" class="btn btn-success">Enviar Whatsapp <i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                     <a href="tel:{{$lugar_cliente_reservado[0]->Numero_celular}}" class="btn btn-secondary" onclick="return(navigator.userAgent.match(/ Android | iPhone | movile /i)) != null;">Llamar</a>
                     <button class="btn btn-primary">Registro De Pago</button>
                     <button class="btn btn-danger">Generar Contrato</button>
                     <button class="btn btn-info">Imprimir Contrato</button>
                     <button class="btn btn-warning">Terminar Estancia</button>
                  </div>
               </p>
            </div>

            <div class="centrar_texto">
                <p><h5>Botones De Accion Para El Local</h5>
                   <div class="gris">
                    @if($locales[0]->Nombre_estado == "Reservada")
                    <button class="btn btn-primary clickForm" href="{{route('view_editar_local', $locales[0]->Id_local )}}">Editar</button>
                    <a href="{{route('limpieza.create')}}?tipoLocacion=Local&id={{$locales[0]->Id_local}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
                    <button class="btn btn-secondary">Rentar</button>
                    @endif

                    @if($locales[0]->Nombre_estado == "Cotizada")
<button class="btn btn-primary clickForm" href="{{route('view_editar_local', $locales[0]->Id_local )}}">Editar</button>
                    <a href="{{route('limpieza.create')}}?tipoLocacion=Local&id={{$locales[0]->Id_local}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
                    <button class="btn btn-secondary">Rentar</button>
                    @endif

                    @if($locales[0]->Nombre_estado == "Rentada")
<button class="btn btn-primary clickForm" href="{{route('view_editar_local', $locales[0]->Id_local )}}">Editar</button>
                    <a href="{{route('limpieza.create')}}?tipoLocacion=Local&id={{$locales[0]->Id_local}}&Id_locacion={{$locacion[0]->Id_locacion}}" class="btn btn-warning">Reporte De MTTO.</a>
                    @endif
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
<script type="text/javascript" src="{{ url('js/detalles_habitacion.js')}}"></script>
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

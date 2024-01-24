      <!--=============== CSS local===============-->
      <link rel="stylesheet" href="{{ asset('assets/detalles_reservacion.css') }}" >
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
         <h1>Detalles De La Reservacion</h1>
         <br>
         <a href="{{route('reservaciones_renta')}}" type="button" class="boton">Regresar</a>
      </div>
   </header>
   

<!--=============== datos del cliente ===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Datos Del Cliente</h5></p>
               <p><h5>Nombre:</h5></p>
                  <div class="gris">
                    <h6>{{$detallereserva[0]->Nombre}} {{$detallereserva[0]->Apellido_paterno}} {{$detallereserva[0]->Apellido_materno}}</h6>
                  </div>
            </div>

            <div class="centrar_texto">
               <p><h5>Numero De Celular:</h5></p>
                <div class="gris">
                    <h6>{{$detallereserva[0]->Numero_celular}}</h6>
                </div>
            </div>

            <div class="centrar_texto">
                <p><h5>Email:</h5></p>
                <div class="gris">
                    <h6>{{$detallereserva[0]->Email}}</h6>
                </div>
             </div>
         </div>
   </div>
</div>

<!--=============== TITULO DE NOMBRE Y TIPO DE RENTA===============-->
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Nombre De La Casa:</h5>
              <div class="gris">
               <h6> {{$detallereserva[0]->Nombre_locacion}}</h6>
              </div>
            </div>


            <div class="centrar_texto">
                <p><h5>Nota</h5>
               <div class="gris">
               <h6> {{$detallereserva[0]->Nota}}</h6>
               </div>
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
                     <h6>Precio Por Noche: ${{$detallereserva[0]->Precio_noche}}</h6>
                     <h6>Precio Por Semana: ${{$detallereserva[0]->Precio_semana}}</h6>
                     <h6>Precio Por Catorcena: ${{$detallereserva[0]->Precio_catorcedias}}</h6>
                     <h6>Precio Por Mes: ${{$detallereserva[0]->Precio_mes}}</h6>
                     <h6>Deposito De Garantia: ${{$detallereserva[0]->Deposito_garantia_casa}}</h6>
                     @if($detallereserva[0]->Tipo_de_cobro == "Noche")  
                     <h6>Cobro Por Persona Extra(Noche): $0</h6>
                     @endif
                     @if($detallereserva[0]->Tipo_de_cobro == "Semana")
                     <h6>Cobro Por Persona Extra(Semana): ${{$detallereserva[0]->Cobro_p_ext_catorcena_c}}</h6>
                     <h6>Cobro De Anticipo(Semana): ${{$detallereserva[0]->Cobro_anticipo_catorcena_c}}</h6>
                     @endif
                     @if($detallereserva[0]->Tipo_de_cobro == "Catorcena")
                     <h6>Cobro Por Persona Extra(Catorcena): ${{$detallereserva[0]->Cobro_p_ext_catorcena_c}}</h6>
                     <h6>Cobro De Anticipo(Catorcena): ${{$detallereserva[0]->Cobro_anticipo_catorcena_c}}</h6>
                     @endif
                     @if($detallereserva[0]->Tipo_de_cobro == "Mes")
                     <h6>Cobro Por Persona Extra(Mes): ${{$detallereserva[0]->Cobro_p_ext_mes_c}}</h6>
                     <h6>Cobro De Anticipo(Mes): ${{$detallereserva[0]->Cobro_anticipo_mes_c}}</h6>
                     @endif
                  </div>
               </p>
            </div>
         </div>
   </div>
</div>


<!--=============== TABLA DATOS DE ALOJAMIENTO ===============-->    
<div class="interno_padre_l">
   <div class="interno_hijo_l">
         <div class="interno_l">
          <div class="container_tabla">
            <div class="centrar_texto">
                <p><h5>Datos De Alojamiento</h5></p>
             </div>
              <table class="table table-striped table-hover">
                     <thead>
                           <tr>
                              <th>Fecha De Reserva</th>
                              <th>Fecha De Entrada</th>
                              <th>Fecha De Salida</th>
                              <th>Personas Extras</th>
                              <th>Nombre Del Lugar</th>
                              <th>Estatus</th>
                              <th>Tipo De Cobro</th>
                           </tr>
                     </thead>
                     <tbody>
                           <tr>
                              <td data-label="Fecha De Reservacion">{{$detallereserva[0]->Fecha_reservacion}}</td>
                              <td data-label="Fecha De Entrada">{{$detallereserva[0]->Start_date}}</td>
                              <td data-label="Fecha De Salida">{{$detallereserva[0]->End_date}}</td> 
                              <td data-label="Personas Extras">
                                 @if($detallereserva[0]->Numero_personas_extras == NULL)
                                    <h6>0</h6>
                                 @else
                                 <i class="fa-solid fa-person"></i> {{$detallereserva[0]->Numero_personas_extras}}
                                 @endif
                              </td> 
                              <td data-label="Nombre Del Lugar">Casa: {{$detallereserva[0]->Nombre_locacion}}</td>
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
                              

                           </tr>
                     </tbody>
              </table>
          </div>
         </div>
   </div>
</div>


<!--=============== TABLA DATOS DE COSTOS Y DETALLES ===============-->
<div class="interno_padre_l">
    <div class="interno_hijo_l">
          <div class="interno_l">
           <div class="container_tabla">
             <div class="centrar_texto">
                 <p><h5>Detalles Y Costo Total</h5></p>
              </div>
               <table class="table table-striped table-hover">
                      <thead>
                            <tr>
                                 <th>Total De Noches: {{$diasredondeados}} </th>
                                 <th>Personas Extras: {{$detallereserva[0]->Numero_personas_extras}}</th>
                                 <th>Monto De Garantia</th>
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
                                 <td data-label="Total De Noches: {{$diasredondeados}}">${{$monto_por_dias}}</td>
                                 <td data-label="Personas Extras: {{$detallereserva[0]->Numero_personas_extras}}">${{$monto_por_p_extras}}</td>
                                 <td data-label="Monto De Garantia">${{$detallereserva[0]->Deposito_garantia_casa}}</td>
                                 <td data-label="Total A Pagar">${{$suma_monto}}</td> 
                                 @if($detallereserva[0]->Tipo_de_cobro == "Noche")  
                                 @endif
                                 @if($detallereserva[0]->Tipo_de_cobro == "Semana")
                                 <td data-label="Monto De Anticipo">${{$detallereserva[0]->Cobro_anticipo_catorcena_c}}</td>   
                                 @endif
                                 @if($detallereserva[0]->Tipo_de_cobro == "Catorcena")
                                 <td data-label="Monto De Anticipo">${{$detallereserva[0]->Cobro_anticipo_catorcena_c}}</td>   
                                 @endif
                                 @if($detallereserva[0]->Tipo_de_cobro == "Mes")
                                 <td data-label="Monto De Anticipo">${{$detallereserva[0]->Cobro_anticipo_mes_c}}</td>   
                                 @endif
                            </tr>
                      </tbody>
               </table>
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
                     <button class="btn btn-success clickForm" href="{{route('viewrentarc1casa', [$detallereserva[0]->Id_reservacion, $detallereserva[0]->Id_locacion, $detallereserva[0]->Id_lugares_reservados ])}}">Pasar a rentar</button>
                    <button class="btn btn-warning clickForm" href="{{route('editarreservacasa', [$detallereserva[0]->Id_reservacion, $detallereserva[0]->Id_locacion, $detallereserva[0]->Id_lugares_reservados])}}">Editar</button>
                    <a class="btn btn-danger" href="">Eliminar</a>
                  </div>
               </p>
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
         <h2 class="modal-title fs-5" id="staticBackdropLabel"></h2>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<script>
         //codigo de jquery que abre los modals de fotos y eliminar registros
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


@endsection
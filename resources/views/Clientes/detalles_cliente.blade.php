<!--=============== CSS local===============-->
<link rel="stylesheet" href="{{ asset('assets/detalle_cliente.css') }}" >
<!--=============== CSS web ===============-->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">      
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

@extends('layouts.menu_layout')
@section('MenuPrincipal')
<!-- libreria para usar las alertas-->
@include('sweetalert::alert')
<!--=============== ENCABEZADO ===============-->

<header class="encabezado">
    <div class="overlay">
       <h1>Detalles Del Cliente</h1>
       <a href="{{route('clientes')}}" type="button" class="boton">Regresar</a>
    </div>
 </header>
 
 <!--=============== Datos Del Cliente ===============-->
<div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
          <div class="seccion_interno_1">
             <div class="centrar_texto">
                <p><h5>Datos Del Cliente</h5></p>
                <p><h5>Nombre</h5></p>
                    <p>
                        <div class="gris">
                        <h6>{{$cliente[0]->Nombre}} {{$cliente[0]->Apellido_paterno}} {{$cliente[0]->Apellido_materno}}</h6>
                        </div>
                    </p>
                
                <p><h5>Numero De Celular</h5></p>
                    <p>
                        <div class="gris">
                        <h6>{{$cliente[0]->Numero_celular}}</h6>
                        </div>
                    </p>

                <p><h5>Email</h5></p>
                    <p>
                        <div class="gris">
                        <h6>{{$cliente[0]->Email}}</h6>
                        </div>
                    </p>


                <p><h5>Estado De Procedencia</h5></p>
                    <p>
                        <div class="gris">
                        <h6>{{$cliente[0]->Estado}}</h6>
                        </div>
                    </p>

                <p><h5>Ciudad De Procedencia</h5></p>
                    <p>
                        <div class="gris">
                        <h6>{{$cliente[0]->Ciudad}}</h6>
                        </div>
                    </p>

                <p><h5>Pais De Procedencia</h5></p>
                    <p>
                        <div class="gris">
                        <h6>{{$cliente[0]->Pais}}</h6>
                        </div>
                    </p>

                <p><h5>Motivo De Visita</h5></p>
                    <p>
                        <div class="gris">
                        <h6>{{$cliente[0]->Motivo_visita}}</h6>
                        </div>
                    </p>

                <p><h5>Institucion o Empresa</h5></p>
                    <p>
                        <div class="gris">
                        <h6>{{$cliente[0]->Lugar_motivo_visita}}</h6>
                        </div>
                    </p>

             </div>
          </div>
    </div>
 </div>

  <!--=============== CONTACTOS DE EMERGENCIA ===============-->
  <div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
             <div class="centrar_texto">
                <div>
                  <p><h5>Fotos De La INE</h5></p>
                  <i id="despliegue_fotos" class="fa-solid fa-chevron-down" onclick="presentar_fotos()"></i>
                  <i id="ocultar_fotos" class="fa-solid fa-chevron-up" onclick="esconder_fotos()"></i>
                  </p>
               </div>
             </div>
         <div class="detalle_fotos" id="detalle_fotos">  
            <p>
                <div class="gris">
                     <div class="seccion_padre_b">
                         <div class="seccion_interno_2">
                             <div id="contenedor" class="rounded mx-auto d-block">
                                 <img class="imagen" src="{{asset('uploads/clientes/').'/'.$cliente[0]->INE_frente  }}" >
                             </div>
                         </div>
                     </div>
                </div>
             </p>

             <p>
                <div class="gris">
<!--=============== FOTO REGLAMENTO===============-->
                     <div class="seccion_padre_b">
                         <div class="seccion_interno_2">
                             <div id="contenedor" class="rounded mx-auto d-block">
                                 <img class="imagen" src="{{asset('uploads/clientes/').'/'.$cliente[0]->INE_reverso  }}" >
                             </div>
                         </div>
                     </div>
                </div>
             </p>


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
                        <p> <h6>{{$cliente[0]->Ref1_nombre}}</h6></p>
                    </div>
            </div>

            <div class="centrar_texto">
                <p><h5>Numero De Celular:</h5></p>
                    <div class="gris">
                        <p> <h6>{{$cliente[0]->Ref1_celular}}</h6></p>
                    </div>
            </div>
            <div class="centrar_texto">
                <p><h5>Parentesco</h5></p>
                    <div class="gris">
                        <p> <h6>{{$cliente[0]->Ref1_parentesco}}</h6></p>
                    </div>
            </div>
            <div class="centrar_texto">
               <a href="https://wa.me/{{$cliente[0]->Ref1_celular}}" target="_blank" class="btn btn-success">Enviar Whatsapp <i class="fa fa-whatsapp" aria-hidden="true"></i></a>
               <a href="tel:{{$cliente[0]->Ref1_celular}}" class="btn btn-secondary" onclick="return(navigator.userAgent.match(/ Android | iPhone | movile /i)) != null;">Llamar</a>
            </div>

<!--=============== Persona 2 ===============-->
            <div class="centrar_texto">
                <p><h5>Nombre De La Persona 2:</h5></p>
                    <div class="gris">
                        <p> <h6>{{$cliente[0]->Ref2_nombre}}</h6></p>
                    </div>
            </div>

            <div class="centrar_texto">
                <p><h5>Numero De Celular:</h5></p>
                    <div class="gris">
                        <p> <h6>{{$cliente[0]->Ref2_celular}}</h6></p>
                    </div>
            </div>
            <div class="centrar_texto">
                <p><h5>Parentesco</h5></p>
                    <div class="gris">
                        <p> <h6>{{$cliente[0]->Ref2_parentesco}}</h6></p>
                    </div>
            </div>
            <div class="centrar_texto">
               <a href="https://wa.me/{{$cliente[0]->Ref1_celular}}" target="_blank" class="btn btn-success">Enviar Whatsapp <i class="fa fa-whatsapp" aria-hidden="true"></i></a>
               <a href="tel:{{$cliente[0]->Ref1_celular}}" class="btn btn-secondary" onclick="return(navigator.userAgent.match(/ Android | iPhone | movile /i)) != null;">Llamar</a>
            </div>
            <br>
         </div>
    </div>
 </div>

 <!--=============== Datos Del Cliente ===============-->
 <div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
          <div class="seccion_interno_1">
             <div class="centrar_texto">
                <p><h5>Botones De Accion</h5></p>
                <p>
                    <button class="btn btn-success clickForm" href="{{route('editarcliente', $cliente[0]->Id_cliente)}}"><i class="fa-solid fa-user-pen"></i></button>
                    @if($cliente[0]->Estatus_cliente == "Desactivado")
                    <button class="btn btn-warning clickForm" href="{{route('view_desact_cliente',$cliente[0]->Id_cliente)}}" >Activar</button>
                    @else
                    <button class="btn btn-danger clickForm" href="{{route('view_desact_cliente', $cliente[0]->Id_cliente)}}" >Bloquear</button>
                    @endif
                </p>

             </div>
          </div>
    </div>
 </div>

<br>


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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ url('js/detalles_habitacion.js')}}"></script>

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

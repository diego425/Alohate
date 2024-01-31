      <!--=============== CSS local===============-->
      <link rel="stylesheet" href="{{ asset('assets/locacion_style.css') }}" >

      <!--=============== CSS web ===============-->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">      

      <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">


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

      .lugareslibres{
            color: mediumseagreen;
            font-weight: bold;
            font-size: 16px
      }
      .textohdl{
            color: rgb(179, 60, 60);
            font-weight: bold;
            font-size: 16px
      }
      .reserva{
            color:  rgb(207, 178, 33);
            font-weight: bold;
            font-size: 16px
      }
      .texto{
            font-weight: bold;
            font-size: 16px
      }

      </style>
<!-- aqui llama a la vista de layout del menu para que se pueda mostrar-->
@extends('layouts.menu_layout')
@section('MenuPrincipal')

<!-- libreria para usar las alertas-->
@include('sweetalert::alert')
<!--=============== ENCABEZADO ===============-->

<header class="encabezado">
      <div class="overlay">
         <h1>Locaciones</h1>
      </div>
   </header>
   
<!--=============== BUSCADOR ===============-->
 
<div class="seccion_padre_b">
      <div class="seccion_hijo_b"> 
            <div class="seccion_interno_b">
                  <form action="{{route('locacion')}}" method="get" class="formulario_buscador">
                        <div>
                              <h5><label class="titulo_buscador">Buscador</label> <small><i class="ri-search-line"></i></small></h5>
                        </div> 
                        <div class="titulos_buscador">
                              <label>NickName</label>
                              <select name="nickname" id="nickname" class="form-control">
                                    <option value="-1" selected disabled>Selecciona una opcion</option>
                                    @foreach($casas as $casa)
                                    <option value="{{$casa->Nombre_locacion}}">{{$casa->Nombre_locacion}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="titulos_buscador">
                              <label>Tipo De Renta</label>
                              <select name="tiporenta" id="tiporenta" class="form-control">
                                    <option value="-1" disabled selected>Selecciona una opcion</option>
                                    <option value="1">Entera</option>
                                    <option value="2">Por Secciones</option>
                              </select>
                        </div>
                        <div class="titulos_buscador">
                              <label>Colonia</label>
                              <input type="text" class="form-control" name="colonia" id="colonia">
                        </div>
                        <div class="titulos_buscador">
                              <label>Estatus De La Casa</label>
                              <select name="estatus" id="estatus" class="form-control">
                                    <option value="-1" selected disabled>Selecciona una opcion</option>
                                    @foreach($estatus_locaciones as $estatus_locacion)
                                    <option value="{{$estatus_locacion->Id_estado_ocupacion}}">{{$estatus_locacion->Id_estado_ocupacion}} .- {{$estatus_locacion->Nombre_estado}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <br>
                        <div style="text-align: center;">
                              <input type="submit" class="btn btn-outline-info elemento_b" value="Buscar">
                        </div>
                        <br>
                  </form>
            </div>
      </div>
</div>

<!--=============== cuerpo tabla cabecera ===============-->
<div class="externo_padre_l">
      <div class="externo_hijo_l">
            <div class="externo_l">
                  <div class="boton_agregar"> 
                   <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#agregar"><i class="ri-add-circle-fill"></i></button>
                  </div>
                 <div class="titulo_gestion">
                  <h5><label>Gestion De Locaciones</label></h5>
                 </div>
            </div>
      </div>
</div>

<!--=============== DATOS DE LAS LOCACIONES  ===============-->
@if(count($locaciones)<=0)
      <div class="seccion_padre_b">
        <div class="seccion_hijo_t"> 
          <div class="centrar_texto">
                <div>
                <p><h5>No hay registros</h5></p>
                </div>
          </div>
        </div>
      </div>
@else
@foreach ($locaciones as $locacion)
      <div class="seccion_padre_b">
      <div class="seccion_hijo_t"> 
                  <div class="centrar_texto">
                        <div>
      <!--=============== seccion que muestra y oculta con los iconos aqui se esta usando el codigo de jquery que esta al final  ===============-->
                        <p><h5>{{$locacion->Nombre_locacion}}</h5>
                        <i class="fa-solid fa-chevron-down despliegue_locacion despliegue_locacionocultar{{$locacion->Id_locacion}}" relacion="ocultar{{$locacion->Id_locacion}}"></i>
                        <i class="fa-solid fa-chevron-up ocultar_locacion ocultar_locacionocultar{{$locacion->Id_locacion}}" relacion="ocultar{{$locacion->Id_locacion}}" style="display: none"></i>
                        </p>
                        </div>
                  </div>
      <!--=============== seccion que muestra la info utilizando jquery  ===============-->
            <div class="detalle_locacion ocultar{{$locacion->Id_locacion}} ">  

            <div class="interno_padre_l">
                  <div class="container_tabla">
                  <table class="table table-striped table-hover">
                  <thead>
                        <tr>  @if($locacion->Tipo_renta == "Secciones")
                              @if($locacion->Numero_total_habitaciones <= 0)

                              @else($locacion->Numero_total_habitaciones > 0)
                              <th>Habitaciones</th>
                              @endif
                              @if($locacion->Numero_total_depas <= 0)

                              @else($locacion->Numero_total_depas > 0)
                              <th>Departamentos</th>
                              @endif
                              @if($locacion->Numero_total_locales <= 0)

                              @else($locacion->Numero_total_locales > 0)
                              <th>Locales</th>
                              @endif
                              @else($locacion->Tipo_renta == "Entera")

                              @endif
                              <th>TIPO DE RENTA</th>
                              <th>UBICACION</th>
                              <th>NUMERO DE PISOS</th>
                              <th>PORCENTAJE DE DISP.</th>
                              <th>ESTATUS DE LA CASA</th>
                              <th>ACCIONES</th>
                        </tr>
                  </thead>
                  <tbody>
                        <tr>
                              @if($locacion->Tipo_renta == "Secciones")
                              @if($locacion->Numero_total_habitaciones <= 0)

                              @else($locacion->totalhabslibres > 0)
                              <td data-label="Habitaciones">
                                    <div class="lugareslibres">Libres: {{$locacion->totalhabslibres}}</div>
                                    <div class="textohdl">Rentadas: {{$locacion->totalhabsocupadas}}</div>
                                    <div class="reserva">Reservadas: {{$locacion->totalhabsreservada}}</div>
                                    <div class="texto">Total: {{$locacion->Numero_total_habitaciones}}</div>
                                    <a href="{{route('habitaciones', $locacion->Id_locacion)}}">Ver Habitaciones</a>
                              </td>
                              @endif
                              @if($locacion->Numero_total_depas <= 0)

                              @else($locacion->totaldepaslibres > 0)
                              <td data-label="Departamentos">
                                    <div class="lugareslibres">Libres: {{$locacion->totaldepaslibres}}</div>
                                    <div class="textohdl">Rentadas: {{$locacion->totaldepasocupadas}}</div>
                                    <div class="reserva">Reservadas: {{$locacion->totaldepasreservada}}</div>
                                    <div class="texto">Total: {{$locacion->Numero_total_depas}}</div> 
                                    <a href="{{route('departamentos', $locacion->Id_locacion)}}">Ver Departamentos</a>
                              </td>
                              @endif
                              @if($locacion->Numero_total_locales <= 0)

                              @else($locacion->totallocslibres > 0)
                              <td data-label="Locales">
                                    <div class="lugareslibres">Libres: {{$locacion->totallocslibres}}</div>
                                    <div class="textohdl">Rentadas: {{$locacion->totallocsocupadas}}</div>
                                    <div class="reserva">Reservadas: {{$locacion->totallocsreservada}}</div>
                                    <div class="texto">Total: {{$locacion->Numero_total_locales}}</div> 
                                    <a href="{{route('locales', $locacion->Id_locacion)}}">Ver Locales</a>
                              </td>
                              @endif
                              @else($locacion->Tipo_renta == "Entera")

                              @endif
                              
                              <td data-label="TIPO DE RENTA">{{$locacion->Tipo_renta}}</td>
                              <td data-label="UBICACION">  {{$locacion->Calle}} #{{$locacion->Numero_ext}} {{$locacion->Colonia}}</td>
                              <td data-label="NUMERO DE PISOS">{{$locacion->Numero_total_de_pisos}}</td>
                              <td data-label="PORCENTAJE DE DISP.">pendiente a mostrar</td>
                              <td data-label="ESTATUS DE LA CASA">

                                    @if($locacion->Nombre_estado == "En Mantenimiento/Limpieza")
                                    <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$locacion->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locacion->Nombre_estado == "Desocupada")
                                    <div class="gris"><h6 style="color: mediumseagreen">{{$locacion->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locacion->Nombre_estado == "Reservada")
                                    <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$locacion->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locacion->Nombre_estado == "Desactivada")
                                    <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$locacion->Nombre_estado}}</h6></div>
                                    @endif

                                    @if($locacion->Nombre_estado == "Rentada")
                                    <div class="gris"><h6 style="color: rgb(33, 36, 207)">{{$locacion->Nombre_estado}}</h6></div>
                                    @endif
                                    @if($locacion->Nombre_estado == "Pago por confirmar")
                                    <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$locacion->Nombre_estado}}</h6></div>
                                    @endif
                                    
                              </td>
                              <td data-label="ACCIONES">
                                    <button class="btn btn-success clickForm" href="{{route('hab_loc')}}"><i class="ri-home-gear-line"></i></button>
                                    <button class="btn btn-primary clickForm" href="{{route('view_editar_loc', $locacion->Id_locacion)}}"><i class="ri-pencil-line"></i></button>
                                    <button class="btn btn-danger clickForm" href="{{route('view_desactivar_loc', $locacion->Id_locacion)}}" ><i class="fa-solid fa-ban"></i></button>
                                    <a class="btn btn-info" href="{{route('detalle_loc', $locacion->Id_locacion)}}"><i class="ri-information-line"></i></a>
                              </td>
                  
                        </tr>
                  </tbody>
                  </table>   
                  </div>
            </div>
            </div>
      </div>
      </div>
@endforeach
@endif
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
                <iframe class="embed-responsive-item" src="<?= route('agregar_loc') ?>" allowfullscreen style="height: 100%; width: 100%;"></iframe>
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


<!--scripts-->
<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>


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
  
//codigo de jquery que muestra y oculta las secciones de las locaciones
              $('.despliegue_locacion').on('click', function (index) {
                  console.log(this);
                  let relacion = $(this).attr("relacion");
                  $(this).hide();
                  $('.ocultar_locacion'+relacion).show();
                  $("."+relacion).show();

              });

              $('.ocultar_locacion').on('click', function (index) {
                  console.log(this);
                  let relacion = $(this).attr("relacion");
                  $(this).hide();
                  $('.despliegue_locacion'+relacion).show();
                  $("."+relacion).hide();
              });

</script>

@endsection
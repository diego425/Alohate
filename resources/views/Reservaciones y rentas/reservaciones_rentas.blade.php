<!--=============== CSS local===============-->
<link rel="stylesheet" href="{{ asset('assets/reservaciones_rentas.css') }}" >
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
       <h1>Reservaciones y Rentas</h1>
    </div>
 </header>



 <!--=============== BUSCADOR ===============-->
 <div class="seccion_padre_b">
    <div class="seccion_hijo_b"> 
          <div class="seccion_interno_b">
                <form action="" method="get" class="formulario_buscador">
                      <div>
                            <h5><label class="titulo_buscador">Buscador</label> <small><i class="ri-search-line"></i></small></h5>
                      </div> 
                      <div class="titulos_buscador">
                            <label>Locacion</label>
                            <select name="" id="" class="form-control">
                            <option value="">Selecciona una opcion</option>
                            </select>
                      </div>
                      <div class="titulos_buscador">
                            <label>Fecha De Entrada</label>
                            <input type="date" class="form-control" name="fecha_entrada" id="f_entrada" >
                      </div>
                      <div class="titulos_buscador">
                            <label>No. De Celular Del Cliente</label>
                            <input type="number" class="form-control" name="no_cel" id="no_cel" placeholder="#">
                      </div>
                      <div class="titulos_buscador">
                            <label>Estatus</label>
                            <input type="text" class="form-control" name="estatus" id="estatus" >
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
               <div class="titulo_gestion">
                <h5><label>Registros De Reservaciones y Rentas Del Mes</label></h5>
               </div>
          </div>
    </div>
</div>


<!--=============== DATOS DE LAS RESERVACIONES Y RENTAS  ===============-->
<div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
          <div class="interno_padre_l">
                @if(count($reservarentas)<=0)
                    <div class="seccion_padre_b">
                        <div class="seccion_hijo_h"> 
                        <div class="centrar_texto">
                                <div>
                                <p><h5>No hay registros</h5></p>
                                </div>
                        </div>
                        </div>
                    </div>
                @else
      
                <div class="container_tabla">
                    <table class="table table-striped table-hover">
                    <thead>
                            <tr>
                                <th>Nombre Del Cliente</th>
                                <th>No. De Cel</th>
                                <th>Fecha De Reservacion</th>
                                <th>Fecha De Entrada</th>
                                <th>Fecha De Salida</th>
                                <th>Lugar De Estancia</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservarentas as $reservarenta)
                            <tr>
                                <td data-label="Nombre Del Cliente">{{$reservarenta->Nombre}} {{$reservarenta->Apellido_paterno}} {{$reservarenta->Apellido_materno}}</td>
                                <td data-label="No. De Cel">{{$reservarenta->Numero_celular}}</td>
                                <td data-label="Fecha De Reservacion">{{$reservarenta->Fecha_reservacion}}</td>
                                <td data-label="Fecha De Entrada">{{$reservarenta->Start_date}}</td>
                                <td data-label="Fecha De Salida">{{$reservarenta->End_date}}</td>
                                @if($reservarenta->Id_local == "")
                                @else
                                <td data-label="Lugar De Estancia">local: {{$reservarenta->Nombre_local}}</td>
                                @endif
                                @if($reservarenta->Id_habitacion == "")
                                @else
                                <td data-label="Lugar De Estancia">Hab: {{$reservarenta->Nombre_hab}}</td>
                                @endif
                                @if($reservarenta->Id_locacion == "")
                                @else
                                <td data-label="Lugar De Estancia">Casa: {{$reservarenta->Nombre_locacion}}</td>
                                @endif
                                @if($reservarenta->Id_departamento == "")
                                @else
                                <td data-label="Lugar De Estancia">Depa: {{$reservarenta->Nombre_depa}}</td>
                                @endif
                                <td data-label="Estatus">
                                    @if($reservarenta->Nombre_estado == "Reservada")
                                    <h6 style="color: rgb(0, 140, 210)">{{$reservarenta->Nombre_estado}}</h6>
                                    @endif

                                    @if($reservarenta->Nombre_estado == "Rentada")
                                    <h6 style="color: rgb(33, 36, 207)">{{$reservarenta->Nombre_estado}}</h6>
                                    @endif

                                    @if($reservarenta->Nombre_estado == "Pago por confirmar")
                                    <h6 style="color: rgb(142, 122, 7)">{{$reservarenta->Nombre_estado}}</h6>
                                    @endif

                                    @if($reservarenta->Nombre_estado == "Estancia terminada")
                                    <h6 style="color: rgb(139, 0, 0)">{{$reservarenta->Nombre_estado}}</h6>
                                    @endif
                                </td> 
                                <td data-label="Acciones">

                                    @if($reservarenta->Id_habitacion == "")
                                    @else

                                        @if($reservarenta->Nombre_estado == "Reservada")
                                        <a class="btn btn-info" href="{{route('detallesreservahab', [$reservarenta->Id_reservacion, $reservarenta->Id_habitacion, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        <button class="btn btn-success clickForm" href="{{route('viewrentarc1hab', [$reservarenta->Id_reservacion, $reservarenta->Id_habitacion, $reservarenta->Id_lugares_reservados ])}}">Pasar a rentar</button>
                                        @endif

                                        @if($reservarenta->Nombre_estado == "Rentada")
                                        <a class="btn btn-info" href="{{route('detallesreservahab', [$reservarenta->Id_reservacion, $reservarenta->Id_habitacion, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        <button class="btn btn-danger clickForm" href="{{route('viewterminarrentahab', [$reservarenta->Id_reservacion, $reservarenta->Id_habitacion, $reservarenta->Id_lugares_reservados ])}}" >Terminar</button>
                                        @endif

                                        @if($reservarenta->Nombre_estado == "Pago por confirmar")
                                        <a class="btn btn-info" href="{{route('detallesreservahab', [$reservarenta->Id_reservacion, $reservarenta->Id_habitacion, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        @endif

                                        @if($reservarenta->Nombre_estado == "Estancia terminada")
                                        <a class="btn btn-info" href="{{route('detallesreservahab', [$reservarenta->Id_reservacion, $reservarenta->Id_habitacion, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        @endif

                                    @endif

                                    @if($reservarenta->Id_locacion == "")
                                    @else
                                        @if($reservarenta->Nombre_estado == "Reservada")
                                        <a class="btn btn-info" href="{{route('detallesreservacasa', [$reservarenta->Id_reservacion, $reservarenta->Id_locacion, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        <button class="btn btn-success clickForm" href="{{route('viewrentarc1casa', [$reservarenta->Id_reservacion, $reservarenta->Id_locacion, $reservarenta->Id_lugares_reservados ])}}">Pasar a rentar</button>
                                        @endif

                                        @if($reservarenta->Nombre_estado == "Rentada")
                                        <a class="btn btn-info" href="{{route('detallesreservacasa', [$reservarenta->Id_reservacion, $reservarenta->Id_locacion, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        <button class="btn btn-danger clickForm" href="{{route('viewterminarrentacasa', [$reservarenta->Id_reservacion, $reservarenta->Id_locacion, $reservarenta->Id_lugares_reservados ])}}" >Terminar</button>
                                        @endif

                                        @if($reservarenta->Nombre_estado == "Pago por confirmar")
                                        <a class="btn btn-info" href="{{route('detallesreservacasa', [$reservarenta->Id_reservacion, $reservarenta->Id_locacion, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        @endif

                                        @if($reservarenta->Nombre_estado == "Estancia terminada")
                                        <a class="btn btn-info" href="{{route('detallesreservacasa', [$reservarenta->Id_reservacion, $reservarenta->Id_locacion, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        @endif

                                    @endif

                                    @if($reservarenta->Id_departamento == "")
                                    @else
                                        @if($reservarenta->Nombre_estado == "Reservada")
                                        <a class="btn btn-info" href="{{route('detallesreservadep', [$reservarenta->Id_reservacion, $reservarenta->Id_departamento, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        <button class="btn btn-success clickForm" href="{{route('viewrentarc1dep', [$reservarenta->Id_reservacion, $reservarenta->Id_departamento, $reservarenta->Id_lugares_reservados ])}}">Pasar a rentar</button>
                                        @endif

                                        @if($reservarenta->Nombre_estado == "Rentada")
                                        <a class="btn btn-info" href="{{route('detallesreservadep', [$reservarenta->Id_reservacion, $reservarenta->Id_departamento, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        <button class="btn btn-danger clickForm" href="{{route('viewterminarrentadep', [$reservarenta->Id_reservacion, $reservarenta->Id_departamento, $reservarenta->Id_lugares_reservados ])}}" >Terminar</button>
                                        @endif 

                                        @if($reservarenta->Nombre_estado == "Pago por confirmar")
                                        <a class="btn btn-info" href="{{route('detallesreservadep', [$reservarenta->Id_reservacion, $reservarenta->Id_departamento, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        @endif

                                        @if($reservarenta->Nombre_estado == "Estancia terminada")
                                        <a class="btn btn-info" href="{{route('detallesreservadep', [$reservarenta->Id_reservacion, $reservarenta->Id_departamento, $reservarenta->Id_lugares_reservados ])}}"><i class="ri-information-line"></i></a>
                                        @endif

                                    @endif

                                    @if($reservarenta->Id_local == "")
                                    @else
                                    <a class="btn btn-info" href=""><i class="ri-information-line"></i></a>
                                    <button class="btn btn-danger clickForm" href="{{route('viewterminarrentaloc', [$reservarenta->Id_reservacion, $reservarenta->Id_local, $reservarenta->Id_lugares_reservados ])}}" >Terminar</button>
                                    @endif

                                </td> 
                            </tr>
                            @endforeach
                    </tbody>
                    </table>   
                       <!--paginador-->
                       <div class="d-flex justify-content-end">
                        {{ $reservarentas->appends(Request::all())->render()}}
                        </div>
                </div>
            @endif 
        </div>
    </div>
</div>
<br>



  <!-- Modal para agregar hab -->
  <div class="modal fade" id="agregar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="margin-left:12px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content tamaño_lg">
        <div class="modal-header">
          <h2 class="modal-title fs-5" id="staticBackdropLabel">Agregar Reservacion</h2>
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

</script>

@endsection

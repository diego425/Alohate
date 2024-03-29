<!--=============== CSS local===============-->
<link rel="stylesheet" href="{{ asset('assets/clientes_style.css') }}" >
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
       <h1>Clientes</h1>
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
                              <label>No. celular</label>
                              <input type="tel" class="form-control" name="no_cel" id="no_cel" placeholder="#">
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
                <button  class="btn btn-secondary clickForm" href="{{route('viewnuevocliente' )}}"><i class="ri-add-circle-fill"></i></button>
                </div>
               <div class="titulo_gestion">
                <h5><label>Gestion De Los Clientes</label></h5>
               </div>
          </div>
    </div>
</div>


<!--=============== DATOS DE LAS LOCACIONES  ===============-->
<div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
          <div class="interno_padre_l">
                @if(count($clientes)<=0)
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
                                <th>Numero De Cel</th>
                                <th>Email</th>
                                <th>Estado De Proc.</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td data-label="Nombre Del Cliente">{{$cliente->Nombre}} {{$cliente->Apellido_paterno}} {{$cliente->Apellido_materno}}</td>
                                <td data-label="Numero De Cel">{{$cliente->Numero_celular}}</td>
                                <td data-label="Email">{{$cliente->Email}}</td>
                                <td data-label="Estado De Proc.">{{$cliente->Estado}}</td>
                                <td data-label="Estatus">{{$cliente->Estatus_cliente}}</td>
                                <td data-label="Acciones">
                                    <a class="btn btn-info" href="{{route('detallecliente', $cliente->Id_cliente )}}"><i class="ri-information-line"></i></a>
                                    @if($cliente->Estatus_cliente == "Bloqueado")
                                    <button class="btn btn-warning clickForm" href="{{route('view_desact_cliente',$cliente->Id_cliente)}}" >Desbloquear</button>
                                    @else
                                    <button class="btn btn-danger clickForm" href="{{route('view_desact_cliente', $cliente->Id_cliente)}}" >Bloquear</button>
                                    @endif
                                </td> 
                            </tr>
                            @endforeach
                    </tbody>
                    </table>   
                       <!--paginador-->
                       <div class="d-flex justify-content-end">
                        {{ $clientes->appends(Request::all())->render()}}
                        </div>
                </div>
            @endif 
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

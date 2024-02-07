<!DOCTYPE html>
<html lang="en">
<head>
<!--=============== metas ===============-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

<!--=============== CSS web ===============-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">      
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
<!--=============== CSS local ===============-->
<link rel="stylesheet" href="{{ asset('assets/habitaciones_style.css') }}" >


</head>
<body>
    @include('sweetalert::alert')

    

<div class="seccion_padre_b">
    <div class="seccion_hijo_t"> 
      <div class="centrar_texto" style="margin: 10px">
        <br>
        @if($cliente[0]->Estatus_cliente == "Activado")
            <form action="{{route('desactivar_cliente', $cliente[0]->Id_cliente)}}" class="form" method="POST" enctype="multipart/form-data">
                @csrf 
                @method('PUT')
        
            <div class="centrar">
                <div class="titulo">
                    <label><h5>¿Estas Seguro De Querer Bloquear Este Cliente?</h5></label>
                </div>
            </div>
            
            <div class="centrar">
                <div class="cuerpo">
                    <p><label><h5>Nombre Del Cliente:</h5> <h6>{{$cliente[0]->Nombre}} {{$cliente[0]->Apellido_paterno}} {{$cliente[0]->Apellido_materno}}</h6></label></p>
                    <p><label><h5>Estatus Del Cliente: </h5><h6>{{$cliente[0]->Estatus_cliente }}</h6></label></p>
                </div>
            </div>
            
            <div class="centrar">
                <input type="submit" class="btn btn-danger" value="Bloquear">
            </div>
            <br>
            </form>
        @else

        @if($cliente[0]->Estatus_cliente == "Bloqueado")

        <form action="{{route('activar_cliente', $cliente[0]->Id_cliente)}}" class="form" method="POST" enctype="multipart/form-data">
            @csrf 
            @method('PUT')
    
        <div class="centrar">
            <div class="titulo">
                <label><h5>¿Estas Seguro De Querer Desbloquear Este Cliente?</h5></label>
            </div>
        </div>
        
        <div class="centrar">
            <div class="cuerpo">
                <p><label><h5>Nombre Del Cliente:</h5> <h6>{{$cliente[0]->Nombre}} {{$cliente[0]->Apellido_paterno}} {{$cliente[0]->Apellido_materno}}</h6></label></p>
                <p><label><h5>Estatus Del Cliente: </h5><h6>{{$cliente[0]->Estatus_cliente }}</h6></label></p>
            </div>
        </div>
        
        <div class="centrar">
            <input type="submit" class="btn btn-info" value="Desbloquear">
        </div>
        <br>
        </form>
        @endif
        @endif
      </div>
    </div>
  </div>
</body>
</html>
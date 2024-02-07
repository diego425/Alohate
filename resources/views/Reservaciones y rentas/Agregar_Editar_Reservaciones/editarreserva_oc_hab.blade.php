<!DOCTYPE html>
<html lang="en">
<head>
 <!--METAS-->
 <meta charset="UTF-8">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<!--LINKS-->
<script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/formulario_Habs_Depas_Locs_style.css') }}">

</head>
  <body>
<!-- libreria para usar las alertas-->
@include('sweetalert::alert')
<div class="form">   
<!-- titulo central -->        
        <div class="titulo_central">
            <div class="centrar">
                <label><h2>Editando Registro De Reservacion</h2></label>
            </div>
        </div>
        <br><br>
<!-- Progress bar -->
            <div class="progressbar">
                <div class="progress" id="progress"></div>
                <div class="progress-step progress-step-active" data-title="Cliente"></div>
                <div class="progress-step" data-title="Alojamiento"></div>
            </div>

<!-- Step 1 -->

            <div class="form-step form-step-active">
                <div class="input-group">
                    <div class="titulo_central">
                        <div class="centrar">
                        <label><h3>Datos Del Cliente</h3></label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                        <label><h5>Nombre</h5></label>
                        <input type="text" value="{{$reservacion[0]->Nombre}}" disabled>
                        <input type="text" value="{{$reservacion[0]->Apellido_paterno}}" disabled> 
                        <input type="text" value="{{$reservacion[0]->Apellido_materno}}" disabled>
                </div>
                <div class="input-group">
                    <label><h5>Numero De Celular</h5></label>
                    <input type="text" value="{{$reservacion[0]->Numero_celular}}" disabled>
                </div>
                <div class="input-group">
                        <div class="centrar">
                            <p><label><h4>Si Quieres Cambiar De Cliente Primero Verifica Si El Cliente Ya Esta Registrado Con Nosotros.
                                Si No Se Encuentra, Registralo.</h4></label>
                        </div>
                </div>
                <div class="input-group">
                        <label>Busca Por Numero De Celular</label>
                        <input type="tel" class="form-control" name="mysearch" id="mysearch" placeholder="#">
                        <ul id="showlist" tabindex="1" class="list-group"></ul>
                </div>
                <div class="input-group">
                        <div class="centrar">
                            <label><h4>-Seleccionaste al Cliente-</h4></label>
                        </div>
                        
                        <div><h4>Nombre:</h4></div>
                            <div>
                                <h5><label id="nombrec"></label> <label id="apellidopat" ></label> <label id="apellidomat"></label></h5>
                            </div>
                        <div><h4>Celular:</h4></div>
                            <div>
                                <h5><label id="celularc"></label></h5>
                            </div>
                      
                </div>

<form action="{{route('update_reservahab', [$reservacion[0]->Id_reservacion, $reservacion[0]->Id_lugares_reservados ,$Id_locacion, $Id_habitacion])}}" method="POST" enctype="multipart/form-data">
@csrf 
@method('PUT')

<input type="text" style="display: none;" id="idcliente" name="selector_cliente" value="{{$reservacion[0]->Id_cliente}}">
                    <div class="centrar">
                        <p><h4>Registra Un Nuevo Cliente</h4></p>
                            <p class="icono_margen">
                            <a href="{{route('editarreservahabnc', [$reservacion[0]->Id_reservacion, $reservacion[0]->Id_lugares_reservados ,$Id_locacion, $Id_habitacion])}}"><i class="fa-solid fa-plus"></i></a>
                            </p>
                    </div>
                <div class="btns-group">
                    <a href="#" class="btn btn-next">Siguiente</a>
                </div>
            </div>


            <!-- Step 2 -->
            <div class="form-step">
                <div class="input-group">
                    <div class="titulo_central">
                        <div class="centrar">
                            <label><h3>Datos De Alojamiento</h3></label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label for="extras">Numero De Personas Extras:</label>
                    <input type="number" class="form-control"  name="extras" id="extras" value="{{$reservacion[0]->Numero_personas_extras}}">
                </div>
                <div class="input-group">
                    <label for="p_total">Numero Total De Personas Que Usaran El Lugar:</label>
                    <input type="number" class="form-control"  name="p_total" id="p_total" value="{{$reservacion[0]->Total_de_personas}}">
                </div>
                <div class="input-group">
                    <label for="f_entrada">Fecha De Entrada:</label>
                    <input type="date" class="form-control"  name="f_entrada" id="f_entrada" value="{{$reservacion[0]->Start_date}}">
                </div>
                <div class="input-group">
                    <label for="f_salida">Fecha De Salida:</label>
                    <input type="date" class="form-control"  name="f_salida" id="f_salida" value="{{$reservacion[0]->End_date}}">
                </div>
                <div class="input-group">
                    <label for="tipo_renta">¿Como Se Cobrara La Renta?</label>
                    <select name="tipo_renta" id="tipo_renta">
                        <option value="-1" disabled selected>Selecciona una opcion</option>
                        <option value="{{$reservacion[0]->Tipo_de_cobro}}" @if($reservacion[0]->Id_habitacion ) selected @endif>{{$reservacion[0]->Tipo_de_cobro}}</option>
                        <option value="Noche">Noche ${{$habitacion[0]->Precio_noche}}</option>
                        <option value="Semana">Semana ${{$habitacion[0]->Precio_semana}}</option>
                        <option value="Catorcena">Catorcena ${{$habitacion[0]->Precio_catorcedias}}</option>
                        <option value="Mes">Mes ${{$habitacion[0]->Precio_mes}}</option>
                    </select>
                </div>
                <div class="input-group">
                    <br>
                    <p><label>Numero De Cocheras Disponibles: </label></p>
                    <p><label style="color: darkgreen">Espacios Libres: {{$result_resta}}</label></p>
                </div>

                    
                    <div class="input-group">
                        <label for="uso_cochera">¿Cuantos Espacios De Cochera Desea Usar?</label>
                        <input type="number" class="form-control"  name="uso_cochera" id="uso_cochera" value="{{$reservacion[0]->Espacios_cochera}}">
                    </div>
                    <div class="input-group">
                        <label for="num_cochera">Monto Por Uso De Cochera:</label>
                        <input type="number" class="form-control"  name="num_cochera" id="num_cochera" value="{{$reservacion[0]->Monto_uso_cochera}}">
                    </div>
                
                <div class="btns-group">
                    <a href="#" class="btn btn-prev">Anterior</a>
                    <input type="submit" value="Guardar" class="boton_finalizar" style="width: 170px">
                </div>
            </div>
        </form>
    </div> 
</body>

    <!--scripts-->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ url('js/Movimiento.js')}}"></script>
    <script type="text/javascript" src="{{ url('js/reservacion.js')}}"></script>
    <script type="module" src="{{ url('js/buscarnum.js')}}"></script>

<script>
    function seleccionar(Id_cliente, Nombre,Apellido_paterno,Apellido_materno,Numero_celular){

    $("#idcliente").val(Id_cliente);
    $("#nombrec").text(Nombre);
    $("#apellidopat").text(Apellido_paterno);
    $("#apellidomat").text(Apellido_materno);
    $("#celularc").text(Numero_celular);
    document.getElementById('showlist').style.display = 'none';
}
</script>
</html>

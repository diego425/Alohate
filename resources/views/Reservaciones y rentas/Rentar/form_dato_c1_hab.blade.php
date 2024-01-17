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
<link rel="stylesheet" href="{{ asset('assets/form-rentar.css') }}">

</head>
  <body>
<!-- libreria para usar las alertas-->
@include('sweetalert::alert')
<div class="form">   
<!-- titulo central -->        
        <div class="titulo_central">
            <div class="centrar">
                <label><h2>Renta Del Lugar</h2></label>
            </div>
        </div>
        <br><br>
<!-- Progress bar -->
            <div class="progressbar">
                <div class="progress" id="progress"></div>
                <div class="progress-step progress-step-active" data-title="Cliente"></div>
                <div class="progress-step progress-step-active" data-title="Documentos"></div>
                <div class="progress-step progress-step-active" data-title="C.Emergencia"></div>
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
                        <div class="centrar">
                            <label><h4><i class="fa-solid fa-magnifying-glass"></i> Autobuscador De Clientes</h4></label>
                        </div>
                        <label>Busca Por Numero De Celular / <a href="" style="text-decoration: black; color:rgb(255, 0, 0);"><i class="fa-solid fa-user-plus"></i></a></label>
                        <input type="search" class="form-control" name="mysearch" id="mysearch" placeholder="#">
                        <ul id="showlist" tabindex="1" class="list-group"></ul>
                    </div>
                    <div class="input-group">
                        <div class="centrar">
                            <label><h4>-Seleccionaste Al Cliente-</h4></label>
                        </div>
                    </div>

<form action="{{route('storerentarhabc',[$renta[0]->Id_cliente, $renta[0]->Id_reservacion, $renta[0]->Id_habitacion, $renta[0]->Id_lugares_reservados ])}}" method="POST" enctype="multipart/form-data">
@csrf 
@method('PUT')

<input type="text" style="display: none;" id="idcliente" name="idcliente" value="{{$renta[0]->Id_cliente}}">


                <div class="input-group">
                    <label for="nombre_c">Nombre:</label>
                    <input type="text" class="form-control"  name="nombre_c" id="nombre_c" placeholder="nombre" value="{{$renta[0]->Nombre}}">
                </div>
                <div class="input-group">
                    <label for="apellido_pat">Apellido Paterno:</label>
                    <input type="text" class="form-control"  name="apellido_pat" id="apellido_pat" placeholder="apellido" value="{{$renta[0]->Apellido_paterno}}">
                </div>
                <div class="input-group">
                    <label for="apellido_mat">Apellido Materno:</label>
                    <input type="text" class="form-control"  name="apellido_mat" id="apellido_mat" placeholder="apellido" value="{{$renta[0]->Apellido_materno}}">
                </div>
                <div class="input-group">
                    <label for="celular">Numero De Celular:</label>
                    <input type="tel" class="form-control"  name="celular_c" id="celular_c" placeholder="#" value="{{$renta[0]->Numero_celular}}">
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control"  name="email_c" id="email_c" placeholder="@" value="{{$renta[0]->Email}}">
                </div>
                <div class="input-group">
                    <label for="estado">Estado De Proc.:</label>
                    <input type="text" class="form-control"  name="estado" id="estado" placeholder="estado" value="{{$renta[0]->Estado}}">
                </div>
                <div class="input-group">
                    <label for="ciudad">Ciudad De Proc.:</label>
                    <input type="text" class="form-control"  name="ciudad" id="ciudad" placeholder="ciudad" value="{{$renta[0]->Ciudad}}">
                </div>
                <div class="input-group">
                    <label for="pais">Pais De Proc.:</label>
                    <input type="text" class="form-control"  name="pais" id="pais" placeholder="pais" value="{{$renta[0]->Pais}}">
                </div>
                <div class="input-group">
                    <label for="motivo_v">Motivo De La Visita:</label>
                    <input type="text" class="form-control"  name="motivo_v" id="motivo_v" placeholder="" value="{{$renta[0]->Motivo_visita}}">
                </div>
                <div class="input-group">
                    <label for="lugar_v">Coloca El Nombre De La Institucion/Empresa:</label>
                    <input type="text" class="form-control"  name="lugar_v" id="lugar_v" placeholder="" value="{{$renta[0]->Lugar_motivo_visita}}">
                </div>
                
                <div class="input-group">
                    <label>Fotografia Del Cliente:</label>
                    <div class="centrar">
                    <div class="container2">
                        <div class="wrapper2">
                        <div class="image2">
                            <img src="{{asset('uploads/clientes/').'/'.$renta[0]->Foto_cliente}}" alt="" id="colocar_img2">
                        </div>
                        <div class="content2">
                            <div class="icon2">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="text2">
                                No hay ningun archivo
                            </div>
                        </div>
                        <div id="cancel-btn2">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="file-name2">
                            
                        </div>
                        </div>
                        <a onclick="defaultBtnActive2()" id="custom-btn2">Selecciona un archivo</a>
                        <input id="img2" name="img2" type="file" hidden onchange="revisarImagen2(this,1)">
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen2" id="nuevaImagen2" class="cuadrito">
                        <br>
                    </div>
                    </div>
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
                            <label><h3>Documentacion</h3></label>
                        </div>
                    </div>
                </div>

<!-- foto ine-->
                <div class="input-group">
                    <label>Foto De la INE(frontal):</label>
                    <div class="centrar">
                    <div class="container3">
                        <div class="wrapper3">
                        <div class="image3">
                            <img src="{{asset('uploads/clientes/').'/'.$renta[0]->INE_frente}}" alt="" id="colocar_img3">
                        </div>
                        <div class="content3">
                            <div class="icon3">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="text3">
                                No hay ningun archivo
                            </div>
                        </div>
                        <div id="cancel-btn3">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="file-name3">
                            
                        </div>
                        </div>
                        <a onclick="defaultBtnActive3()" id="custom-btn3">Selecciona un archivo</a>
                        <input id="img3" name="img3" type="file" hidden onchange="revisarImagen3(this,1)">
                        <br>
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen3" id="nuevaImagen3" class="cuadrito">
                        <br>
                    </div>
                    </div>
                </div>

<!-- foto ine-->
                <div class="input-group">
                    <label>Foto De la INE(trasera):</label>
                    <div class="centrar">
                    <div class="container4">
                        <div class="wrapper4">
                        <div class="image4">
                            <img src="{{asset('uploads/clientes/').'/'.$renta[0]->INE_reverso}}" alt="" id="colocar_img4">
                        </div>
                        <div class="content4">
                            <div class="icon4">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="text4">
                                No hay ningun archivo
                            </div>
                        </div>
                        <div id="cancel-btn4">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="file-name4">
                            
                        </div>
                        </div>
                        <a onclick="defaultBtnActive4()" id="custom-btn4">Selecciona un archivo</a>
                        <input id="img4" name="img4" type="file" hidden onchange="revisarImagen4(this,1)">
                        <br>
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen4" id="nuevaImagen4" class="cuadrito">
                        <br>
                    </div>
                    </div>
                </div>

                <div class="btns-group">
                    <a href="#" class="btn btn-prev">Anterior</a>
                    <a href="#" class="btn btn-next">Siguiente</a>
                </div>
            </div>
            
<!-- Step 3 -->
            <div class="form-step">
                <div class="input-group">
                    <div class="titulo_central">
                        <div class="centrar">
                            <label><h3>Contactos De Emergencia</h3></label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label for="nombre_p_e1">Nombre Completo De La Persona 1:</label>
                    <input type="text" class="form-control"  name="nombre_p_e1" id="nombre_p_e1" placeholder="nombre y apellidos" value="{{$renta[0]->Ref1_nombre}}">
                </div>
                <div class="input-group">
                    <label for="numero_p_e1">Numero De Celular De La Persona 1:</label>
                    <input type="tel" class="form-control"  name="numero_p_e1" id="numero_p_e1" placeholder="#" value="{{$renta[0]->Ref1_celular}}">
                </div>
                <div class="input-group">
                    <label for="parentesco1">Parentesco De La Persona 1:</label>
                    <input type="text" class="form-control"  name="parentesco1" id="parentesco1" placeholder="" value="{{$renta[0]->Ref1_parentesco}}">
                </div>
                <div class="input-group">
                    <label for="nombre_p_e2">Nombre Completo De La Persona 2:</label>
                    <input type="text" class="form-control"  name="nombre_p_e2" id="nombre_p_e2" placeholder="nombre y apellidos" value="{{$renta[0]->Ref2_nombre}}">
                </div>
                <div class="input-group">
                    <label for="numero_p_e2">Numero De Celular De La Persona 2:</label>
                    <input type="tel" class="form-control"  name="numero_p_e2" id="numero_p_e2" placeholder="#" value="{{$renta[0]->Ref2_celular}}">
                </div>
                <div class="input-group">
                    <label for="parentesco2">Parentesco De La Persona 2:</label>
                    <input type="text" class="form-control"  name="parentesco2" id="parentesco2" placeholder="" value="{{$renta[0]->Ref2_parentesco}}">
                </div>
                <div class="btns-group">
                    <a href="#" class="btn btn-prev">Anterior</a>
                    <input type="submit" value="Guardar y continuar" class="boton_finalizar" style="width: 170px">
                </div>
            </div>
        </form>
</body>

    <!--scripts-->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ url('js/Movimiento.js')}}"></script>
    <script type="text/javascript" src="{{ url('js/reservacion.js')}}"></script>
    <script type="module" src="{{ url('js/buscarnum.js')}}"></script>

<script>
    function seleccionar(Id_cliente,Nombre,Apellido_paterno,Apellido_materno,Numero_celular,Email,Ciudad,Estado,Pais,Ref1_nombre,Ref2_nombre,Ref1_celular,Ref2_celular,Ref1_parentesco,Ref2_parentesco,Motivo_visita,Lugar_motivo_visita){

    $("#idcliente").val(Id_cliente);
    $("#nombre_c").val(Nombre);
    $("#apellido_pat").val(Apellido_paterno);
    $("#apellido_mat").val(Apellido_materno);
    $("#celular_c").val(Numero_celular);
    $("#email_c").val(Email);
    $("#ciudad").val(Ciudad);
    $("#estado").val(Estado);
    $("#pais").val(Pais);
    $("#nombre_p_e1").val(Ref1_nombre);
    $("#nombre_p_e2").val(Ref2_nombre);
    $("#numero_p_e1").val(Ref1_celular);
    $("#numero_p_e2").val(Ref2_celular);
    $("#parentesco1").val(Ref1_parentesco);
    $("#parentesco2").val(Ref2_parentesco);
    $("#motivo_v").val(Motivo_visita);
    $("#lugar_v").val(Lugar_motivo_visita);

    document.getElementById('showlist').style.display = 'none';
}
</script>
</html>




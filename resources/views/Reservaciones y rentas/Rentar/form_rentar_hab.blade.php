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
                <div class="progress-step progress-step-active" data-title="Reglamentos"></div>
                <div class="progress-step progress-step-active" data-title="Contratos"></div>
            </div>

<form action="" method="POST" enctype="multipart/form-data">
@csrf 




<!-- Step 1 -->
            <div class="form-step form-step-active">
                <div class="input-group">
                    <div class="titulo_central">
                        <div class="centrar">
                            <label><h3>Reglamentos</h3></label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label>Fotografia Del Aviso De Privacidad</label>
                    <div class="centrar">
                    <div class="container14">
                        <div class="wrapper14">
                        <div class="image14">
                            <img src="" alt="" id="colocar_img14">
                        </div>
                        <div class="content14">
                            <div class="icon14">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="text14">
                                No hay ningun archivo
                            </div>
                        </div>
                        <div id="cancel-btn14">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="file-name14">
                            
                        </div>
                        </div>
                        <a onclick="defaultBtnActive14()" id="custom-btn14">Selecciona un archivo</a>
                        <input id="img14" name="img14" type="file"  onchange="revisarImagen14(this,1)">
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen14" id="nuevaImagen14" class="cuadrito">
                        <br>
                    </div>
                    </div>
                </div>
                <div class="input-group">
                    <label>Fotografia Del Reglamento Firmado</label>
                    <div class="centrar">
                    <div class="container15">
                        <div class="wrapper15">
                        <div class="image15">
                            <img src="" alt="" id="colocar_img15">
                        </div>
                        <div class="content15">
                            <div class="icon15">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="text15">
                                No hay ningun archivo
                            </div>
                        </div>
                        <div id="cancel-btn15">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="file-name15">
                            
                        </div>
                        </div>
                        <a onclick="defaultBtnActive15()" id="custom-btn15">Selecciona un archivo</a>
                        <input id="img15" name="img15" type="file"  onchange="revisarImagen15(this,1)">
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen15" id="nuevaImagen15" class="cuadrito">
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
                            <label><h3>Contratos</h3></label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <div class="titulo_central">
                        <div class="centrar">
                            <label><h3>Requiere Contrato</h3></label>
                            <i id="despliegue_contrato" class="fa-solid fa-chevron-down" onclick="presentar_contrato()"></i>
                            <i id="ocultar_contrato" class="fa-solid fa-chevron-up" onclick="esconder_contrato()"></i>   
                        </div>
                    </div>  
                </div>
                <div class="detalle_contrato" id="detalle_contrato">
                    <div class="input-group">
                        <div class="titulo_central">
                            <div class="centrar">
                                <label><h4>Datos Para El Contrato</h4></label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="fecha_inicio">Fecha De Inicio:</label>
                        <input type="date" class="form-control"  name="fecha_inicio" id="fecha_inicio">
                    </div>
                    <div class="input-group">
                        <label for="fecha_termino">Fecha De Termino:</label>
                        <input type="date" class="form-control"  name="fecha_termino" id="fecha_termino">
                    </div>
                    <div class="input-group">
                        <label for="tipo_contrato">Tipo De Contrato</label>
                        <select name="tipo_contrato" id="tipo_contrato">
                            <option value="-1">Selecciona una opcion</option>
                            <option value="Rigido">Rigido</option>
                            <option value="Flexible">Flexible</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Fotografia Del Contrato</label>
                        <div class="centrar">
                        <div class="container10">
                            <div class="wrapper10">
                            <div class="image10">
                                <img src="" alt="" id="colocar_img10">
                            </div>
                            <div class="content10">
                                <div class="icon10">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="text10">
                                    No hay ningun archivo
                                </div>
                            </div>
                            <div id="cancel-btn10">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="file-name10">
                                
                            </div>
                            </div>
                            <a onclick="defaultBtnActive10()" id="custom-btn10">Selecciona un archivo</a>
                            <input id="img10" name="img10" type="file"  onchange="revisarImagen10(this,1)">
                            <!--input que ayuda a sacar el link de base64 de la img -->
                            <input type="textarea" name="nuevaImagen10" id="nuevaImagen10" class="cuadrito">
                            <br>
                        </div>
                        </div>
                    </div>
                </div>
                
                <div class="input-group">
                    <div class="titulo_central">
                        <div class="centrar">
                            <label><h3>Requiere Fiador</h3></label>
                            <i id="despliegue_fiador" class="fa-solid fa-chevron-down" onclick="presentar_fiador()"></i>
                            <i id="ocultar_fiador" class="fa-solid fa-chevron-up" onclick="esconder_fiador()"></i>   
                        </div>
                    </div>  
                </div>
                <div class="detalle_fiador" id="detalle_fiador">
                    <div class="input-group">
                        <div class="titulo_central">
                            <div class="centrar">
                                <label><h4>Datos Para El Fiador</h4></label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="nombre_f">Nombre</label>
                        <input type="text" id="nombre_f" name="nombre_f" placeholder="Nombre">
                    </div>
                    <div class="input-group">
                        <label for="apellido_pat_f">Apellido Paterno</label>
                        <input type="text" id="apellido_pat_f" name="apellido_pat_f" placeholder="Apellido Paterno">
                    </div>
                    <div class="input-group">
                        <label for="apellido_mat_f">Apellido Materno</label>
                        <input type="text" id="apellido_mat_f" name="apellido_mat_f" placeholder="Apellido Materno">
                    </div>
                    <div class="input-group">
                        <label for="no_ext_casa">Numero Ext. De La Casa Del Fiador</label>
                        <input type="number" id="no_ext_casa" name="no_ext_casa" placeholder="#">
                    </div>
                    <div class="input-group">
                        <label for="calle_f">Calle</label>
                        <input type="text" id="calle_f" name="calle_f" placeholder="Calle">
                    </div>
                    <div class="input-group">
                        <label for="colonia_f">Colonia</label>
                        <input type="text" id="colonia_f" name="colonia_f" placeholder="Colonia">
                    </div>
                    <div class="input-group">
                        <label for="estado_f">Estado</label>
                        <input type="text" id="estado_f" name="estado_f" placeholder="Estado">
                    </div>
                    <div class="input-group">
                        <label for="num_telefono_f">Colonia</label>
                        <input type="text" id="num_telefono_f" name="num_telefono_f" placeholder="Colonia">
                    </div>

                    <div class="input-group">
                        <label>Fotografia Frontal De La INE</label>
                        <div class="centrar">
                        <div class="container8">
                            <div class="wrapper8">
                            <div class="image8">
                                <img src="" alt="" id="colocar_img8">
                            </div>
                            <div class="content8">
                                <div class="icon8">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="text8">
                                    No hay ningun archivo
                                </div>
                            </div>
                            <div id="cancel-btn8">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="file-name8">
                                
                            </div>
                            </div>
                            <a onclick="defaultBtnActive8()" id="custom-btn8">Selecciona un archivo</a>
                            <input id="img8" name="img8" type="file"  onchange="revisarImagen8(this,1)">
                            <!--input que ayuda a sacar el link de base64 de la img -->
                            <input type="textarea" name="nuevaImagen8" id="nuevaImagen8" class="cuadrito">
                            <br>
                        </div>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Fotografia Trasera De La INE</label>
                        <div class="centrar">
                        <div class="container9">
                            <div class="wrapper9">
                            <div class="image9">
                                <img src="" alt="" id="colocar_img9">
                            </div>
                            <div class="content9">
                                <div class="icon9">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="text9">
                                    No hay ningun archivo
                                </div>
                            </div>
                            <div id="cancel-btn9">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="file-name9">
                                
                            </div>
                            </div>
                            <a onclick="defaultBtnActive9()" id="custom-btn9">Selecciona un archivo</a>
                            <input id="img9" name="img9" type="file"  onchange="revisarImagen9(this,1)">
                            <!--input que ayuda a sacar el link de base64 de la img -->
                            <input type="textarea" name="nuevaImagen9" id="nuevaImagen9" class="cuadrito">
                            <br>
                        </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="btns-group">
                    <a href="#" class="btn btn-prev">Anterior</a>
                    <input type="submit" value="Guardar" class="boton_finalizar" style="width: 170px">
                </div>
            </div>
        </form>
</body>

    <!--scripts-->

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ url('js/reservacion.js')}}"></script>
    <script type="text/javascript" src="{{ url('js/Movimiento.js')}}"></script>

</html>




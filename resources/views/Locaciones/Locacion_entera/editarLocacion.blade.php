<!DOCTYPE html>
<html lang="en">
<head>
<!--METAS-->
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<!--LINKS-->
 <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
 <link rel="stylesheet" href="{{ asset('assets/editar_ent_secc.css') }}">

<title>Editar Locacion</title>
</head>

  <body>
<!-- libreria para usar las alertas-->
@include('sweetalert::alert')
    <form action="{{route('update_loc', ["locacion"=>$locacion->Id_locacion, "servicio_bano"=>$servicio_bano[0]->Id_servicio_bano, "servicio_cama"=>$servicio_cama[0] ->Id_servicio_cama] )}}" class="form" method="POST" enctype="multipart/form-data">
        @csrf 
        @method('PUT')

<!-- titulo central -->        
    <div class="titulo_central">
        <div class="centrar">
            <label><h2>Editando Locacion Entera</h2></label>
        </div>
    </div>
         
<!-- Progress bar -->
            <div class="progressbar">
                <div class="progress" id="progress"></div>
        
                <div class="progress-step progress-step-active" data-title="Datos"></div>
                <div class="progress-step" data-title="Baños"></div>
                <div class="progress-step" data-title="Camas"></div>
                <div class="progress-step" data-title="Servicios"></div>
                <div class="progress-step" data-title="Fotos"></div>
            </div>

<!-- Step 1 -->
            <div class="form-step form-step-active">
                <div class="input-group" style="display: none;">
                    <label for="tipo_renta">¿Como se rentara la casa?</label>
                    <select name="tipo_renta" id="tipo_renta" class="form-control">
                        <option value="Entera" selected>Entera</option>
                    </select>
                </div>
                <div class="input-group">
                    <div class="titulo_central">
                        <div class="centrar">
                            <label for="nombre"><h3>Datos Del Lugar</h3></label>
                        </div>
                    </div>
                </div>
                    <div class="input-group">
                        <label>Estatus De La Casa</label>
                        <select name="estatus" id="estatus" class="form-control">
                              <option value="-1" selected disabled>Selecciona una opcion</option>
                              @foreach($estatus_locaciones as $estatus_locacion)
                              <option value="{{$estatus_locacion->Id_estado_ocupacion}}" @if($estatus_locacion->Id_estado_ocupacion == $locacion->Id_estado_ocupacion) selected @endif>{{$estatus_locacion->Id_estado_ocupacion}} .- {{$estatus_locacion->Nombre_estado}}</option>
                              @endforeach
                        </select>
                    </div>
                
                <div class="input-group">
                    <label for="nombre">Nombre De La Locacion:</label>
                    <input type="text" class="form-control"  name="nombre" id="nombre" placeholder="NickName" value="{{$locacion->Nombre_locacion}}">
                </div>
                
                <div class="input-group">
                    <label for="numero_ext">Numero Exterior:</label>
                    <input type="text" class="form-control"  name="numero_ext" id="numero_ext" placeholder="#" value="{{$locacion->Numero_ext}}">
                </div>
                <div class="input-group">
                    <label for="calle">Calle:</label>
                    <input type="text" class="form-control"  name="calle" id="calle" placeholder="Calle" value="{{$locacion->Calle}}">
                </div>
                <div class="input-group">
                    <label for="colonia">Colonia:</label>
                    <input type="text" class="form-control"  name="colonia" id="colonia" placeholder="Colonia" value="{{$locacion->Colonia}}">
                </div>
                <div class="input-group">
                    <label for="link">Link De Ubicacion De Google Maps:</label>
                    <input type="text" class="form-control"  name="LinkGM" id="LinkGM" placeholder="Link De Google Maps" value="{{$locacion->Ubi_google_maps}}">
                </div>
                <div class="input-group">
                    <label for="zona">¿En Que Zona De La Ciudad Se Encuentra?</label>
                    <input type="text" class="form-control"  name="zona" id="zona" placeholder="zona" value="{{$locacion->Zona_ciudad}}">
                </div>
                <div class="input-group">
                    <label for="pisos">No. Total De Pisos:</label>
                    <input type="text" class="form-control"  name="pisos" id="pisos" placeholder="Total De Pisos" value="{{$locacion->Numero_total_de_pisos}}">
                </div>
                <div class="input-group">
                    <label for="cap_personas">Capacidad De Personas:</label>
                    <input type="text" class="form-control"  name="cap_personas" id="cap_personas" placeholder="Capacidad Total" value="{{$locacion->Capacidad_personas}}">
                </div>
                <div class="input-group">
                    <label for="noche">Precio Por Noche:</label>
                    <input type="text" class="form-control"  name="precio_noche" id="precio_noche" placeholder="$" value="{{$locacion->Precio_noche}}">
                </div>
                <div class="input-group">
                    <label for="semana">Precio Por Semana:</label>
                    <input type="text" class="form-control"  name="precio_semana" id="precio_semana" placeholder="$" value="{{$locacion->Precio_semana}}">
                </div>
                <div class="input-group">
                    <label for="catorcena">Precio Por Catorcena:</label>
                    <input type="text" class="form-control"  name="precio_catorcena" id="precio_catorcena" placeholder="$" value="{{$locacion->Precio_catorcedias}}">
                </div>
                <div class="input-group">
                    <label for="mes">Precio Por Mes:</label>
                    <input type="text" class="form-control"  name="precio_mes" id="precio_mes" placeholder="$" value="{{$locacion->Precio_mes}}">
                </div>
                <div class="input-group">
                    <label for="garantia">Deposito De Garantia:</label>
                    <input type="text" class="form-control"  name="garantia" id="garantia" placeholder="$" value="{{$locacion->Deposito_garantia_casa}}">
                </div>
                <div class="input-group">
                    <label for="p_ext_mes">Cobro Por Persona Extra(Mes):</label>
                    <input type="text" class="form-control"  name="p_ext_mes" id="p_ext_mes" placeholder="$" value="{{$locacion->Cobro_p_ext_mes_c}}">
                </div>
                <div class="input-group">
                    <label for="p_ext_catorce">Cobro Por Persona Extra(Catorcena):</label>
                    <input type="text" class="form-control"  name="p_ext_catorce" id="p_ext_catorce" placeholder="$" value="{{$locacion->Cobro_p_ext_catorcena_c}}">
                </div>
                <div class="input-group">
                    <label for="p_ext_noche">Cobro Por Persona Extra(Noche):</label>
                    <input type="text" class="form-control"  name="p_ext_noche" id="p_ext_noche" placeholder="$" value="{{$locacion->Cobro_p_ext_noche_c}}">
                </div>
                <div class="input-group">
                    <label for="c_anticipo_mes">Cobro De Apartado(Mes):</label>
                    <input type="text" class="form-control"  name="c_anticipo_mes" id="c_anticipo_mes" placeholder="$" value="{{$locacion->Cobro_anticipo_mes_c}}">
                </div>
                <div class="input-group">
                    <label for="c_anticipo_catorce">Cobro De Apartado(Catorcena):</label>
                    <input type="text" class="form-control"  name="c_anticipo_catorce" id="c_anticipo_catorce" placeholder="$" value="{{$locacion->Cobro_anticipo_catorcena_c}}">
                </div>
                <div class="input-group">
                    <label for="total_hab">No. Total De Habitaciones</label>
                    <input type="text" class="form-control"  name="total_hab" id="total_hab" placeholder="#" value="{{$locacion->Numero_total_habitaciones}}">
                </div>
                <div class="input-group">
                    <label for="superficie">Espacio De Superficie:</label>
                    <input type="text" class="form-control"  name="superficie" id="superficie" placeholder="m2" value="{{$locacion->Espacio_superficie}}">
                </div>
                <div class="input-group">
                    <label for="nota">Deja Una Nota Por Si Tienes Algo Importante Que Remarcar</label>
                    <textarea name="nota" id="nota" cols="40" rows="10" class="form-control" >{{$locacion->Nota}}</textarea>
                </div>
                <div class="input-group">
                    <label for="descripcion">Descripcion Del Lugar</label>
                    <textarea name="descripcion" id="descripcion" cols="40" rows="10" class="form-control">{{$locacion->Descripcion}}</textarea>
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
                            <label for="nombre"><h3>Tipo De Baño</h3></label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label for="b_compartido">Baño Compartido:</label>
                    <input type="number" class="form-control"  name="b_compartido" id="b_compartido" value="{{$servicio_bano[0]->Bano_compartido}}">
                </div>
                <div class="input-group">
                    <label for="b_medio">Medio Baño:</label>
                    <input type="number" class="form-control"  name="b_medio" id="b_medio" value="{{$servicio_bano[0]->Bano_medio}}">
                </div>
                <div class="input-group">
                    <label for="b_completo">Baño Completo:</label>
                    <input type="number" class="form-control"  name="b_completo" id="b_completo" value="{{$servicio_bano[0]->Bano_completo}}">
                </div>
                <div class="input-group">
                    <label for="b_completorl">Baño Completo Con Regadera/Lavamanos:</label>
                    <input type="number" class="form-control"  name="b_completorl" id="b_completorl" value="{{$servicio_bano[0]->Bano_completo_RL}}">
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
                            <label for="nombre"><h3>Tipo De Cama</h3></label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label for="c_individual">¿El Lugar Tiene Las Camas Juntas?</label>
                    <select name="camas_juntas" id="camas_juntas" class="form-control">
                        <option value="-1" disabled selected>Selecciona una opcion</option>
                        <option value="{{$locacion->Camas_juntas}}" @if($locacion->Id_locacion ) selected @endif>{{$locacion->Camas_juntas}}</option>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="c_individual">Cama Individual:</label>
                    <input type="number" class="form-control"  name="c_individual" id="c_individual" value="{{$servicio_cama[0]->Cama_individual}}">
                </div>
                <div class="input-group">
                    <label for="c_matrimonial">Cama Matrimonial:</label>
                    <input type="number" class="form-control"  name="c_matrimonial" id="c_matrimonial" value="{{$servicio_cama[0]->Cama_matrimonial}}">
                </div>
                <div class="input-group">
                    <label for="c_l_individual">Litera Individual:</label>
                    <input type="number" class="form-control"  name="c_l_individual" id="c_l_individual" value="{{$servicio_cama[0]->Litera_individual}}">
                </div>
                <div class="input-group">
                    <label for="c_l_matrimonial">Litera Matrimonial:</label>
                    <input type="number" class="form-control"  name="c_l_matrimonial" id="c_l_matrimonial" value="{{$servicio_cama[0]->Litera_matrimonial}}">
                </div>
                <div class="input-group">
                    <label for="c_litera_im">Litera individual Matrimonial:</label>
                    <input type="number" class="form-control"  name="c_litera_im" id="c_litera_im" value="{{$servicio_cama[0]->Litera_ind_mat}}">
                </div>
                <div class="input-group">
                    <label for="c_kingsize">Cama Kingsize:</label>
                    <input type="number" class="form-control"  name="c_kingsize" id="c_kingsize" value="{{$servicio_cama[0]->Cama_kingsize}}">
                </div>
                <div class="btns-group">
                    <a href="#" class="btn btn-prev">Anterior</a>
                    <a href="#" class="btn btn-next">Siguiente</a>
                </div>
            </div>
            
<!-- Step 4 -->
     
        <div class="form-step">
<!--  seccion 1 -->
            <div class="input-group">
                <div class="titulo_central">
                    <div class="centrar">
                        <label for="nombre"><h3>Servicios</h3></label>
                    </div>
                </div>
            </div>
            <div class="centrar" style="text-align: center">
                <h3>Asegurate De Seleccionar Al Menos Un Servicio.</h3>
            </div>
            <div class="subtitulo">
                <label> Sin Servicios </label>
                    <i id="despliegue_sinservicio" class="fa-solid fa-chevron-down" onclick="presentar_sinservicio()"></i>
                    <i id="ocultar_sinservicio" class="fa-solid fa-chevron-up" onclick="esconder_sinservicio()"></i>   
            </div>

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
                                        name="arregloServicios[]" id="{{$servicio->Nombre_servicio}}" value="{{$servicio->Id_servicios_estancia}}">
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
            <div class="subtitulo">
                    <label> Cocina </label>
                    <i id="despliegue_cocina" class="fa-solid fa-chevron-down" onclick="presentar_cocina()"></i>
                    <i id="ocultar_cocina" class="fa-solid fa-chevron-up" onclick="esconder_cocina()"></i>
            </div>

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
                                        name="arregloServicios[]" id="{{$servicio->Nombre_servicio}}" value="{{$servicio->Id_servicios_estancia}}">
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


<!--seccion 2-->
                <div class="subtitulo">
                    <label> Lavanderia </label>
                        <i id="despliegue_lavanderia" class="fa-solid fa-chevron-down" onclick="presentar_lavanderia()"></i>
                        <i id="ocultar_lavanderia" class="fa-solid fa-chevron-up" onclick="esconder_lavanderia()"></i>   
                </div>

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
                                            name="arregloServicios[]" id="{{$servicio->Nombre_servicio}}" value="{{$servicio->Id_servicios_estancia}}">
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

<!--seccion 3-->
                <div class="subtitulo">
                    <label> Estacionamiento </label>
                        <i id="despliegue_estacionamiento" class="fa-solid fa-chevron-down" onclick="presentar_estacionamiento()"></i>
                        <i id="ocultar_estacionamiento" class="fa-solid fa-chevron-up" onclick="esconder_estacionamiento()"></i>   
                </div>

                <div class="detalle_estacionamiento" id="detalle_estacionamiento">
                  
                    <div class="input-group">
                        @foreach($servicios as $servicio)
                             @if($servicio -> Seccion_servicio == "Estacionamiento")
                                 <div class="centrar">
                                     <label for="{{$servicio->Nombre_servicio}}" class="option_item">
                                        <input type="checkbox" class="checkbox"
                                        @foreach($relacion_servicio as $relacion)
                                            @if($relacion->Id_servicios_estancia == $servicio->Id_servicios_estancia)
                                                checked
                                            @endif
                                        @endforeach
                                        name="arregloServicios[]" id="{{$servicio->Nombre_servicio}}" value="{{$servicio->Id_servicios_estancia}}" onclick="activar_cochera()">
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

                    <div class="input-group">
                        <label for="num_cochera">¿Cuantos Espacios De Cochera Tiene?</label>
                        <input type="number" class="form-control"  name="num_cochera" id="num_cochera" disabled value="{{$locacion->Total_cocheras}}">
                    </div>    
                </div>

<!--seccion 4-->
                <div class="subtitulo">
                    <label> Servicios Extras </label>
                        <i id="despliegue_otro_s" class="fa-solid fa-chevron-down" onclick="presentar_otro_s()"></i>
                        <i id="ocultar_otro_s" class="fa-solid fa-chevron-up" onclick="esconder_otro_s()"></i>   
                </div>

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
                                             name="arregloServicios[]" id="{{$servicio->Nombre_servicio}}" value="{{$servicio->Id_servicios_estancia}}">
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
                    <br>
                    <div class="btns-group">
                        <a href="#" class="btn btn-prev">Anterior</a>
                        <a href="#" class="btn btn-next">Siguiente</a>
                    </div>
        </div>

<!-- Step 5 -->
            <div class="form-step">
                <div class="input-group">
                    <div class="titulo_central">
                        <div class="centrar">
                            <label for="nombre"><h3>Fotografias Del Lugar</h3></label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <div class="centrar">
                        <label>Las Fotografias deben de ser de...</label>
                    </div>
                </div>
 <!-- foto 1-->
                <div class="input-group">
                        <label>Fotografia #1:</label>
                        <div class="centrar">
                        <div class="container">
                            <div class="wrapper">
                               <div class="image">
                                  <img src="" alt="" id="colocar_img1">
                               </div>
                               <div class="content">
                                  <div class="icon">
                                     <i class="fas fa-cloud-upload-alt"></i>
                                  </div>
                                  <div class="text">
                                     No hay ningun archivo
                                  </div>
                               </div>
                               <div id="cancel-btn">
                                  <i class="fas fa-times"></i>
                               </div>
                               <div class="file-name">
                                  
                               </div>
                            </div>
                            <a onclick="defaultBtnActive1()" id="custom-btn">Selecciona un archivo</a>
                            <input id="img1" name="img1" type="file" hidden onchange="revisarImagen1(this,1)">
                            <br>
                            <!--input que ayuda a sacar el link de base64 de la img -->
                            <input type="textarea" name="nuevaImagen1" id="nuevaImagen1" class="cuadrito">
                            <br>

                         </div>
                        </div>
                    </div>
 <!-- foto 2-->
                 <div class="input-group">
                    <label>Fotografia #2:</label>
                    <div class="centrar">
                    <div class="container2">
                        <div class="wrapper2">
                           <div class="image2">
                              <img src="" alt="" id="colocar_img2">
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
                        <br>
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen2" id="nuevaImagen2" class="cuadrito">
                        <br>
                    </div>
                    </div>
                 </div>
                
 <!-- foto 3-->
                 <div class="input-group">
                    <label>Fotografia #3:</label>
                    <div class="centrar">
                    <div class="container3">
                        <div class="wrapper3">
                           <div class="image3">
                              <img src="" alt="" id="colocar_img3">
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

 <!-- foto 4-->
                 <div class="input-group">
                    <label>Fotografia #4:</label>
                    <div class="centrar">
                    <div class="container4">
                        <div class="wrapper4">
                           <div class="image4">
                              <img src="" alt="" id="colocar_img4">
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

 <!-- foto 5-->
                 <div class="input-group">
                    <label>Fotografia #5:</label>
                    <div class="centrar">
                    <div class="container5">
                        <div class="wrapper5">
                           <div class="image5">
                              <img src="" alt="" id="colocar_img5">
                           </div>
                           <div class="content5">
                              <div class="icon5">
                                 <i class="fas fa-cloud-upload-alt"></i>
                              </div>
                              <div class="text5">
                                 No hay ningun archivo
                              </div>
                           </div>
                           <div id="cancel-btn5">
                              <i class="fas fa-times"></i>
                           </div>
                           <div class="file-name5">
                              
                           </div>
                        </div>
                        <a onclick="defaultBtnActive5()" id="custom-btn5">Selecciona un archivo</a>
                        <input id="img5" name="img5" type="file" hidden onchange="revisarImagen5(this,1)">
                        <br>
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen5" id="nuevaImagen5" class="cuadrito">
                        <br>
                    </div>
                    </div>
                 </div>

 <!-- foto 6-->
                 <div class="input-group">
                    <label>Fotografia #6:</label>
                    <div class="centrar">
                    <div class="container6">
                        <div class="wrapper6">
                           <div class="image6">
                              <img src="" alt="" id="colocar_img6">
                           </div>
                           <div class="content6">
                              <div class="icon6">
                                 <i class="fas fa-cloud-upload-alt"></i>
                              </div>
                              <div class="text6">
                                 No hay ningun archivo
                              </div>
                           </div>
                           <div id="cancel-btn6">
                              <i class="fas fa-times"></i>
                           </div>
                           <div class="file-name6">
                              
                           </div>
                        </div>
                        <a onclick="defaultBtnActive6()" id="custom-btn6">Selecciona un archivo</a>
                        <input id="img6" name="img6" type="file" hidden onchange="revisarImagen6(this,1)">
                        <br>
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen6" id="nuevaImagen6" class="cuadrito">
                        <br>
                    </div>
                    </div>
                 </div>

                <div class="btns-group">
                    <a href="#" class="btn btn-prev">Anterior</a>
                    <input type="submit" value="Guardar" class="boton_finalizar">
                </div>

                </form>
                <br><br><br>

                <div class="centrar">
                    <label><h3>Fotos Existentes De La Locacion</h3></label>
                </div>
                <div class="input-group">
                    <div class="fotos_existentes">
                        <div class="row mt-3 p-3">
                            <div>
                                @foreach ($fotos as $foto)
                                <form action="{{route('destroyimg', $foto->Id_foto_lugar )}}" method="post">
                                @csrf
                                <img class="card-img col-md-2 moverfoto" src="{{asset('uploads/locacion/').'/'.$foto->Ruta_lugar  }}" alt="">
                                <button class="btn btn-danger eliminar ">X</button>
                                </form>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>

  </body>

  
  <!--scripts-->

  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="{{ url('js/Agregar_locacion_ent_secc.js')}}"></script>
  <script type="text/javascript" src="{{ url('js/Movimiento.js')}}"></script>
  <script>
    //funciones para transofrmar las imagenes a base64 para su almacenamiento    
    //funcion para mostrar una previsualizacion de imagenes
    const wrapper = document.querySelector(".wrapper");
    const fileName = document.querySelector(".file-name");
    const defaultBtn = document.querySelector("#img1");
    const customBtn = document.querySelector("#custom-btn");
    const cancelBtn = document.querySelector("#cancel-btn i");
    const img = document.querySelector("#colocar_img1");
    const esconder = document.querySelector(".content");
    let regExp = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
    function defaultBtnActive1(){
      defaultBtn.click();
    }
    defaultBtn.addEventListener("change", function(){
      const file = this.files[0];
      if(file){
        const reader = new FileReader();
        reader.onload = function(){
          const result = reader.result;
          img.src = result;
          wrapper.classList.add("active");
        }
        cancelBtn.addEventListener("click", function(){
          img.src = "";
          wrapper.classList.remove("active");
        })
        reader.readAsDataURL(file);
    
        esconder.style.display = 'none';
      }
      if(this.value){
        let valueStore = this.value.match(regExp);
        fileName.textContent = valueStore;
      }
    });
    
    
    //funcion para transformar la imagen 1 a base64 y lo manda al textarea 
    var imagen = [];
        
    function revisarImagen1(input, num){
        console.log(input.files);
        var id_preview = input.getAttribute("id") + "_preview";
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onloadend = function (e) {
                var id_preview_text = "#"+id_preview;
                var base64image = e.target.result;                    
                $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
                var canvas=document.getElementById("tempCanvas");
                var ctx=canvas.getContext("2d");
                var cw=canvas.width;
                var ch=canvas.height;
                var maxW=800;
                var maxH=800;
                var img = new Image;
                img.src=this.result;
                img.onload = function(){
                    var iw=img.width;
                    var ih=img.height;
                    var scale=Math.min((maxW/iw),(maxH/ih));
                    var iwScaled=iw*scale;
                    var ihScaled=ih*scale;
                    canvas.width=iwScaled;
                    canvas.height=ihScaled;
                    ctx.drawImage(img,0,0,iwScaled,ihScaled);
                    base64image = canvas.toDataURL("image/jpeg");                       
                    $(id_preview_text).attr('src', base64image).width(250).height(157);
                    imagen[num] = base64image;
                    $("#tempCanvas").remove();
                    $('#nuevaImagen1').val(base64image);
                    console.log($('#nuevaImagen1').val());
                }
            };
            reader.readAsDataURL(input.files[0]);
            $('#imagen_preview').show();
        }
    }
    
    //imagen2
    const wrapper2 = document.querySelector(".wrapper2");
    const fileName2 = document.querySelector(".file-name2");
    const defaultBtn2 = document.querySelector("#img2");
    const customBtn2 = document.querySelector("#custom-btn2");
    const cancelBtn2 = document.querySelector("#cancel-btn2 i");
    const img2 = document.querySelector("#colocar_img2");
    const esconder2 = document.querySelector(".content2");
    let regExp2 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
    function defaultBtnActive2(){
      defaultBtn2.click();
    }
    defaultBtn2.addEventListener("change", function(){
      const file = this.files[0];
      if(file){
        const reader = new FileReader();
        reader.onload = function(){
          const result = reader.result;
          img2.src = result;
          wrapper2.classList.add("active2");
        }
        cancelBtn2.addEventListener("click", function(){
          img2.src = "";
          wrapper2.classList.remove("active2");
        })
        reader.readAsDataURL(file);
    
        esconder2.style.display = 'none';
      }
      if(this.value){
        let valueStore = this.value.match(regExp2);
        fileName2.textContent = valueStore;
      }
    });
    
    
    //funcion para transformar la imagen 2 a base64 y lo manda al textarea 
    
    var imagen = [];
        
    function revisarImagen2(input, num){
      console.log(input.files);
      var id_preview = input.getAttribute("id") + "_preview";
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onloadend = function (e) {
              var id_preview_text = "#"+id_preview;
              var base64image = e.target.result;                    
              $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
              var canvas=document.getElementById("tempCanvas");
              var ctx=canvas.getContext("2d");
              var cw=canvas.width;
              var ch=canvas.height;
              var maxW=800;
              var maxH=800;
              var img = new Image;
              img.src=this.result;
              img.onload = function(){
                  var iw=img.width;
                  var ih=img.height;
                  var scale=Math.min((maxW/iw),(maxH/ih));
                  var iwScaled=iw*scale;
                  var ihScaled=ih*scale;
                  canvas.width=iwScaled;
                  canvas.height=ihScaled;
                  ctx.drawImage(img,0,0,iwScaled,ihScaled);
                  base64image = canvas.toDataURL("image/jpeg");                       
                  $(id_preview_text).attr('src', base64image).width(250).height(157);
                  imagen[num] = base64image;
                  $("#tempCanvas").remove();
                  $('#nuevaImagen2').val(base64image);
                  console.log($('#nuevaImagen2').val());
              }
          };
          reader.readAsDataURL(input.files[0]);
          $('#imagen_preview').show();
      }}
    
    
    //imagen3
    const wrapper3 = document.querySelector(".wrapper3");
    const fileName3 = document.querySelector(".file-name3");
    const defaultBtn3 = document.querySelector("#img3");
    const customBtn3 = document.querySelector("#custom-btn3");
    const cancelBtn3 = document.querySelector("#cancel-btn3 i");
    const img3 = document.querySelector("#colocar_img3");
    const esconder3 = document.querySelector(".content3");
    let regExp3 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
    function defaultBtnActive3(){
      defaultBtn3.click();
    }
    defaultBtn3.addEventListener("change", function(){
      const file = this.files[0];
      if(file){
        const reader = new FileReader();
        reader.onload = function(){
          const result = reader.result;
          img3.src = result;
          wrapper3.classList.add("active3");
        }
        cancelBtn3.addEventListener("click", function(){
          img3.src = "";
          wrapper3.classList.remove("active3");
        })
        reader.readAsDataURL(file);
    
        esconder3.style.display = 'none';
      }
      if(this.value){
        let valueStore = this.value.match(regExp3);
        fileName3.textContent = valueStore;
      }
    });
    
    
    //funcion para transformar la imagen 3 a base64 y lo manda al textarea 
    var imagen = [];
        
    function revisarImagen3(input, num){
        console.log(input.files);
        var id_preview = input.getAttribute("id") + "_preview";
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onloadend = function (e) {
                var id_preview_text = "#"+id_preview;
                var base64image = e.target.result;                    
                $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
                var canvas=document.getElementById("tempCanvas");
                var ctx=canvas.getContext("2d");
                var cw=canvas.width;
                var ch=canvas.height;
                var maxW=800;
                var maxH=800;
                var img = new Image;
                img.src=this.result;
                img.onload = function(){
                    var iw=img.width;
                    var ih=img.height;
                    var scale=Math.min((maxW/iw),(maxH/ih));
                    var iwScaled=iw*scale;
                    var ihScaled=ih*scale;
                    canvas.width=iwScaled;
                    canvas.height=ihScaled;
                    ctx.drawImage(img,0,0,iwScaled,ihScaled);
                    base64image = canvas.toDataURL("image/jpeg");                       
                    $(id_preview_text).attr('src', base64image).width(250).height(157);
                    imagen[num] = base64image;
                    $("#tempCanvas").remove();
                    $('#nuevaImagen3').val(base64image);
                    console.log($('#nuevaImagen3').val());
                }
            };
            reader.readAsDataURL(input.files[0]);
            $('#imagen_preview').show();
        }
    }          
    
    //imagen4
    const wrapper4 = document.querySelector(".wrapper4");
    const fileName4 = document.querySelector(".file-name4");
    const defaultBtn4 = document.querySelector("#img4");
    const customBtn4 = document.querySelector("#custom-btn4");
    const cancelBtn4 = document.querySelector("#cancel-btn4 i");
    const img4 = document.querySelector("#colocar_img4");
    const esconder4 = document.querySelector(".content4");
    let regExp4 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
    function defaultBtnActive4(){
      defaultBtn4.click();
    }
    defaultBtn4.addEventListener("change", function(){
      const file = this.files[0];
      if(file){
        const reader = new FileReader();
        reader.onload = function(){
          const result = reader.result;
          img4.src = result;
          wrapper4.classList.add("active4");
        }
        cancelBtn4.addEventListener("click", function(){
          img4.src = "";
          wrapper4.classList.remove("active4");
        })
        reader.readAsDataURL(file);
    
        esconder4.style.display = 'none';
      }
      if(this.value){
        let valueStore = this.value.match(regExp4);
        fileName4.textContent = valueStore;
      }
    });
    
    
    //funcion para transformar la imagen 4 a base64 y lo manda al textarea 
    var imagen = [];
        
    function revisarImagen4(input, num){
        console.log(input.files);
        var id_preview = input.getAttribute("id") + "_preview";
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onloadend = function (e) {
                var id_preview_text = "#"+id_preview;
                var base64image = e.target.result;                    
                $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
                var canvas=document.getElementById("tempCanvas");
                var ctx=canvas.getContext("2d");
                var cw=canvas.width;
                var ch=canvas.height;
                var maxW=800;
                var maxH=800;
                var img = new Image;
                img.src=this.result;
                img.onload = function(){
                    var iw=img.width;
                    var ih=img.height;
                    var scale=Math.min((maxW/iw),(maxH/ih));
                    var iwScaled=iw*scale;
                    var ihScaled=ih*scale;
                    canvas.width=iwScaled;
                    canvas.height=ihScaled;
                    ctx.drawImage(img,0,0,iwScaled,ihScaled);
                    base64image = canvas.toDataURL("image/jpeg");                       
                    $(id_preview_text).attr('src', base64image).width(250).height(157);
                    imagen[num] = base64image;
                    $("#tempCanvas").remove();
                    $('#nuevaImagen4').val(base64image);
                    console.log($('#nuevaImagen4').val());
                }
            };
            reader.readAsDataURL(input.files[0]);
            $('#imagen_preview').show();
        }
    }
    
    
    //imagen5
    const wrapper5 = document.querySelector(".wrapper5");
    const fileName5 = document.querySelector(".file-name5");
    const defaultBtn5 = document.querySelector("#img5");
    const customBtn5 = document.querySelector("#custom-btn5");
    const cancelBtn5 = document.querySelector("#cancel-btn5 i");
    const img5 = document.querySelector("#colocar_img5");
    const esconder5 = document.querySelector(".content5");
    let regExp5 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
    function defaultBtnActive5(){
      defaultBtn5.click();
    }
    defaultBtn5.addEventListener("change", function(){
      const file = this.files[0];
      if(file){
        const reader = new FileReader();
        reader.onload = function(){
          const result = reader.result;
          img5.src = result;
          wrapper5.classList.add("active5");
        }
        cancelBtn5.addEventListener("click", function(){
          img5.src = "";
          wrapper5.classList.remove("active5");
        })
        reader.readAsDataURL(file);
    
        esconder5.style.display = 'none';
      }
      if(this.value){
        let valueStore = this.value.match(regExp5);
        fileName5.textContent = valueStore;
      }
    });
    
    
    
    //funcion para transformar la imagen 5 a base64 y lo manda al textarea 
    var imagen = [];
        
    function revisarImagen5(input, num){
        console.log(input.files);
        var id_preview = input.getAttribute("id") + "_preview";
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onloadend = function (e) {
                var id_preview_text = "#"+id_preview;
                var base64image = e.target.result;                    
                $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
                var canvas=document.getElementById("tempCanvas");
                var ctx=canvas.getContext("2d");
                var cw=canvas.width;
                var ch=canvas.height;
                var maxW=800;
                var maxH=800;
                var img = new Image;
                img.src=this.result;
                img.onload = function(){
                    var iw=img.width;
                    var ih=img.height;
                    var scale=Math.min((maxW/iw),(maxH/ih));
                    var iwScaled=iw*scale;
                    var ihScaled=ih*scale;
                    canvas.width=iwScaled;
                    canvas.height=ihScaled;
                    ctx.drawImage(img,0,0,iwScaled,ihScaled);
                    base64image = canvas.toDataURL("image/jpeg");                       
                    $(id_preview_text).attr('src', base64image).width(250).height(157);
                    imagen[num] = base64image;
                    $("#tempCanvas").remove();
                    $('#nuevaImagen5').val(base64image);
                    console.log($('#nuevaImagen5').val());
                }
            };
            reader.readAsDataURL(input.files[0]);
            $('#imagen_preview').show();
        }
    }
    
    
    
    //imagen6
    const wrapper6 = document.querySelector(".wrapper6");
    const fileName6 = document.querySelector(".file-name6");
    const defaultBtn6 = document.querySelector("#img6");
    const customBtn6 = document.querySelector("#custom-btn6");
    const cancelBtn6 = document.querySelector("#cancel-btn6 i");
    const img6 = document.querySelector("#colocar_img6");
    const esconder6 = document.querySelector(".content6");
    let regExp6 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
    function defaultBtnActive6(){
      defaultBtn6.click();
    }
    defaultBtn6.addEventListener("change", function(){
      const file = this.files[0];
      if(file){
        const reader = new FileReader();
        reader.onload = function(){
          const result = reader.result;
          img6.src = result;
          wrapper6.classList.add("active6");
        }
        cancelBtn6.addEventListener("click", function(){
          img6.src = "";
          wrapper6.classList.remove("active6");
        })
        reader.readAsDataURL(file);
    
        esconder6.style.display = 'none';
      }
      if(this.value){
        let valueStore = this.value.match(regExp6);
        fileName6.textContent = valueStore;
      }
    });
    
    //funcion para transformar la imagen 6 a base64 y lo manda al textarea 
      var imagen = [];
        
     function revisarImagen6(input, num){
        console.log(input.files);
        var id_preview = input.getAttribute("id") + "_preview";
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onloadend = function (e) {
                var id_preview_text = "#"+id_preview;
                var base64image = e.target.result;                    
                $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
                var canvas=document.getElementById("tempCanvas");
                var ctx=canvas.getContext("2d");
                var cw=canvas.width;
                var ch=canvas.height;
                var maxW=800;
                var maxH=800;
                var img = new Image;
                img.src=this.result;
                img.onload = function(){
                    var iw=img.width;
                    var ih=img.height;
                    var scale=Math.min((maxW/iw),(maxH/ih));
                    var iwScaled=iw*scale;
                    var ihScaled=ih*scale;
                    canvas.width=iwScaled;
                    canvas.height=ihScaled;
                    ctx.drawImage(img,0,0,iwScaled,ihScaled);
                    base64image = canvas.toDataURL("image/jpeg");                       
                    $(id_preview_text).attr('src', base64image).width(250).height(157);
                    imagen[num] = base64image;
                    $("#tempCanvas").remove();
                    $('#nuevaImagen6').val(base64image);
                    console.log($('#nuevaImagen6').val());
                }
            };
            reader.readAsDataURL(input.files[0]);
            $('#imagen_preview').show();
        }
    }
      </script>

</html>
  
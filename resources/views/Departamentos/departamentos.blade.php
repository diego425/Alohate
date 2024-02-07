<!--=============== CSS local===============-->
<link rel="stylesheet" href="{{ asset('assets/departamentos_style.css') }}" >
<!--=============== CSS web ===============-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">      
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

@extends('layouts.menu_layout')
@section('MenuPrincipal')
<!-- libreria para usar las alertas-->
@include('sweetalert::alert')
<!--=============== ENCABEZADO ===============-->

<header class="encabezado">
    <div class="overlay">
       <h1>Departamentos De La Casa {{$locacion[0]->Nombre_locacion}}</h1>
       <br>
       <a href="{{route('detalle_loc', $locacion[0]->Id_locacion)}}" type="button" class="boton">Regresar</a>
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
                           <label>Estatus</label>
                           <select name="estatus" id="estatus" class="form-control">
                                 <option value="-1" selected disabled>Selecciona una opcion</option>
                                 
                           </select>
                     </div>
                     <div class="titulos_buscador">
                           <label>Capacidad De Personas</label>
                           <input type="text" class="form-control" name="personas" id="personas" placeholder="Numero de personas">
                     </div>
                     
                     <div class="titulos_buscador">
                           <label>Fecha De Reservacion</label>
                           <input type="date" class="form-control" name="reservacion" id="reservacion" placeholder="$">
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
                  @if($locacion[0]->Numero_depas_actuales == $locacion[0]->Numero_total_depas)
                        <a href="{{route('depasllenos')}}" class="btn btn-secondary" ><i class="ri-add-circle-fill"></i></a>
                  @else($locacion[0]->Numero_depas_actuales < $locacion[0]->Numero_total_depas)
                        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#agregar"><i class="ri-add-circle-fill"></i></button>
                  @endif
              </div>
              <div class="titulo_gestion">
               <h5><label>Gestion De Los Departamentos</label></h5>
              </div>
         </div>
   </div>
</div>

@if(count($departamentos) <=0)
      <div class="seccion_padre_b">
        <div class="seccion_hijo_t"> 
          <div class="centrar_texto">
                <div class="texto_solo">
                <p><h5>No se encontro ningun departamento</h5></p>
                </div>
          </div>
        </div>
      </div>
@else


<!--=============== cabecera para planta baja  ===============-->
@if($departamentos[0]->totaldepaspisobajo <= 0)

@else
<div class="seccion_padre_b">
      <div class="seccion_hijo_p"> 
            <div class="seccion_interno_p">
                  <div class="centrar_texto">
                        <p><p><h5>Planta Baja</h5></p>
                        <i id="despliegue_planta_b" class="fa-solid fa-chevron-down" onclick="presentar_planta_b()"></i>
                        <i id="ocultar_planta_b" class="fa-solid fa-chevron-up" onclick="esconder_planta_b()"></i>
                        </p>
                  </div>

            <div class="detalle_planta_b" id="detalle_planta_b">
                  @foreach($departamentos as $departamento)
                  @if($departamento -> Nombre_planta == "Planta baja")
                  <div class="seccion_hijo_t">
                        <div class="centrar_texto">
                        <div> 

                              <p><h5>Nombre Del Departamento</h5>
                              <div class="gris"><h6>{{$departamento->Nombre_depa}}</h6></div>
                              </p>

                              <p><h5>Estatus</h5>
                              @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                              <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desocupada")
                              <div class="gris"><h6 style="color: mediumseagreen">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Reservada")
                              <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desactivada")
                              <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Rentada")
                              <div class="gris"><h6 style="color: rgb(33, 36, 207)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif

                              @if($departamento->Nombre_estado == "Pago por confirmar")
                              <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
                              </p>
                              <i class="fa-solid fa-chevron-down despliegue_departamento despliegue_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}"></i>
                              <i class="fa-solid fa-chevron-up ocultar_departamento ocultar_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}" style="display: none"></i>
                        </div>
                        
                        </div>
                        <div class="detalle_departamento ocultar{{$departamento->Id_departamento}} "> 
                              <div class="centrar_texto"> 
                                    <p><h5>Precios </h5>
                                    <div class="gris">
                                          <h6>Noche ${{$departamento->Precio_noche}}MXN</h6>
                                          <h6>Semana ${{$departamento->Precio_semana}}MXN</h6>
                                          <h6>Catorcedias ${{$departamento->Precio_catorcedias}}MXN</h6>
                                          <h6>Mes ${{$departamento->Precio_mes}}MXN</h6>
                                          <h6>Deposito De Garantia ${{$departamento->Deposito_garantia_dep}}MXN</h6>
                                          <h6>Monto De Apartado(-2semanas): ${{$departamento->Cobro_anticipo_catorcena_d}}MXN</h6>
                                          <h6>Monto De Apartado(+3semanas): ${{$departamento->Cobro_anticipo_mes_d}}MXN</h6>
                                    </div>
                                    </p>
                                    <p><h5>Notas Del Lugar</h5>
                                          <div class="gris">
                                           <h6>{{$departamento->Nota}}</h6>
                                          </div>
                                    </p>
                                    <p><h5>Capacidad De Personas</h5>
                                    <div class="gris"><h6>{{$departamento->Capacidad_personas}} Personas</h6></div>
                                    </p>
                                    <p><h5>Camas</h5>
                                    <div class="gris">
                                          @if($departamento->Cama_individual != "0")
                              
                                          @else
                                          <h6>Camas Individuales: {{$departamento->Cama_individual}} </h6>
                                          @endif
                                          @if($departamento->Cama_matrimonial < "0")

                                          @else
                                          <h6>Camas Matrimoniales: {{$departamento->Cama_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_individual < "0")

                                          @else
                                          <h6>Literas Individuales: {{$departamento->Litera_individual}} </h6>
                                          @endif
                                          @if($departamento->Litera_matrimonial < "0")

                                          @else
                                          <h6>Literas Matrimoniales: {{$departamento->Litera_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_ind_mat < "0")

                                          @else
                                          <h6>Literas Ind/Mat: {{$departamento->Litera_ind_mat}} </h6>
                                          @endif
                                          @if($departamento->Cama_kingsize < "0")

                                          @else
                                          <h6>Camas Kingsizes: {{$departamento->Cama_kingsize}} </h6>
                                          @endif

                                    </div>
                                    </p>
                                    <p><h5>Baños</h5>
                                    <div class="gris">
                                          @if($departamento->Bano_compartido < "0")

                                          @else
                                          <h6>Baños Compartidos: {{$departamento->Bano_compartido}} </h6>
                                          @endif
                                          @if($departamento->Bano_medio < "0")
                                          
                                          @else
                                          <h6>Medios Baños: {{$departamento->Bano_medio}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo < "0")
                                          
                                          @else
                                          <h6>Baños Completos: {{$departamento->Bano_completo}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo_RL < "0")
                                          
                                          @else
                                          <h6>Baños Completos Con Regadera Y Lavamanos: {{$departamento->Bano_completo_RL}} </h6>
                                          @endif
                                          
                                    </div>
                                    </p>
                                    <p><h5>Acciones</h5>
                                    <div class="gris">
                                          @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desocupada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <button class="btn btn-primary clickForm" href="{{route('viewreservadepoc', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}">Reservar</button>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Reservada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif

                                          @if($departamento->Nombre_estado == "Pago por confirmar")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desactivada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Rentada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <a href="" class="btn btn-danger">Terminar</a>
                                          @endif
                                    </div>
                                    </p>
                              </div>
                        </div>
                  </div>
                  @endif
                  @endforeach
            </div>
            </div>
      </div>
</div>
@endif



<!--=============== planta 1 ===============-->
@if($departamentos[0]->totaldepaspiso1 <= 0)
                  
@else
<div class="seccion_padre_b">
      <div class="seccion_hijo_p"> 
            <div class="seccion_interno_p">
                  <div class="centrar_texto">
                  <p><p><h5>Planta 1</h5></p>
                  <i id="despliegue_planta_1" class="fa-solid fa-chevron-down" onclick="presentar_planta_1()"></i>
                  <i id="ocultar_planta_1" class="fa-solid fa-chevron-up" onclick="esconder_planta_1()"></i>
                  </p>
                  </div>

            <div class="detalle_planta_1" id="detalle_planta_1">
                  @foreach($departamentos as $departamento)
                  @if($departamento -> Nombre_planta == "Planta 1")
                  <div class="seccion_hijo_t">
                        <div class="centrar_texto">
                        <div>  

                              <p><h5>Nombre Del Departamento</h5>
                              <div class="gris"><h6>{{$departamento->Nombre_depa}}</h6></div>
                              </p>

                              <p><h5>Estatus</h5>
                                    @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                              <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desocupada")
                              <div class="gris"><h6 style="color: mediumseagreen">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Reservada")
                              <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desactivada")
                              <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Rentada")
                              <div class="gris"><h6 style="color: rgb(33, 36, 207)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif

                              @if($departamento->Nombre_estado == "Pago por confirmar")
                              <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
                              </p>
                              <i class="fa-solid fa-chevron-down despliegue_departamento despliegue_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}"></i>
                              <i class="fa-solid fa-chevron-up ocultar_departamento ocultar_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}" style="display: none"></i>
                        </div>

                        </div>
                        <div class="detalle_departamento ocultar{{$departamento->Id_departamento}} "> 
                              <div class="centrar_texto"> 
                                    <p><h5>Precios </h5>
                                    <div class="gris">
                                          <h6>Noche ${{$departamento->Precio_noche}}MXN</h6>
                                          <h6>Semana ${{$departamento->Precio_semana}}MXN</h6>
                                          <h6>Catorcedias ${{$departamento->Precio_catorcedias}}MXN</h6>
                                          <h6>Mes ${{$departamento->Precio_mes}}MXN</h6>
                                          <h6>Deposito De Garantia ${{$departamento->Deposito_garantia_dep}}MXN</h6>
                                          <h6>Monto De Apartado(-2semanas): ${{$departamento->Cobro_anticipo_catorcena_d}}MXN</h6>
                                          <h6>Monto De Apartado(+3semanas): ${{$departamento->Cobro_anticipo_mes_d}}MXN</h6>
                                    </div>
                                    </p>
                                    <p><h5>Notas Del Lugar</h5>
                                          <div class="gris">
                                           <h6>{{$departamento->Nota}}</h6>
                                          </div>
                                    </p>
                                    <p><h5>Capacidad De Personas</h5>
                                    <div class="gris"><h6>{{$departamento->Capacidad_personas}} Personas</h6></div>
                                    </p>
                                    <p><h5>Camas</h5>
                                    <div class="gris">
                                          @if($departamento->Cama_individual != "0")
                              
                                          @else
                                          <h6>Camas Individuales: {{$departamento->Cama_individual}} </h6>
                                          @endif
                                          @if($departamento->Cama_matrimonial < "0")

                                          @else
                                          <h6>Camas Matrimoniales: {{$departamento->Cama_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_individual < "0")

                                          @else
                                          <h6>Literas Individuales: {{$departamento->Litera_individual}} </h6>
                                          @endif
                                          @if($departamento->Litera_matrimonial < "0")

                                          @else
                                          <h6>Literas Matrimoniales: {{$departamento->Litera_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_ind_mat < "0")

                                          @else
                                          <h6>Literas Ind/Mat: {{$departamento->Litera_ind_mat}} </h6>
                                          @endif
                                          @if($departamento->Cama_kingsize < "0")

                                          @else
                                          <h6>Camas Kingsizes: {{$departamento->Cama_kingsize}} </h6>
                                          @endif

                                    </div>
                                    </p>
                                    <p><h5>Baños</h5>
                                    <div class="gris">
                                          @if($departamento->Bano_compartido < "0")

                                          @else
                                          <h6>Baños Compartidos: {{$departamento->Bano_compartido}} </h6>
                                          @endif
                                          @if($departamento->Bano_medio < "0")
                                          
                                          @else
                                          <h6>Medios Baños: {{$departamento->Bano_medio}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo < "0")
                                          
                                          @else
                                          <h6>Baños Completos: {{$departamento->Bano_completo}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo_RL < "0")
                                          
                                          @else
                                          <h6>Baños Completos Con Regadera Y Lavamanos: {{$departamento->Bano_completo_RL}} </h6>
                                          @endif
                                          
                                    </div>
                                    </p>
                                    <p><h5>Acciones</h5>
                                    <div class="gris">
                                          @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desocupada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <button class="btn btn-primary clickForm" href="{{route('viewreservadepoc', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}">Reservar</button>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Reservada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif

                                          @if($departamento->Nombre_estado == "Pago por confirmar")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desactivada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Rentada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <a href="" class="btn btn-danger">Terminar</a>
                                          @endif
                                    </div>
                                    </p>
                              </div>
                        </div>
                  </div>
                  @endif
                  @endforeach
            </div>
            </div>
      </div>
</div>
@endif




<!--=============== planta 2 ===============-->
@if($departamentos[0]->totaldepaspiso2 <= 0)
                  
@else
<div class="seccion_padre_b">
      <div class="seccion_hijo_p"> 
            <div class="seccion_interno_p">
                  <div class="centrar_texto">
                  <p><p><h5>Planta 2</h5></p>
                  <i id="despliegue_planta_2" class="fa-solid fa-chevron-down" onclick="presentar_planta_2()"></i>
                  <i id="ocultar_planta_2" class="fa-solid fa-chevron-up" onclick="esconder_planta_2()"></i>
                  </p>
                  </div>
            <div class="detalle_planta_2" id="detalle_planta_2">
                  @foreach($departamentos as $departamento)
                  @if($departamento -> Nombre_planta == "Planta 2")
                  <div class="seccion_hijo_t">
                        <div class="centrar_texto">
                        <div>  
                              <p><h5>Nombre Del Departamento</h5>
                              <div class="gris"><h6>{{$departamento->Nombre_depa}}</h6></div>
                              </p>

                              <p><h5>Estatus</h5>

                              @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                              <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desocupada")
                              <div class="gris"><h6 style="color: mediumseagreen">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Reservada")
                              <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desactivada")
                              <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Rentada")
                              <div class="gris"><h6 style="color: rgb(33, 36, 207)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif

                              @if($departamento->Nombre_estado == "Pago por confirmar")
                              <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif

                              </p>
                              <i class="fa-solid fa-chevron-down despliegue_departamento despliegue_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}"></i>
                              <i class="fa-solid fa-chevron-up ocultar_departamento ocultar_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}" style="display: none"></i>
                        </div>

                        </div>
                        <div class="detalle_departamento ocultar{{$departamento->Id_departamento}} "> 
                              <div class="centrar_texto"> 
                                    <p><h5>Precios </h5>
                                    <div class="gris">
                                          <h6>Noche ${{$departamento->Precio_noche}}MXN</h6>
                                          <h6>Semana ${{$departamento->Precio_semana}}MXN</h6>
                                          <h6>Catorcedias ${{$departamento->Precio_catorcedias}}MXN</h6>
                                          <h6>Mes ${{$departamento->Precio_mes}}MXN</h6>
                                          <h6>Deposito De Garantia ${{$departamento->Deposito_garantia_dep}}MXN</h6>
                                          <h6>Monto De Apartado(-2semanas): ${{$departamento->Cobro_anticipo_catorcena_d}}MXN</h6>
                                          <h6>Monto De Apartado(+3semanas): ${{$departamento->Cobro_anticipo_mes_d}}MXN</h6>
                                    </div>
                                    </p>
                                    <p><h5>Notas Del Lugar</h5>
                                          <div class="gris">
                                           <h6>{{$departamento->Nota}}</h6>
                                          </div>
                                    </p>
                                    <p><h5>Capacidad De Personas</h5>
                                    <div class="gris"><h6>{{$departamento->Capacidad_personas}} Personas</h6></div>
                                    </p>
                                    <p><h5>Camas</h5>
                                    <div class="gris">
                                          @if($departamento->Cama_individual != "0")
                              
                                          @else
                                          <h6>Camas Individuales: {{$departamento->Cama_individual}} </h6>
                                          @endif
                                          @if($departamento->Cama_matrimonial < "0")

                                          @else
                                          <h6>Camas Matrimoniales: {{$departamento->Cama_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_individual < "0")

                                          @else
                                          <h6>Literas Individuales: {{$departamento->Litera_individual}} </h6>
                                          @endif
                                          @if($departamento->Litera_matrimonial < "0")

                                          @else
                                          <h6>Literas Matrimoniales: {{$departamento->Litera_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_ind_mat < "0")

                                          @else
                                          <h6>Literas Ind/Mat: {{$departamento->Litera_ind_mat}} </h6>
                                          @endif
                                          @if($departamento->Cama_kingsize < "0")

                                          @else
                                          <h6>Camas Kingsizes: {{$departamento->Cama_kingsize}} </h6>
                                          @endif

                                    </div>
                                    </p>
                                    <p><h5>Baños</h5>
                                    <div class="gris">
                                          @if($departamento->Bano_compartido < "0")

                                          @else
                                          <h6>Baños Compartidos: {{$departamento->Bano_compartido}} </h6>
                                          @endif
                                          @if($departamento->Bano_medio < "0")
                                          
                                          @else
                                          <h6>Medios Baños: {{$departamento->Bano_medio}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo < "0")
                                          
                                          @else
                                          <h6>Baños Completos: {{$departamento->Bano_completo}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo_RL < "0")
                                          
                                          @else
                                          <h6>Baños Completos Con Regadera Y Lavamanos: {{$departamento->Bano_completo_RL}} </h6>
                                          @endif
                                          
                                    </div>
                                    </p>
                                    <p><h5>Acciones</h5>
                                    <div class="gris">
                                          @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desocupada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <button class="btn btn-primary clickForm" href="{{route('viewreservadepoc', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}">Reservar</button>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Reservada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif

                                          @if($departamento->Nombre_estado == "Pago por confirmar")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desactivada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Rentada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <a href="" class="btn btn-danger">Terminar</a>
                                          @endif
                                    </div>
                                    </p>
                              </div>
                        </div>
                  </div>
                  @endif
                  @endforeach
            </div>
            </div>
      </div>
</div>
@endif



<!--=============== planta 3 ===============-->
@if($departamentos[0]->totaldepaspiso3 <= "0")
      
@else
<div class="seccion_padre_b">
      <div class="seccion_hijo_p"> 
            <div class="seccion_interno_p">
                  <div class="centrar_texto">
                  <p><p><h5>Planta 3</h5></p>
                  <i id="despliegue_planta_3" class="fa-solid fa-chevron-down" onclick="presentar_planta_3()"></i>
                  <i id="ocultar_planta_3" class="fa-solid fa-chevron-up" onclick="esconder_planta_3()"></i>
                  </p>
                  </div>
            <div class="detalle_planta_3" id="detalle_planta_3">
                  
                  @foreach($departamentos as $departamento)
                  @if($departamento -> Nombre_planta == "Planta 3")
                  <div class="seccion_hijo_t">
                        <div class="centrar_texto">
                        <div>  
                              <p><h5>Nombre Del Departamento</h5>
                              <div class="gris"><h6>{{$departamento->Nombre_depa}}</h6></div>
                              </p>

                              <p><h5>Estatus</h5>
                                    @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                              <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desocupada")
                              <div class="gris"><h6 style="color: mediumseagreen">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Reservada")
                              <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desactivada")
                              <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Rentada")
                              <div class="gris"><h6 style="color: rgb(33, 36, 207)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif

                              @if($departamento->Nombre_estado == "Pago por confirmar")
                              <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
                              </p>
                              <i class="fa-solid fa-chevron-down despliegue_departamento despliegue_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}"></i>
                              <i class="fa-solid fa-chevron-up ocultar_departamento ocultar_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}" style="display: none"></i>
                        </div>

                        </div>
                        <div class="detalle_departamento ocultar{{$departamento->Id_departamento}} "> 
                              <div class="centrar_texto"> 
                                    <p><h5>Precios </h5>
                                    <div class="gris">
                                          <h6>Noche ${{$departamento->Precio_noche}}MXN</h6>
                                          <h6>Semana ${{$departamento->Precio_semana}}MXN</h6>
                                          <h6>Catorcedias ${{$departamento->Precio_catorcedias}}MXN</h6>
                                          <h6>Mes ${{$departamento->Precio_mes}}MXN</h6>
                                          <h6>Deposito De Garantia ${{$departamento->Deposito_garantia_dep}}MXN</h6>
                                          <h6>Monto De Apartado(-2semanas): ${{$departamento->Cobro_anticipo_catorcena_d}}MXN</h6>
                                          <h6>Monto De Apartado(+3semanas): ${{$departamento->Cobro_anticipo_mes_d}}MXN</h6>
                                    </div>
                                    </p>
                                    <p><h5>Notas Del Lugar</h5>
                                          <div class="gris">
                                           <h6>{{$departamento->Nota}}</h6>
                                          </div>
                                    </p>
                                    <p><h5>Capacidad De Personas</h5>
                                    <div class="gris"><h6>{{$departamento->Capacidad_personas}} Personas</h6></div>
                                    </p>
                                    <p><h5>Camas</h5>
                                    <div class="gris">
                                          @if($departamento->Cama_individual != "0")
                              
                                          @else
                                          <h6>Camas Individuales: {{$departamento->Cama_individual}} </h6>
                                          @endif
                                          @if($departamento->Cama_matrimonial < "0")

                                          @else
                                          <h6>Camas Matrimoniales: {{$departamento->Cama_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_individual < "0")

                                          @else
                                          <h6>Literas Individuales: {{$departamento->Litera_individual}} </h6>
                                          @endif
                                          @if($departamento->Litera_matrimonial < "0")

                                          @else
                                          <h6>Literas Matrimoniales: {{$departamento->Litera_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_ind_mat < "0")

                                          @else
                                          <h6>Literas Ind/Mat: {{$departamento->Litera_ind_mat}} </h6>
                                          @endif
                                          @if($departamento->Cama_kingsize < "0")

                                          @else
                                          <h6>Camas Kingsizes: {{$departamento->Cama_kingsize}} </h6>
                                          @endif

                                    </div>
                                    </p>
                                    <p><h5>Baños</h5>
                                    <div class="gris">
                                          @if($departamento->Bano_compartido < "0")

                                          @else
                                          <h6>Baños Compartidos: {{$departamento->Bano_compartido}} </h6>
                                          @endif
                                          @if($departamento->Bano_medio < "0")
                                          
                                          @else
                                          <h6>Medios Baños: {{$departamento->Bano_medio}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo < "0")
                                          
                                          @else
                                          <h6>Baños Completos: {{$departamento->Bano_completo}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo_RL < "0")
                                          
                                          @else
                                          <h6>Baños Completos Con Regadera Y Lavamanos: {{$departamento->Bano_completo_RL}} </h6>
                                          @endif
                                          
                                    </div>
                                    </p>
                                    <p><h5>Acciones</h5>
                                    <div class="gris">
                                          @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desocupada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <button class="btn btn-primary clickForm" href="{{route('viewreservadepoc', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}">Reservar</button>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Reservada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif

                                          @if($departamento->Nombre_estado == "Pago por confirmar")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desactivada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Rentada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <a href="" class="btn btn-danger">Terminar</a>
                                          @endif
                                    </div>
                                    </p>
                              </div>
                        </div>
                  </div>
                  @endif
                  @endforeach
            </div>
            </div>
      </div>
</div>
@endif




<!--=============== planta 4 ===============-->
@if($departamentos[0]->totaldepaspiso4 <= "0")
      
@else
<div class="seccion_padre_b">
      <div class="seccion_hijo_p"> 
            <div class="seccion_interno_p">
                  <div class="centrar_texto">
                  <p><p><h5>Planta 4</h5></p>
                  <i id="despliegue_planta_4" class="fa-solid fa-chevron-down" onclick="presentar_planta_4()"></i>
                  <i id="ocultar_planta_4" class="fa-solid fa-chevron-up" onclick="esconder_planta_4()"></i>
                  </p>
                  </div>
            <div class="detalle_planta_4" id="detalle_planta_4">
                  
                  @foreach($departamentos as $departamento)
                  @if($departamento -> Nombre_planta == "Planta 4")
                  <div class="seccion_hijo_t">
                        <div class="centrar_texto">
                        <div>  
                              <p><h5>Nombre Del Departamento</h5>
                              <div class="gris"><h6>{{$departamento->Nombre_depa}}</h6></div>
                              </p>

                              <p><h5>Estatus</h5>
                                    @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                              <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desocupada")
                              <div class="gris"><h6 style="color: mediumseagreen">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Reservada")
                              <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desactivada")
                              <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Rentada")
                              <div class="gris"><h6 style="color: rgb(33, 36, 207)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif

                              @if($departamento->Nombre_estado == "Pago por confirmar")
                              <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
                              </p>
                              <i class="fa-solid fa-chevron-down despliegue_departamento despliegue_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}"></i>
                              <i class="fa-solid fa-chevron-up ocultar_departamento ocultar_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}" style="display: none"></i>
                        </div>

                        </div>
                        <div class="detalle_departamento ocultar{{$departamento->Id_departamento}} "> 
                              <div class="centrar_texto"> 
                                    <p><h5>Precios </h5>
                                    <div class="gris">
                                          <h6>Noche ${{$departamento->Precio_noche}}MXN</h6>
                                          <h6>Semana ${{$departamento->Precio_semana}}MXN</h6>
                                          <h6>Catorcedias ${{$departamento->Precio_catorcedias}}MXN</h6>
                                          <h6>Mes ${{$departamento->Precio_mes}}MXN</h6>
                                          <h6>Deposito De Garantia ${{$departamento->Deposito_garantia_dep}}MXN</h6>
                                          <h6>Monto De Apartado(-2semanas): ${{$departamento->Cobro_anticipo_catorcena_d}}MXN</h6>
                                          <h6>Monto De Apartado(+3semanas): ${{$departamento->Cobro_anticipo_mes_d}}MXN</h6>
                                    </div>
                                    </p>
                                    <p><h5>Notas Del Lugar</h5>
                                          <div class="gris">
                                           <h6>{{$departamento->Nota}}</h6>
                                          </div>
                                    </p>
                                    <p><h5>Capacidad De Personas</h5>
                                    <div class="gris"><h6>{{$departamento->Capacidad_personas}} Personas</h6></div>
                                    </p>
                                    <p><h5>Camas</h5>
                                    <div class="gris">
                                          @if($departamento->Cama_individual != "0")
                              
                                          @else
                                          <h6>Camas Individuales: {{$departamento->Cama_individual}} </h6>
                                          @endif
                                          @if($departamento->Cama_matrimonial < "0")

                                          @else
                                          <h6>Camas Matrimoniales: {{$departamento->Cama_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_individual < "0")

                                          @else
                                          <h6>Literas Individuales: {{$departamento->Litera_individual}} </h6>
                                          @endif
                                          @if($departamento->Litera_matrimonial < "0")

                                          @else
                                          <h6>Literas Matrimoniales: {{$departamento->Litera_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_ind_mat < "0")

                                          @else
                                          <h6>Literas Ind/Mat: {{$departamento->Litera_ind_mat}} </h6>
                                          @endif
                                          @if($departamento->Cama_kingsize < "0")

                                          @else
                                          <h6>Camas Kingsizes: {{$departamento->Cama_kingsize}} </h6>
                                          @endif

                                    </div>
                                    </p>
                                    <p><h5>Baños</h5>
                                    <div class="gris">
                                          @if($departamento->Bano_compartido < "0")

                                          @else
                                          <h6>Baños Compartidos: {{$departamento->Bano_compartido}} </h6>
                                          @endif
                                          @if($departamento->Bano_medio < "0")
                                          
                                          @else
                                          <h6>Medios Baños: {{$departamento->Bano_medio}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo < "0")
                                          
                                          @else
                                          <h6>Baños Completos: {{$departamento->Bano_completo}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo_RL < "0")
                                          
                                          @else
                                          <h6>Baños Completos Con Regadera Y Lavamanos: {{$departamento->Bano_completo_RL}} </h6>
                                          @endif
                                          
                                    </div>
                                    </p>
                                    <p><h5>Acciones</h5>
                                    <div class="gris">
                                          @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desocupada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <button class="btn btn-primary clickForm" href="{{route('viewreservadepoc', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}">Reservar</button>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Reservada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif

                                          @if($departamento->Nombre_estado == "Pago por confirmar")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desactivada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Rentada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <a href="" class="btn btn-danger">Terminar</a>
                                          @endif
                                    </div>
                                    </p>
                              </div>
                        </div>
                  </div>
                  @endif
                  @endforeach
            </div>
            </div>
      </div>
</div>
@endif



<!--=============== planta 5 ===============-->
@if($departamentos[0]->totaldepaspiso5 <= "0")
      
@else
<div class="seccion_padre_b">
      <div class="seccion_hijo_p"> 
            <div class="seccion_interno_p">
                  <div class="centrar_texto">
                  <p><p><h5>Planta 5</h5></p>
                  <i id="despliegue_planta_5" class="fa-solid fa-chevron-down" onclick="presentar_planta_5()"></i>
                  <i id="ocultar_planta_5" class="fa-solid fa-chevron-up" onclick="esconder_planta_5()"></i>
                  </p>
                  </div>
            <div class="detalle_planta_5" id="detalle_planta_5">
                  
                  @foreach($departamentos as $departamento)
                  @if($departamento -> Nombre_planta == "Planta 5")
                  <div class="seccion_hijo_t">
                        <div class="centrar_texto">
                        <div>  
                              <p><h5>Nombre Del Departamento</h5>
                              <div class="gris"><h6>{{$departamento->Nombre_depa}}</h6></div>
                              </p>

                              <p><h5>Estatus</h5>
                                    @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                              <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desocupada")
                              <div class="gris"><h6 style="color: mediumseagreen">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Reservada")
                              <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desactivada")
                              <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Rentada")
                              <div class="gris"><h6 style="color: rgb(33, 36, 207)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif

                              @if($departamento->Nombre_estado == "Pago por confirmar")
                              <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
                              </p>
                              <i class="fa-solid fa-chevron-down despliegue_departamento despliegue_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}"></i>
                              <i class="fa-solid fa-chevron-up ocultar_departamento ocultar_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}" style="display: none"></i>
                        </div>

                        </div>
                        <div class="detalle_departamento ocultar{{$departamento->Id_departamento}} "> 
                              <div class="centrar_texto"> 
                                    <p><h5>Precios </h5>
                                    <div class="gris">
                                          <h6>Noche ${{$departamento->Precio_noche}}MXN</h6>
                                          <h6>Semana ${{$departamento->Precio_semana}}MXN</h6>
                                          <h6>Catorcedias ${{$departamento->Precio_catorcedias}}MXN</h6>
                                          <h6>Mes ${{$departamento->Precio_mes}}MXN</h6>
                                          <h6>Deposito De Garantia ${{$departamento->Deposito_garantia_dep}}MXN</h6>
                                          <h6>Monto De Apartado(-2semanas): ${{$departamento->Cobro_anticipo_catorcena_d}}MXN</h6>
                                          <h6>Monto De Apartado(+3semanas): ${{$departamento->Cobro_anticipo_mes_d}}MXN</h6>
                                    </div>
                                    </p>
                                    <p><h5>Notas Del Lugar</h5>
                                          <div class="gris">
                                           <h6>{{$departamento->Nota}}</h6>
                                          </div>
                                    </p>
                                    <p><h5>Capacidad De Personas</h5>
                                    <div class="gris"><h6>{{$departamento->Capacidad_personas}} Personas</h6></div>
                                    </p>
                                    <p><h5>Camas</h5>
                                    <div class="gris">
                                          @if($departamento->Cama_individual != "0")
                              
                                          @else
                                          <h6>Camas Individuales: {{$departamento->Cama_individual}} </h6>
                                          @endif
                                          @if($departamento->Cama_matrimonial < "0")

                                          @else
                                          <h6>Camas Matrimoniales: {{$departamento->Cama_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_individual < "0")

                                          @else
                                          <h6>Literas Individuales: {{$departamento->Litera_individual}} </h6>
                                          @endif
                                          @if($departamento->Litera_matrimonial < "0")

                                          @else
                                          <h6>Literas Matrimoniales: {{$departamento->Litera_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_ind_mat < "0")

                                          @else
                                          <h6>Literas Ind/Mat: {{$departamento->Litera_ind_mat}} </h6>
                                          @endif
                                          @if($departamento->Cama_kingsize < "0")

                                          @else
                                          <h6>Camas Kingsizes: {{$departamento->Cama_kingsize}} </h6>
                                          @endif

                                    </div>
                                    </p>
                                    <p><h5>Baños</h5>
                                    <div class="gris">
                                          @if($departamento->Bano_compartido < "0")

                                          @else
                                          <h6>Baños Compartidos: {{$departamento->Bano_compartido}} </h6>
                                          @endif
                                          @if($departamento->Bano_medio < "0")
                                          
                                          @else
                                          <h6>Medios Baños: {{$departamento->Bano_medio}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo < "0")
                                          
                                          @else
                                          <h6>Baños Completos: {{$departamento->Bano_completo}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo_RL < "0")
                                          
                                          @else
                                          <h6>Baños Completos Con Regadera Y Lavamanos: {{$departamento->Bano_completo_RL}} </h6>
                                          @endif
                                          
                                    </div>
                                    </p>
                                    <p><h5>Acciones</h5>
                                    <div class="gris">
                                          @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desocupada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <button class="btn btn-primary clickForm" href="{{route('viewreservadepoc', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}">Reservar</button>
                                          @endif

                                          @if($departamento->Nombre_estado == "Pago por confirmar")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Reservada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desactivada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Rentada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <a href="" class="btn btn-danger">Terminar</a>
                                          @endif
                                    </div>
                                    </p>
                              </div>
                        </div>
                  </div>
                  @endif
                  @endforeach
            </div>
            </div>
      </div>
</div>
@endif



<!--=============== planta 6 ===============-->
@if($departamentos[0]->totaldepaspiso6 <= "0")
      
@else
<div class="seccion_padre_b">
      <div class="seccion_hijo_p"> 
            <div class="seccion_interno_p">
                  <div class="centrar_texto">
                  <p><p><h5>Planta 6</h5></p>
                  <i id="despliegue_planta_6" class="fa-solid fa-chevron-down" onclick="presentar_planta_6()"></i>
                  <i id="ocultar_planta_6" class="fa-solid fa-chevron-up" onclick="esconder_planta_6()"></i>
                  </p>
                  </div>
            <div class="detalle_planta_6" id="detalle_planta_6">
                  
                  @foreach($departamentos as $departamento)
                  @if($departamento -> Nombre_planta == "Planta 6")
                  <div class="seccion_hijo_t">
                        <div class="centrar_texto">
                        <div>  
                              <p><h5>Nombre Del Departamento</h5>
                              <div class="gris"><h6>{{$departamento->Nombre_depa}}</h6></div>
                              </p>

                              <p><h5>Estatus</h5>
                                    @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                              <div class="gris"><h6 style="color:  rgb(179, 60, 60)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desocupada")
                              <div class="gris"><h6 style="color: mediumseagreen">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Reservada")
                              <div class="gris"><h6 style="color: rgb(0, 140, 210)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Desactivada")
                              <div class="gris"><h6 style="color: rgb(207, 33, 204)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
      
                              @if($departamento->Nombre_estado == "Rentada")
                              <div class="gris"><h6 style="color: rgb(33, 36, 207)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif

                              @if($departamento->Nombre_estado == "Pago por confirmar")
                              <div class="gris"><h6 style="color: rgb(142, 122, 7)">{{$departamento->Nombre_estado}}</h6></div>
                              @endif
                              </p>
                              <div class="icono_des">
                                    <i class="fa-solid fa-chevron-down despliegue_departamento despliegue_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}"></i>
                              <i class="fa-solid fa-chevron-up ocultar_departamento ocultar_departamentoocultar{{$departamento->Id_departamento}}" relacion="ocultar{{$departamento->Id_departamento}}" style="display: none"></i>
                              </div>
                        </div>

                        </div>
                        <div class="detalle_departamento ocultar{{$departamento->Id_departamento}} "> 
                              <div class="centrar_texto"> 
                                    <p><h5>Precios </h5>
                                    <div class="gris">
                                          <h6>Noche ${{$departamento->Precio_noche}}MXN</h6>
                                          <h6>Semana ${{$departamento->Precio_semana}}MXN</h6>
                                          <h6>Catorcedias ${{$departamento->Precio_catorcedias}}MXN</h6>
                                          <h6>Mes ${{$departamento->Precio_mes}}MXN</h6>
                                          <h6>Deposito De Garantia ${{$departamento->Deposito_garantia_dep}}MXN</h6>
                                          <h6>Monto De Apartado(-2semanas): ${{$departamento->Cobro_anticipo_catorcena_d}}MXN</h6>
                                          <h6>Monto De Apartado(+3semanas): ${{$departamento->Cobro_anticipo_mes_d}}MXN</h6>
                                    </div>
                                    </p>
                                    <p><h5>Notas Del Lugar</h5>
                                          <div class="gris">
                                           <h6>{{$departamento->Nota}}</h6>
                                          </div>
                                    </p>
                                    <p><h5>Capacidad De Personas</h5>
                                    <div class="gris"><h6>{{$departamento->Capacidad_personas}} Personas</h6></div>
                                    </p>
                                    <p><h5>Camas</h5>
                                    <div class="gris">
                                          @if($departamento->Cama_individual != "0")
                              
                                          @else
                                          <h6>Camas Individuales: {{$departamento->Cama_individual}} </h6>
                                          @endif
                                          @if($departamento->Cama_matrimonial < "0")

                                          @else
                                          <h6>Camas Matrimoniales: {{$departamento->Cama_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_individual < "0")

                                          @else
                                          <h6>Literas Individuales: {{$departamento->Litera_individual}} </h6>
                                          @endif
                                          @if($departamento->Litera_matrimonial < "0")

                                          @else
                                          <h6>Literas Matrimoniales: {{$departamento->Litera_matrimonial}} </h6>
                                          @endif
                                          @if($departamento->Litera_ind_mat < "0")

                                          @else
                                          <h6>Literas Ind/Mat: {{$departamento->Litera_ind_mat}} </h6>
                                          @endif
                                          @if($departamento->Cama_kingsize < "0")

                                          @else
                                          <h6>Camas Kingsizes: {{$departamento->Cama_kingsize}} </h6>
                                          @endif

                                    </div>
                                    </p>
                                    <p><h5>Baños</h5>
                                    <div class="gris">
                                          @if($departamento->Bano_compartido < "0")

                                          @else
                                          <h6>Baños Compartidos: {{$departamento->Bano_compartido}} </h6>
                                          @endif
                                          @if($departamento->Bano_medio < "0")
                                          
                                          @else
                                          <h6>Medios Baños: {{$departamento->Bano_medio}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo < "0")
                                          
                                          @else
                                          <h6>Baños Completos: {{$departamento->Bano_completo}} </h6>
                                          @endif
                                          @if($departamento->Bano_completo_RL < "0")
                                          
                                          @else
                                          <h6>Baños Completos Con Regadera Y Lavamanos: {{$departamento->Bano_completo_RL}} </h6>
                                          @endif
                                          
                                    </div>
                                    </p>
                                    <p><h5>Acciones</h5>
                                    <div class="gris">
                                          @if($departamento->Nombre_estado == "En Mantenimiento/Limpieza")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desocupada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <button class="btn btn-primary clickForm" href="{{route('viewreservadepoc', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}">Reservar</button>
                                          @endif

                                          @if($departamento->Nombre_estado == "Pago por confirmar")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Reservada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Desactivada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          @endif
                  
                                          @if($departamento->Nombre_estado == "Rentada")
                                          <a href="{{route('detallesdepalibre', [$locacion[0]->Id_locacion, $departamento->Id_departamento ])}}" class="btn btn-info"><i class="ri-information-line"></i></a>
                                          <a href="" class="btn btn-danger">Terminar</a>
                                          @endif
                                    </div>
                                    </p>
                              </div>
                        </div>
                  </div>
                  @endif
                  @endforeach
            </div>
            </div>
      </div>
</div>
@endif

@endif
<br>


<!-- Modal para agregar hab -->
<div class="modal fade" id="agregar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="margin-left:12px;">
<div class="modal-dialog modal-lg">
  <div class="modal-content tamaño_lg">
    <div class="modal-header">
      <h2 class="modal-title fs-5" id="staticBackdropLabel">Agregar departamento</h2>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    
    <div class="modal-body mb-0 p-0">
      <div class="embed-responsive z-depth-1-half">
          <iframe class="embed-responsive-item" src="<?= route('viewdepa', $locacion[0]->Id_locacion) ?>" allowfullscreen style="height: 100%; width: 100%;"></iframe>
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




<!--=============== scripts ===============-->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ url('js/departamento.js')}}"></script>


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
  
//codigo de jquery que muestra y oculta las secciones de los depas
              $('.despliegue_departamento').on('click', function (index) {
                  console.log(this);
                  let relacion = $(this).attr("relacion");
                  $(this).hide();
                  $('.ocultar_departamento'+relacion).show();
                  $("."+relacion).show();

              });

              $('.ocultar_departamento').on('click', function (index) {
                  console.log(this);
                  let relacion = $(this).attr("relacion");
                  $(this).hide();
                  $('.despliegue_departamento'+relacion).show();
                  $("."+relacion).hide();
              });

</script>

@endsection

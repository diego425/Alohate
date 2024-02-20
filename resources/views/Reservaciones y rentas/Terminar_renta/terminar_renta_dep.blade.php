<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>terminar renta</title>

<!--=============== CSS local===============-->
<link rel="stylesheet" href="{{ asset('assets/terminar_renta.css') }}" >
<!--=============== CSS web ===============-->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">      
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
<!--=============== ESTILOS ===============-->
</head>

<body>
   <!-- libreria para usar las alertas-->
@include('sweetalert::alert')
     <!--=============== DESCRIPCION===============-->
 <div class="seccion_padre_b">
          <div class="seccion_interno_1">
             <div class="centrar_texto">
                <p><h3>Terminar Estancia</h3>
                </p>
             </div>
          </div>
 </div>

 <br>

 <div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
            <div class="centrar_texto">
               <p><h5>Nombre Del Departamento:</h5>
              <div class="gris">
                <h6>{{$datos_cobro[0]->Nombre_depa}}</h6>
              </div>
            </div>

            <div class="centrar_texto">
               <p><h5>Locacion A La Que Pertenece:
               </h5>
              <div class="gris">
               <h6>{{$datos_cobro[0]->Nombre_locacion}}</h6>
              </div>
            </div>
       
         </div>
   </div>
</div>
<p></p>
<!--=============== datos del cliente===============-->
<div class="interno_padre_C">
   <div class="interno_hijo_C">
         <div class="interno_C">
            <div class="centrar_texto">
               <div>
                  <p><h5>Datos De Los Clientes</h5>
                  </p>
               </div>
            </div>
          <div class="container_tabla_C">
              <table class="table table-striped table-hover">
                     <thead>
                           <tr>
                                 <th>NOMBRE</th>
                                 <th>NUM. CELULAR</th>
                                 <th>EMAIL</th>
                           </tr>
                     </thead>
                     <tbody>
                       @foreach($datos_cobro as $lugar_cliente)
                           <tr>
                                 <td data-label="NOMBRE">{{$lugar_cliente->Nombre}} {{$lugar_cliente->Apellido_paterno}} {{$lugar_cliente->Apellido_materno}}</td>
                                 <td data-label="NUM. CELULAR">{{$lugar_cliente->Numero_celular}}</td>
                                 <td data-label="EMAIL">{{$lugar_cliente->Email}}</td>
                           </tr>
                       @endforeach     
                     </tbody>
                   </table>
               </div>
      </div>
   </div>
</div>
<p></p>

 <!--=============== DATOS DE ALOJAMIENTO ===============-->
 <div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
            <div class="centrar_texto">
              <div>
                 <p><h5>Datos De Alojamiento</h5></p>
              </div>
            </div>
           <div class="interno_padre_l">
               
                     <div class="interno_l">
                      <div class="container_tabla">
                          <table class="table table-striped table-hover">
                                 <thead>
                                       <tr>
                                          <th>Fecha De Entrada</th>
                                          <th>Fecha De Salida</th>
                                          <th>Personas Extras</th>
                                          <th>Nombre Del Lugar</th>
                                          <th>Estatus</th>
                                          <th>Tipo De Cobro</th>
                                          <th>No. De Cocheras Que Usara</th>
                                       </tr>
                                 </thead>
                                 <tbody>
                                       <tr>
                                          <td data-label="Fecha De Entrada">{{$datos_cobro[0]->Start_date}}</td>
                                          <td data-label="Fecha De Salida">{{$datos_cobro[0]->End_date}}</td> 
                                          <td data-label="Personas Extras">
                                             @if($datos_cobro[0]->Numero_personas_extras == NULL)
                                                <h6>0</h6>
                                             @else
                                             <i class="fa-solid fa-person"></i> {{$datos_cobro[0]->Numero_personas_extras}}
                                             @endif
                                          </td> 
                                          <td data-label="Nombre Del Lugar">Depa: {{$datos_cobro[0]->Nombre_depa}}</td>
                                          <td data-label="Estatus">
                                                @if($datos_cobro[0]->Nombre_estado == "Ocupada")
                                                <h6 style="color:  rgb(179, 60, 60)">{{$datos_cobro[0]->Nombre_estado}}</h6>
                                                @endif
            
                                                @if($datos_cobro[0]->Nombre_estado == "Desocupada")
                                                <h6 style="color: mediumseagreen">{{$datos_cobro[0]->Nombre_estado}}</h6>
                                                @endif
            
                                                @if($datos_cobro[0]->Nombre_estado == "Reservada")
                                                <h6 style="color: rgb(0, 140, 210)">{{$datos_cobro[0]->Nombre_estado}}</h6>
                                                @endif
            
                                                @if($datos_cobro[0]->Nombre_estado == "Desactivada")
                                                <h6 style="color: rgb(207, 33, 204)">{{$datos_cobro[0]->Nombre_estado}}</h6>
                                                @endif
            
                                                @if($datos_cobro[0]->Nombre_estado == "Rentada")
                                                <h6 style="color: rgb(33, 36, 207)">{{$datos_cobro[0]->Nombre_estado}}</h6>
                                                @endif
            
                                                @if($datos_cobro[0]->Nombre_estado == "Pago por confirmar")
                                                <h6 style="color: rgb(142, 122, 7)">{{$datos_cobro[0]->Nombre_estado}}</h6>
                                                @endif</td>
            
                                          <td data-label="Tipo De Cobro">Por: {{$datos_cobro[0]->Tipo_de_cobro}}</td>
                                          <td data-label="No. De Cocheras Que Usara"><i class="fa-solid fa-car-side"></i>
                                              @if($datos_cobro[0]->Espacios_cochera == "")
                                              0
                                              @else
                                              {{$datos_cobro[0]->Espacios_cochera}}
                                              @endif
                                          </td>
            
                                       </tr>
                                 </tbody>
                          </table>
                      </div>
                     </div>
               
            </div>
   </div>
</div>

<p></p>
{{-- aqui se hara una funcion para que me calcule el monto por el total de desperfectos encontrados --}}
<div class="seccion_padre_b">
   <div class="seccion_hijo_t"> 
         <div class="seccion_interno_1">
     
            <div class="centrar_texto">
               <p><h5>Selecciona los desperfectos que hayas notado en el lugar</h5></p>
            </div>
            <div class="gris">
               <form action="{{route('viewmontodep', [$datos_cobro[0]->Id_reservacion, $datos_cobro[0]->Id_departamento, $datos_cobro[0]->Id_lugares_reservados])}}" method="GET"> 
                  @csrf
               <div class="container_tabla_C">
                  <table class="table table-striped table-hover">
                         <thead>
                               <tr>
                                     <th>Olor a cigarro</th>
                                     <th>Deteccion de plagas</th>
                                     <th>Muebles dañados</th>
                                     <th>Ropa de cama dañada</th>
                                     <th>Limpieza profunda</th>
                                     <th>Vidrios rotos</th>
                                     <th>Otro</th>
                               </tr>
                         </thead>
                         <tbody>
                               <tr>
                                     <td data-label="Olor a cigarro">
                                       <input type="checkbox" name="cigarro" id="cigarro" style="width: 18px; height:18px" onclick="activar_olor()">
                                       <input type="number"   name="monto_olor" id="monto_olor" disabled class="monto" onchange="sumar();">
                                    </td>
                                     <td data-label="Deteccion de plagas">
                                       <input type="checkbox" name="plaga" id="plaga" style="width: 18px; height:18px" onclick="activar_plaga()">
                                       <input type="number"   name="monto_plaga" id="monto_plaga" disabled class="monto" onchange="sumar();">
                                    </td>
                                     <td data-label="Muebles dañados">
                                       <input type="checkbox" name="muebles" id="muebles" style="width: 18px; height:18px" onclick="activar_muebles()">
                                       <input type="number"   name="monto_muebles" id="monto_muebles" disabled class="monto" onchange="sumar();">
                                    </td>
                                     <td data-label="Ropa de cama dañada">
                                       <input type="checkbox" name="ropa" id="ropa" style="width: 18px; height:18px" onclick="activar_ropa()">
                                       <input type="number"   name="monto_ropa" id="monto_ropa" disabled class="monto" onchange="sumar();">
                                    </td>
                                     <td data-label="Limpieza profunda">
                                       <input type="checkbox" name="limpieza" id="limpieza" style="width: 18px; height:18px" onclick="activar_limpieza()">
                                       <input type="number"   name="monto_limpieza" id="monto_limpieza" class="monto" disabled onchange="sumar();">
                                    </td>
                                     <td data-label="Vidrios rotos">
                                       <input type="checkbox" name="vidrio" id="vidrio" style="width: 18px; height:18px" onclick="activar_vidrio()">
                                       <input type="number"   name="monto_vidrio" id="monto_vidrio" class="monto" disabled onchange="sumar();">
                                    </td>
                                     <td data-label="Otro">
                                       <p><label for="">Especifica:</label> <br>
                                          <input type="text" name="titulo_otro" id="titulo_otro" ></p>
                                       <p><label for="">Cobro:</label> <br>
                                          <input type="text" name="monto_otro" id="monto_otro" class="monto" onchange="sumar();"></p>
                                    </td>
                                    
                               </tr>
                         </tbody>
                       </table>
                   </div>
                  </div> 
                  
                  <div class="centrar_texto">
                     <div class="gris">
                        <span>TOTAL: $</span>
                     <input type="number" id="total" value="0" readonly/>
                     </div>
                  </div>

                  <div class="centrar_texto">
                     <p><h6>Deposito De Garantia:</h6></p>
                    <div class="gris">
                     <input type="number" name="" id="" value="{{$datos_cobro[0]->Deposito_garantia_dep}}" readonly >
                    </div>
                  </div>

                  <div class="centrar_texto">
                     <p><h6>Cambio A Regresar:</h6></p>
                    <div class="gris">
                     <input type="number" name="" id="total_resta" value="{{$datos_cobro[0]->Deposito_garantia_dep}}" readonly/>
                    </div>
                  </div>
                  <br>
                  
                  <div class="centrar_texto">
                        <div>
                              <p><h5>Reporte De Mtto</h5></p>
                              <i id="despliegue_mtto" class="fa-solid fa-chevron-down" onclick="presentar_mtto()"></i>
                              <i id="ocultar_mtto" class="fa-solid fa-chevron-up" onclick="esconder_mtto()"></i>
                              </p>
                        </div>
                        <div class="detalle_mtto" id="detalle_mtto">
                           <div class="gris">
                                 <p>
                                    <p><label for="mtto">Tipo De Mtto</label></p>
                                 <select name="mtto" id="mtto" class="form-control">
                                    <option value="-1" disabled selected>Selecciona una opcion</option>
                                    <option value="Correctivo">Correctivo</option>
                                    <option value="Preventivo">Preventivo</option>
                                 </select>
                                 </p>

                                 <p>
                                    <p><label for="descripcion_m">Descripcion Del Trabajo</label></p>
                                    <textarea name="descripcion_m" id="descripcion_m" cols="30" rows="4" class="form-control" placeholder="escribe aqui"></textarea>
                                 </p>
                              <br>
                           </div>
                        </div>
                  </div>

                  <div class="centrar_texto">
                     <button type="submit" class="btn btn-success">Guardar</button>
                  </div>
                  <br>
               </form>
         </div>
   </div>
</div>
<p></p>
 
</body>

<!--=============== scripts ===============-->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ url('js/detalles_habitacion.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

<script>


/* Suma automatica. */
function sumar()
{
  const $total = document.getElementById('total');
  let subtotal = 0;
  const $resta = document.getElementById('total_resta');
  let subtotal_res = 0;

  [ ...document.getElementsByClassName( "monto" ) ].forEach( function ( element ) {
    if(element.value !== '') {
      subtotal += parseFloat(element.value);
    }
  });
  $total.value = subtotal;

  subtotal_res = {{$datos_cobro[0]->Deposito_garantia_dep}} - $total.value;

  $resta.value = subtotal_res;

}

//resta automatica


//funcion que detecta si se estan seleccionando los checkbox
function activar_olor(){
    var activo = document.getElementById("cigarro");
    var desactivoolor = document.getElementById("monto_olor");
    if(activo.checked){
       desactivoolor.removeAttribute("disabled");
    }else{
       desactivoolor.disabled="true";
    }
   }

   function activar_plaga(){
    var activop = document.getElementById("plaga");
    var desactivp = document.getElementById("monto_plaga");
    if(activop.checked){
       desactivp.removeAttribute("disabled");
    }else{
       desactivp.disabled="true";
    }
   }

   function activar_muebles(){
    var activom = document.getElementById("muebles");
    var desactivm = document.getElementById("monto_muebles");
    if(activom.checked){
       desactivm.removeAttribute("disabled");
    }else{
       desactivm.disabled="true";
    }
   }


   function activar_ropa(){
    var activor = document.getElementById("ropa");
    var desactivr = document.getElementById("monto_ropa");
    if(activor.checked){
       desactivr.removeAttribute("disabled");
    }else{
       desactivr.disabled="true";
    }
   }

   function activar_limpieza(){
    var activol = document.getElementById("limpieza");
    var desactivl = document.getElementById("monto_limpieza");
    if(activol.checked){
       desactivl.removeAttribute("disabled");
    }else{
       desactivl.disabled="true";
    }
   }


   function activar_vidrio(){
    var activov = document.getElementById("vidrio");
    var desactivv = document.getElementById("monto_vidrio");
    if(activov.checked){
       desactivv.removeAttribute("disabled");
    }else{
       desactivv.disabled="true";
    }
   }


function esconder_mtto(){
    document.getElementById('ocultar_mtto').style.display = 'none';
    document.getElementById('despliegue_mtto').style.display = 'block';
    document.getElementById('detalle_mtto').style.display = 'none';
}

function presentar_mtto(){
    document.getElementById('ocultar_mtto').style.display = 'block';
    document.getElementById('despliegue_mtto').style.display = 'none';
    document.getElementById('detalle_mtto').style.display = 'block';
}

</script>

</html>
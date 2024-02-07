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
                <label><h2>Registro De Reservacion 1 lugar casa</h2></label>
            </div>
        </div>
        <br><br>
<!-- Progress bar -->
            <div class="progressbar">
                <div class="progress" id="progress"></div>
                <div class="progress-step progress-step-active" data-title="Cliente"></div>
                <div class="progress-step" data-title="Alojamiento"></div>
                <div class="progress-step" data-title="Pago Anticipo"></div>
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
                            <p><label><h4>Primero Verifica Si El Cliente Ya Esta Registrado Con Nosotros.
                                Si No Se Encuentra, Registralo.</h4></label>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Busca Por Numero De Celular:</label>
                        <input type="tel" class="form-control" name="mysearch" id="mysearch" placeholder="#">
                        <ul id="showlist" tabindex="1" class="list-group"></ul>
                    </div>
                    <div class="input-group">
                        <div class="centrar">
                            <label><h4>-Seleccionaste Al Cliente-</h4></label>
                        </div>
                        <div><h4>Nombre:</h4></div>
                            <div><h5><label id="nombrec"></label> <label id="apellidopat" ></label> <label id="apellidomat"></label></h5></div>
                        <div><h4>Celular:</h4></div>
                            <div><h5><label id="celularc"></label></h5></div>
                        
                    </div>

<form action="{{route('storereservacasaoc', $totalcocheras[0]->Id_locacion)}}" method="POST" enctype="multipart/form-data">
@csrf 
<input type="text" style="display: none;" id="idcliente" name="selector_cliente" value="">
                    <div class="centrar">
                        <p><h4>Registra Un Nuevo Cliente</h4></p>
                            <p class="icono_margen">
                            <a href="{{route('viewreservacasanc',$totalcocheras[0]->Id_locacion)}}"><i class="fa-solid fa-plus"></i></a>
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
                    <input type="number" class="form-control"  name="extras" id="extras">
                </div>
                <div class="input-group">
                    <label for="p_total">Numero Total De Personas Que Usaran El Lugar:</label>
                    <input type="number" class="form-control"  name="p_total" id="p_total">
                </div>
                <div class="input-group">
                    <label for="f_entrada">Fecha De Entrada:</label>
                    <input type="date" class="form-control"  name="f_entrada" id="f_entrada">
                </div>
                <div class="input-group">
                    <label for="f_salida">Fecha De Salida:</label>
                    <input type="date" class="form-control"  name="f_salida" id="f_salida">
                </div>
                <div class="input-group">
                    <label for="tipo_renta">¿Como Se Cobrara La Renta?</label>
                    <select name="tipo_renta" id="tipo_renta">
                        <option value="-1" disabled selected>Selecciona una opcion</option>
                        <option value="Noche">Noche ${{$locacion[0]->Precio_noche}}</option>
                        <option value="Semana">Semana ${{$locacion[0]->Precio_semana}}</option>
                        <option value="Catorcena">Catorcena ${{$locacion[0]->Precio_catorcedias}}</option>
                        <option value="Mes">Mes ${{$locacion[0]->Precio_mes}}</option>
                    </select>
                </div>

                <div class="input-group">
                    <br>
                    <p><label>Numero De Cocheras Disponibles: </label></p>
                    <p><label style="color: darkgreen">Espacios Libres: {{$result_resta}}</label></p>
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
                            <label><h3>Registro Del Pago De Anticipo</h3></label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label for="monto_anticipo">Monto Del Anticipo:</label>
                    <input type="number" class="form-control"  name="monto_anticipo" id="monto_anticipo" placeholder="$">
                </div>
                <div class="input-group">
                    <label for="metodo_pago">Metodo De Pago:</label>
                    <select name="metodo_pago" id="metodo_pago">
                        <option value="-1" disabled selected>Selecciona una opcion</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Deposito">Deposito</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="fecha_pago">Fecha Del Pago:</label>
                    <input type="date" class="form-control"  name="fecha_pago" id="fecha_pago">
                </div>
                <div class="input-group">
                    <label>Fotografia Del Comprobante De Pago:</label>
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
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen2" id="nuevaImagen2" class="cuadrito">
                        <br>
                    </div>
                    </div>
                </div>
                <div class="input-group">
                    <label for="nota_pago">Aqui Puedes Dejar Alguna Nota Para Aclaraciones Futuras</label>
                    <textarea name="nota_pago" id="nota_pago" cols="35" rows="6" class="form-control"></textarea>
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
<script>
//funciones js para las fotografias 

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

</script>
</html>




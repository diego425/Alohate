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
                <div class="progress-step" data-title="Documentos"></div>
                <div class="progress-step" data-title="C.Emergencia"></div>
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
                        <label>Busca Por Numero De Celular</label>
                        <input type="search" class="form-control" name="mysearch" id="mysearch" placeholder="#">
                        <ul id="showlist" tabindex="1" class="list-group"></ul>
                    </div>
                    <div class="input-group">
                        <div class="centrar">
                            <label><h4>-Seleccionaste Al Cliente-</h4></label>
                        </div>
                    </div>

<form action="{{route('storerentarhabc2',[$renta[0]->Id_reservacion, $renta[0]->Id_habitacion, $renta[0]->Id_lugares_reservados ])}}" method="POST" enctype="multipart/form-data">
@csrf 

<input type="text" style="display: none;" id="idcliente" name="idcliente" value="">


                <div class="input-group">
                    <label for="nombre_c">Nombre:</label>
                    <input type="text" class="form-control"  name="nombre_c" id="nombre_c" placeholder="nombre" value="">
                </div>
                <div class="input-group">
                    <label for="apellido_pat">Apellido Paterno:</label>
                    <input type="text" class="form-control"  name="apellido_pat" id="apellido_pat" placeholder="apellido" value="">
                </div>
                <div class="input-group">
                    <label for="apellido_mat">Apellido Materno:</label>
                    <input type="text" class="form-control"  name="apellido_mat" id="apellido_mat" placeholder="apellido" value="">
                </div>
                <div class="input-group">
                    <label for="celular">Numero De Celular:</label>
                    <input type="tel" class="form-control"  name="celular_c" id="celular_c" placeholder="#" value="">
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control"  name="email_c" id="email_c" placeholder="@" value="">
                </div>
                <div class="input-group">
                    <label for="estado">Estado De Proc.:</label>
                    <input type="text" class="form-control"  name="estado" id="estado" placeholder="estado" value="">
                </div>
                <div class="input-group">
                    <label for="ciudad">Ciudad De Proc.:</label>
                    <input type="text" class="form-control"  name="ciudad" id="ciudad" placeholder="ciudad" value="">
                </div>
                <div class="input-group">
                    <label for="pais">Pais De Proc.:</label>
                    <input type="text" class="form-control"  name="pais" id="pais" placeholder="pais" value="">
                </div>
                <div class="input-group">
                    <label for="motivo_v">Motivo De La Visita:</label>
                    <input type="text" class="form-control"  name="motivo_v" id="motivo_v" placeholder="" value="">
                </div>
                <div class="input-group">
                    <label for="lugar_v">Coloca El Nombre De La Institucion/Empresa:</label>
                    <input type="text" class="form-control"  name="lugar_v" id="lugar_v" placeholder="" value="">
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

<!-- foto ine-->
                <div class="input-group">
                    <label>Foto De la INE(trasera):</label>
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
                    <input type="text" class="form-control"  name="nombre_p_e1" id="nombre_p_e1" placeholder="nombre y apellidos" value="">
                </div>
                <div class="input-group">
                    <label for="numero_p_e1">Numero De Celular De La Persona 1:</label>
                    <input type="tel" class="form-control"  name="numero_p_e1" id="numero_p_e1" placeholder="#" value="">
                </div>
                <div class="input-group">
                    <label for="parentesco1">Parentesco De La Persona 1:</label>
                    <input type="text" class="form-control"  name="parentesco1" id="parentesco1" placeholder="" value="">
                </div>
                <div class="input-group">
                    <label for="nombre_p_e2">Nombre Completo De La Persona 2:</label>
                    <input type="text" class="form-control"  name="nombre_p_e2" id="nombre_p_e2" placeholder="nombre y apellidos" value="">
                </div>
                <div class="input-group">
                    <label for="numero_p_e2">Numero De Celular De La Persona 2:</label>
                    <input type="tel" class="form-control"  name="numero_p_e2" id="numero_p_e2" placeholder="#" value="">
                </div>
                <div class="input-group">
                    <label for="parentesco2">Parentesco De La Persona 2:</label>
                    <input type="text" class="form-control"  name="parentesco2" id="parentesco2" placeholder="" value="">
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

</script>
</html>




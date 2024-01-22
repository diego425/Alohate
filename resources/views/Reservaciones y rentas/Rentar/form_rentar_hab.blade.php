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

<form action="{{route('storerentarhab',[$renta[0]->Id_reservacion, $renta[0]->Id_habitacion, $renta[0]->Id_lugares_reservados ])}}" method="POST" enctype="multipart/form-data">
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
                        <input id="img2" name="img2" type="file"  onchange="revisarImagen2(this,1)">
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen2" id="nuevaImagen2" class="cuadrito">
                        <br>
                    </div>
                    </div>
                </div>
                <div class="input-group">
                    <label>Fotografia Del Reglamento Firmado</label>
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
                        <input id="img3" name="img3" type="file"  onchange="revisarImagen3(this,1)">
                        <!--input que ayuda a sacar el link de base64 de la img -->
                        <input type="textarea" name="nuevaImagen3" id="nuevaImagen3" class="cuadrito">
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
                        <input type="date" class="form-control"  name="fecha_inicio" id="fecha_inicio" value="{{$renta[0]->Start_date}}" disabled>
                    </div>
                    <div class="input-group">
                        <label for="fecha_termino">Fecha De Termino:</label>
                        <input type="date" class="form-control"  name="fecha_termino" id="fecha_termino" value="{{$renta[0]->End_date}}" disabled>
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
                            <input id="img4" name="img4" type="file"  onchange="revisarImagen4(this,1)">
                            <!--input que ayuda a sacar el link de base64 de la img -->
                            <input type="textarea" name="nuevaImagen4" id="nuevaImagen4" class="cuadrito">
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
                        <label for="nombre_f">Nombre:</label>
                        <input type="text" id="nombre_f" name="nombre_f" placeholder="Nombre">
                    </div>
                    <div class="input-group">
                        <label for="apellido_pat_f">Apellido Paterno:</label>
                        <input type="text" id="apellido_pat_f" name="apellido_pat_f" placeholder="Apellido Paterno">
                    </div>
                    <div class="input-group">
                        <label for="apellido_mat_f">Apellido Materno:</label>
                        <input type="text" id="apellido_mat_f" name="apellido_mat_f" placeholder="Apellido Materno">
                    </div>
                    <div class="input-group">
                        <label for="no_ext_casa">Numero Ext. De La Casa Del Fiador:</label>
                        <input type="text" id="no_ext_casa" name="no_ext_casa" placeholder="#">
                    </div>
                    <div class="input-group">
                        <label for="calle_f">Calle:</label>
                        <input type="text" id="calle_f" name="calle_f" placeholder="Calle">
                    </div>
                    <div class="input-group">
                        <label for="colonia_f">Colonia:</label>
                        <input type="text" id="colonia_f" name="colonia_f" placeholder="Colonia">
                    </div>
                    <div class="input-group">
                        <label for="estado_f">Estado:</label>
                        <input type="text" id="estado_f" name="estado_f" placeholder="Estado">
                    </div>
                    <div class="input-group">
                        <label for="num_telefono_f">Numero De Celular:</label>
                        <input type="text" id="num_telefono_f" name="num_telefono_f" placeholder="#">
                    </div>

                    <div class="input-group">
                        <label>Fotografia Frontal De La INE</label>
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
                            <input id="img5" name="img5" type="file"  onchange="revisarImagen5(this,1)">
                            <!--input que ayuda a sacar el link de base64 de la img -->
                            <input type="textarea" name="nuevaImagen5" id="nuevaImagen5" class="cuadrito">
                            <br>
                        </div>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Fotografia Trasera De La INE</label>
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
                            <input id="img6" name="img6" type="file"  onchange="revisarImagen6(this,1)">
                            <!--input que ayuda a sacar el link de base64 de la img -->
                            <input type="textarea" name="nuevaImagen6" id="nuevaImagen6" class="cuadrito">
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
  }}



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
  }}

</script>

</html>





//funcion para mostrar contenido segun el boton seleccionado
function mostrarform1(){
  document.getElementById('formulario_1').style.display="block";
  document.getElementById('formulario_2').style.display="none";
}

function mostrarform2(){
  document.getElementById('formulario_1').style.display="none";
  document.getElementById('formulario_2').style.display="block";
}


//funciones que muestran y esconden los checks de los servicios
function esconder_cocina(){
    document.getElementById('ocultar_cocina').style.display = 'none';
    document.getElementById('despliegue_cocina').style.display = 'block';
    document.getElementById('detalle_cocina').style.display = 'none';
}

function presentar_cocina(){
    document.getElementById('ocultar_cocina').style.display = 'block';
    document.getElementById('despliegue_cocina').style.display = 'none';
    document.getElementById('detalle_cocina').style.display = 'block';
}


function esconder_lavanderia(){
    document.getElementById('ocultar_lavanderia').style.display = 'none';
    document.getElementById('despliegue_lavanderia').style.display = 'block';
    document.getElementById('detalle_lavanderia').style.display = 'none';
}

function presentar_lavanderia(){
    document.getElementById('ocultar_lavanderia').style.display = 'block';
    document.getElementById('despliegue_lavanderia').style.display = 'none';
    document.getElementById('detalle_lavanderia').style.display = 'block';
}


function esconder_otro_s(){
    document.getElementById('ocultar_otro_s').style.display = 'none';
    document.getElementById('despliegue_otro_s').style.display = 'block';
    document.getElementById('detalle_otro_s').style.display = 'none';
}

function presentar_otro_s(){
    document.getElementById('ocultar_otro_s').style.display = 'block';
    document.getElementById('despliegue_otro_s').style.display = 'none';
    document.getElementById('detalle_otro_s').style.display = 'block';
}

function esconder_estacionamiento(){
    document.getElementById('ocultar_estacionamiento').style.display = 'none';
    document.getElementById('despliegue_estacionamiento').style.display = 'block';
    document.getElementById('detalle_estacionamiento').style.display = 'none';
}

function presentar_estacionamiento(){
    document.getElementById('ocultar_estacionamiento').style.display = 'block';
    document.getElementById('despliegue_estacionamiento').style.display = 'none';
    document.getElementById('detalle_estacionamiento').style.display = 'block';
}

function esconder_sinservicio(){
  document.getElementById('ocultar_sinservicio').style.display = 'none';
  document.getElementById('despliegue_sinservicio').style.display = 'block';
  document.getElementById('detalle_sinservicio').style.display = 'none';
}

function presentar_sinservicio(){
  document.getElementById('ocultar_sinservicio').style.display = 'block';
  document.getElementById('despliegue_sinservicio').style.display = 'none';
  document.getElementById('detalle_sinservicio').style.display = 'block';
}


//funcion que activa y desactiva el input para el numero de cocheras disponibles
function activar_cochera(){
 var activo = document.getElementById("Cochera");
 var desactivo = document.getElementById("num_cochera");
 if(activo.checked){
    desactivo.removeAttribute("disabled");
 }else{
    desactivo.disabled="true";
 }
}




// //funcion para mostrar una previsualizacion de imagenes
// const wrapper = document.querySelector(".wrapper");
// const fileName = document.querySelector(".file-name");
// const defaultBtn = document.querySelector("#img1");
// const customBtn = document.querySelector("#custom-btn");
// const cancelBtn = document.querySelector("#cancel-btn i");
// const img = document.querySelector("#colocar_img1");
// const esconder = document.querySelector(".content");
// let regExp = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive1(){
//   defaultBtn.click();
// }
// defaultBtn.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img.src = result;
//       wrapper.classList.add("active");
//     }
//     cancelBtn.addEventListener("click", function(){
//       img.src = "";
//       wrapper.classList.remove("active");
//     })
//     reader.readAsDataURL(file);

//     esconder.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp);
//     fileName.textContent = valueStore;
//   }
// });


// //funcion para transformar la imagen 1 a base64 y lo manda al textarea 
// var imagen = [];
    
// function revisarImagen1(input, num){
//     console.log(input.files);
//     var id_preview = input.getAttribute("id") + "_preview";
//     if (input.files && input.files[0]) {
//         var reader = new FileReader();
//         reader.onloadend = function (e) {
//             var id_preview_text = "#"+id_preview;
//             var base64image = e.target.result;                    
//             $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
//             var canvas=document.getElementById("tempCanvas");
//             var ctx=canvas.getContext("2d");
//             var cw=canvas.width;
//             var ch=canvas.height;
//             var maxW=800;
//             var maxH=800;
//             var img = new Image;
//             img.src=this.result;
//             img.onload = function(){
//                 var iw=img.width;
//                 var ih=img.height;
//                 var scale=Math.min((maxW/iw),(maxH/ih));
//                 var iwScaled=iw*scale;
//                 var ihScaled=ih*scale;
//                 canvas.width=iwScaled;
//                 canvas.height=ihScaled;
//                 ctx.drawImage(img,0,0,iwScaled,ihScaled);
//                 base64image = canvas.toDataURL("image/jpeg");                       
//                 $(id_preview_text).attr('src', base64image).width(250).height(157);
//                 imagen[num] = base64image;
//                 $("#tempCanvas").remove();
//                 $('#nuevaImagen1').val(base64image);
//                 console.log($('#nuevaImagen1').val());
//             }
//         };
//         reader.readAsDataURL(input.files[0]);
//         $('#imagen_preview').show();
//     }
// }

// //imagen2
// const wrapper2 = document.querySelector(".wrapper2");
// const fileName2 = document.querySelector(".file-name2");
// const defaultBtn2 = document.querySelector("#img2");
// const customBtn2 = document.querySelector("#custom-btn2");
// const cancelBtn2 = document.querySelector("#cancel-btn2 i");
// const img2 = document.querySelector("#colocar_img2");
// const esconder2 = document.querySelector(".content2");
// let regExp2 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive2(){
//   defaultBtn2.click();
// }
// defaultBtn2.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img2.src = result;
//       wrapper2.classList.add("active2");
//     }
//     cancelBtn2.addEventListener("click", function(){
//       img2.src = "";
//       wrapper2.classList.remove("active2");
//     })
//     reader.readAsDataURL(file);

//     esconder2.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp2);
//     fileName2.textContent = valueStore;
//   }
// });



// //funcion para transformar la imagen 2 a base64 y lo manda al textarea 

// var imagen = [];
    
// function revisarImagen2(input, num){
//   console.log(input.files);
//   var id_preview = input.getAttribute("id") + "_preview";
//   if (input.files && input.files[0]) {
//       var reader = new FileReader();
//       reader.onloadend = function (e) {
//           var id_preview_text = "#"+id_preview;
//           var base64image = e.target.result;                    
//           $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
//           var canvas=document.getElementById("tempCanvas");
//           var ctx=canvas.getContext("2d");
//           var cw=canvas.width;
//           var ch=canvas.height;
//           var maxW=800;
//           var maxH=800;
//           var img = new Image;
//           img.src=this.result;
//           img.onload = function(){
//               var iw=img.width;
//               var ih=img.height;
//               var scale=Math.min((maxW/iw),(maxH/ih));
//               var iwScaled=iw*scale;
//               var ihScaled=ih*scale;
//               canvas.width=iwScaled;
//               canvas.height=ihScaled;
//               ctx.drawImage(img,0,0,iwScaled,ihScaled);
//               base64image = canvas.toDataURL("image/jpeg");                       
//               $(id_preview_text).attr('src', base64image).width(250).height(157);
//               imagen[num] = base64image;
//               $("#tempCanvas").remove();
//               $('#nuevaImagen2').val(base64image);
//               console.log($('#nuevaImagen2').val());
//           }
//       };
//       reader.readAsDataURL(input.files[0]);
//       $('#imagen_preview').show();
//   }}


// //imagen3
// const wrapper3 = document.querySelector(".wrapper3");
// const fileName3 = document.querySelector(".file-name3");
// const defaultBtn3 = document.querySelector("#img3");
// const customBtn3 = document.querySelector("#custom-btn3");
// const cancelBtn3 = document.querySelector("#cancel-btn3 i");
// const img3 = document.querySelector("#colocar_img3");
// const esconder3 = document.querySelector(".content3");
// let regExp3 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive3(){
//   defaultBtn3.click();
// }
// defaultBtn3.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img3.src = result;
//       wrapper3.classList.add("active3");
//     }
//     cancelBtn3.addEventListener("click", function(){
//       img3.src = "";
//       wrapper3.classList.remove("active3");
//     })
//     reader.readAsDataURL(file);

//     esconder3.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp3);
//     fileName3.textContent = valueStore;
//   }
// });


// //funcion para transformar la imagen 3 a base64 y lo manda al textarea 
// var imagen = [];
    
// function revisarImagen3(input, num){
//     console.log(input.files);
//     var id_preview = input.getAttribute("id") + "_preview";
//     if (input.files && input.files[0]) {
//         var reader = new FileReader();
//         reader.onloadend = function (e) {
//             var id_preview_text = "#"+id_preview;
//             var base64image = e.target.result;                    
//             $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
//             var canvas=document.getElementById("tempCanvas");
//             var ctx=canvas.getContext("2d");
//             var cw=canvas.width;
//             var ch=canvas.height;
//             var maxW=800;
//             var maxH=800;
//             var img = new Image;
//             img.src=this.result;
//             img.onload = function(){
//                 var iw=img.width;
//                 var ih=img.height;
//                 var scale=Math.min((maxW/iw),(maxH/ih));
//                 var iwScaled=iw*scale;
//                 var ihScaled=ih*scale;
//                 canvas.width=iwScaled;
//                 canvas.height=ihScaled;
//                 ctx.drawImage(img,0,0,iwScaled,ihScaled);
//                 base64image = canvas.toDataURL("image/jpeg");                       
//                 $(id_preview_text).attr('src', base64image).width(250).height(157);
//                 imagen[num] = base64image;
//                 $("#tempCanvas").remove();
//                 $('#nuevaImagen3').val(base64image);
//                 console.log($('#nuevaImagen3').val());
//             }
//         };
//         reader.readAsDataURL(input.files[0]);
//         $('#imagen_preview').show();
//     }
// }            

// //imagen4
// const wrapper4 = document.querySelector(".wrapper4");
// const fileName4 = document.querySelector(".file-name4");
// const defaultBtn4 = document.querySelector("#img4");
// const customBtn4 = document.querySelector("#custom-btn4");
// const cancelBtn4 = document.querySelector("#cancel-btn4 i");
// const img4 = document.querySelector("#colocar_img4");
// const esconder4 = document.querySelector(".content4");
// let regExp4 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive4(){
//   defaultBtn4.click();
// }
// defaultBtn4.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img4.src = result;
//       wrapper4.classList.add("active4");
//     }
//     cancelBtn4.addEventListener("click", function(){
//       img4.src = "";
//       wrapper4.classList.remove("active4");
//     })
//     reader.readAsDataURL(file);

//     esconder4.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp4);
//     fileName4.textContent = valueStore;
//   }
// });



// //funcion para transformar la imagen 4 a base64 y lo manda al textarea 
// var imagen = [];
    
// function revisarImagen4(input, num){
//     console.log(input.files);
//     var id_preview = input.getAttribute("id") + "_preview";
//     if (input.files && input.files[0]) {
//         var reader = new FileReader();
//         reader.onloadend = function (e) {
//             var id_preview_text = "#"+id_preview;
//             var base64image = e.target.result;                    
//             $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
//             var canvas=document.getElementById("tempCanvas");
//             var ctx=canvas.getContext("2d");
//             var cw=canvas.width;
//             var ch=canvas.height;
//             var maxW=800;
//             var maxH=800;
//             var img = new Image;
//             img.src=this.result;
//             img.onload = function(){
//                 var iw=img.width;
//                 var ih=img.height;
//                 var scale=Math.min((maxW/iw),(maxH/ih));
//                 var iwScaled=iw*scale;
//                 var ihScaled=ih*scale;
//                 canvas.width=iwScaled;
//                 canvas.height=ihScaled;
//                 ctx.drawImage(img,0,0,iwScaled,ihScaled);
//                 base64image = canvas.toDataURL("image/jpeg");                       
//                 $(id_preview_text).attr('src', base64image).width(250).height(157);
//                 imagen[num] = base64image;
//                 $("#tempCanvas").remove();
//                 $('#nuevaImagen4').val(base64image);
//                 console.log($('#nuevaImagen4').val());
//             }
//         };
//         reader.readAsDataURL(input.files[0]);
//         $('#imagen_preview').show();
//     }
// }


// //imagen5
// const wrapper5 = document.querySelector(".wrapper5");
// const fileName5 = document.querySelector(".file-name5");
// const defaultBtn5 = document.querySelector("#img5");
// const customBtn5 = document.querySelector("#custom-btn5");
// const cancelBtn5 = document.querySelector("#cancel-btn5 i");
// const img5 = document.querySelector("#colocar_img5");
// const esconder5 = document.querySelector(".content5");
// let regExp5 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive5(){
//   defaultBtn5.click();
// }
// defaultBtn5.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img5.src = result;
//       wrapper5.classList.add("active5");
//     }
//     cancelBtn5.addEventListener("click", function(){
//       img5.src = "";
//       wrapper5.classList.remove("active5");
//     })
//     reader.readAsDataURL(file);

//     esconder5.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp5);
//     fileName5.textContent = valueStore;
//   }
// });



// //funcion para transformar la imagen 5 a base64 y lo manda al textarea 
// var imagen = [];
    
// function revisarImagen5(input, num){
//     console.log(input.files);
//     var id_preview = input.getAttribute("id") + "_preview";
//     if (input.files && input.files[0]) {
//         var reader = new FileReader();
//         reader.onloadend = function (e) {
//             var id_preview_text = "#"+id_preview;
//             var base64image = e.target.result;                    
//             $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
//             var canvas=document.getElementById("tempCanvas");
//             var ctx=canvas.getContext("2d");
//             var cw=canvas.width;
//             var ch=canvas.height;
//             var maxW=800;
//             var maxH=800;
//             var img = new Image;
//             img.src=this.result;
//             img.onload = function(){
//                 var iw=img.width;
//                 var ih=img.height;
//                 var scale=Math.min((maxW/iw),(maxH/ih));
//                 var iwScaled=iw*scale;
//                 var ihScaled=ih*scale;
//                 canvas.width=iwScaled;
//                 canvas.height=ihScaled;
//                 ctx.drawImage(img,0,0,iwScaled,ihScaled);
//                 base64image = canvas.toDataURL("image/jpeg");                       
//                 $(id_preview_text).attr('src', base64image).width(250).height(157);
//                 imagen[num] = base64image;
//                 $("#tempCanvas").remove();
//                 $('#nuevaImagen5').val(base64image);
//                 console.log($('#nuevaImagen5').val());
//             }
//         };
//         reader.readAsDataURL(input.files[0]);
//         $('#imagen_preview').show();
//     }
// }


// //imagen6
// const wrapper6 = document.querySelector(".wrapper6");
// const fileName6 = document.querySelector(".file-name6");
// const defaultBtn6 = document.querySelector("#img6");
// const customBtn6 = document.querySelector("#custom-btn6");
// const cancelBtn6 = document.querySelector("#cancel-btn6 i");
// const img6 = document.querySelector("#colocar_img6");
// const esconder6 = document.querySelector(".content6");
// let regExp6 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive6(){
//   defaultBtn6.click();
// }
// defaultBtn6.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img6.src = result;
//       wrapper6.classList.add("active6");
//     }
//     cancelBtn6.addEventListener("click", function(){
//       img6.src = "";
//       wrapper6.classList.remove("active6");
//     })
//     reader.readAsDataURL(file);

//     esconder6.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp6);
//     fileName6.textContent = valueStore;
//   }
// });

// //funcion para transformar la imagen 6 a base64 y lo manda al textarea 
//   var imagen = [];
    
//  function revisarImagen6(input, num){
//     console.log(input.files);
//     var id_preview = input.getAttribute("id") + "_preview";
//     if (input.files && input.files[0]) {
//         var reader = new FileReader();
//         reader.onloadend = function (e) {
//             var id_preview_text = "#"+id_preview;
//             var base64image = e.target.result;                    
//             $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
//             var canvas=document.getElementById("tempCanvas");
//             var ctx=canvas.getContext("2d");
//             var cw=canvas.width;
//             var ch=canvas.height;
//             var maxW=800;
//             var maxH=800;
//             var img = new Image;
//             img.src=this.result;
//             img.onload = function(){
//                 var iw=img.width;
//                 var ih=img.height;
//                 var scale=Math.min((maxW/iw),(maxH/ih));
//                 var iwScaled=iw*scale;
//                 var ihScaled=ih*scale;
//                 canvas.width=iwScaled;
//                 canvas.height=ihScaled;
//                 ctx.drawImage(img,0,0,iwScaled,ihScaled);
//                 base64image = canvas.toDataURL("image/jpeg");                       
//                 $(id_preview_text).attr('src', base64image).width(250).height(157);
//                 imagen[num] = base64image;
//                 $("#tempCanvas").remove();
//                 $('#nuevaImagen6').val(base64image);
//                 console.log($('#nuevaImagen6').val());
//             }
//         };
//         reader.readAsDataURL(input.files[0]);
//         $('#imagen_preview').show();
//     }
// }




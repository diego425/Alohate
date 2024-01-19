
//funcion que activa y desactiva el input para el numero de cocheras disponibles
function activar_cochera(){
    var activo = document.getElementById("cochera");
    var desactivonum = document.getElementById("num_cochera");
    var desactivocan = document.getElementById("uso_cochera");
    if(activo.checked){
       desactivonum.removeAttribute("disabled");
       desactivocan.removeAttribute("disabled");
    }else{
       desactivonum.disabled="true";
       desactivocan.disabled="true";
    }
   }
   
function esconder_cliente_reserva(){
      document.getElementById('ocultar_cliente_reserva').style.display = 'none';
      document.getElementById('despliegue_cliente_reserva').style.display = 'block';
      document.getElementById('detalle_cliente_reserva').style.display = 'none';
}
  
function presentar_cliente_reserva(){
      document.getElementById('ocultar_cliente_reserva').style.display = 'block';
      document.getElementById('despliegue_cliente_reserva').style.display = 'none';
      document.getElementById('detalle_cliente_reserva').style.display = 'block';
}


function esconder_contrato(){
  document.getElementById('ocultar_contrato').style.display = 'none';
  document.getElementById('despliegue_contrato').style.display = 'block';
  document.getElementById('detalle_contrato').style.display = 'none';
}

function presentar_contrato(){
  document.getElementById('ocultar_contrato').style.display = 'block';
  document.getElementById('despliegue_contrato').style.display = 'none';
  document.getElementById('detalle_contrato').style.display = 'block';
}

function esconder_fiador(){
  document.getElementById('ocultar_fiador').style.display = 'none';
  document.getElementById('despliegue_fiador').style.display = 'block';
  document.getElementById('detalle_fiador').style.display = 'none';
}

function presentar_fiador(){
  document.getElementById('ocultar_fiador').style.display = 'block';
  document.getElementById('despliegue_fiador').style.display = 'none';
  document.getElementById('detalle_fiador').style.display = 'block';
}

//toda esta secion esta comentada ya que daba errores cuando mandaba a transformar las imagenes a base64 para el almacenamiento de estas mismas
//la solucion que encontre fue colocar estos codigos directamente en el archivo html con unas etiquetas scripts
//de igual forma deje esto aqui para no hacer tanto movedero y pueda que me sirva en el futuro o/a ti, futuro programador. PD: si encuentras esto CORREEEEEEEEEEEEE busca un mejor lugar para trabajar 
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



// //imagen8
// const wrapper8 = document.querySelector(".wrapper8");
// const fileName8 = document.querySelector(".file-name8");
// const defaultBtn8 = document.querySelector("#img8");
// const customBtn8 = document.querySelector("#custom-btn8");
// const cancelBtn8 = document.querySelector("#cancel-btn8 i");
// const img8 = document.querySelector("#colocar_img8");
// const esconder8 = document.querySelector(".content8");
// let regExp8 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive8(){
//   defaultBtn8.click();
// }
// defaultBtn8.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img8.src = result;
//       wrapper8.classList.add("active8");
//     }
//     cancelBtn8.addEventListener("click", function(){
//       img8.src = "";
//       wrapper8.classList.remove("active8");
//     })
//     reader.readAsDataURL(file);

//     esconder8.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp8);
//     fileName8.textContent = valueStore;
//   }
// });

// //funcion para transformar la imagen 8 a base64 y lo manda al textarea 

// var imagen = [];
    
// function revisarImagen8(input, num){
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
//               $('#nuevaImagen8').val(base64image);
//               console.log($('#nuevaImagen8').val());
//           }
//       };
//       reader.readAsDataURL(input.files[0]);
//       $('#imagen_preview').show();
//   }}



// //imagen9
// const wrapper9 = document.querySelector(".wrapper9");
// const fileName9 = document.querySelector(".file-name9");
// const defaultBtn9 = document.querySelector("#img9");
// const customBtn9 = document.querySelector("#custom-btn9");
// const cancelBtn9 = document.querySelector("#cancel-btn9 i");
// const img9 = document.querySelector("#colocar_img9");
// const esconder9 = document.querySelector(".content9");
// let regExp9 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive9(){
//   defaultBtn9.click();
// }
// defaultBtn9.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img9.src = result;
//       wrapper9.classList.add("active9");
//     }
//     cancelBtn9.addEventListener("click", function(){
//       img9.src = "";
//       wrapper9.classList.remove("active9");
//     })
//     reader.readAsDataURL(file);

//     esconder9.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp9);
//     fileName9.textContent = valueStore;
//   }
// });

// //funcion para transformar la imagen 9 a base64 y lo manda al textarea 

// var imagen = [];
    
// function revisarImagen9(input, num){
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
//               $('#nuevaImagen9').val(base64image);
//               console.log($('#nuevaImagen9').val());
//           }
//       };
//       reader.readAsDataURL(input.files[0]);
//       $('#imagen_preview').show();
//   }}



// //imagen10
// const wrapper10 = document.querySelector(".wrapper10");
// const fileName10 = document.querySelector(".file-name10");
// const defaultBtn10 = document.querySelector("#img10");
// const customBtn10 = document.querySelector("#custom-btn10");
// const cancelBtn10 = document.querySelector("#cancel-btn10 i");
// const img10 = document.querySelector("#colocar_img10");
// const esconder10 = document.querySelector(".content10");
// let regExp10 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive10(){
//   defaultBtn10.click();
// }
// defaultBtn10.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img10.src = result;
//       wrapper10.classList.add("active10");
//     }
//     cancelBtn10.addEventListener("click", function(){
//       img10.src = "";
//       wrapper10.classList.remove("active10");
//     })
//     reader.readAsDataURL(file);

//     esconder10.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp10);
//     fileName10.textContent = valueStore;
//   }
// });

// //funcion para transformar la imagen 10 a base64 y lo manda al textarea 

// var imagen = [];
    
// function revisarImagen10(input, num){
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
//               $('#nuevaImagen10').val(base64image);
//               console.log($('#nuevaImagen10').val());
//           }
//       };
//       reader.readAsDataURL(input.files[0]);
//       $('#imagen_preview').show();
//   }}



// //imagen11
// const wrapper11 = document.querySelector(".wrapper11");
// const fileName11 = document.querySelector(".file-name11");
// const defaultBtn11 = document.querySelector("#img11");
// const customBtn11 = document.querySelector("#custom-btn11");
// const cancelBtn11 = document.querySelector("#cancel-btn11 i");
// const img11 = document.querySelector("#colocar_img11");
// const esconder11 = document.querySelector(".content11");
// let regExp11 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive11(){
//   defaultBtn11.click();
// }
// defaultBtn11.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img11.src = result;
//       wrapper11.classList.add("active11");
//     }
//     cancelBtn11.addEventListener("click", function(){
//       img11.src = "";
//       wrapper11.classList.remove("active11");
//     })
//     reader.readAsDataURL(file);

//     esconder11.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp11);
//     fileName11.textContent = valueStore;
//   }
// });

// //funcion para transformar la imagen 11 a base64 y lo manda al textarea 

// var imagen = [];
    
// function revisarImagen11(input, num){
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
//               $('#nuevaImagen11').val(base64image);
//               console.log($('#nuevaImagen11').val());
//           }
//       };
//       reader.readAsDataURL(input.files[0]);
//       $('#imagen_preview').show();
//   }}





// //imagen12
// const wrapper12 = document.querySelector(".wrapper12");
// const fileName12 = document.querySelector(".file-name12");
// const defaultBtn12 = document.querySelector("#img12");
// const customBtn12 = document.querySelector("#custom-btn12");
// const cancelBtn12 = document.querySelector("#cancel-btn12 i");
// const img12 = document.querySelector("#colocar_img12");
// const esconder12 = document.querySelector(".content12");
// let regExp12 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive12(){
//   defaultBtn12.click();
// }
// defaultBtn12.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img12.src = result;
//       wrapper12.classList.add("active12");
//     }
//     cancelBtn12.addEventListener("click", function(){
//       img12.src = "";
//       wrapper12.classList.remove("active12");
//     })
//     reader.readAsDataURL(file);

//     esconder12.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp12);
//     fileName12.textContent = valueStore;
//   }
// });

// //funcion para transformar la imagen 12 a base64 y lo manda al textarea 

// var imagen = [];
    
// function revisarImagen12(input, num){
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
//               $('#nuevaImagen12').val(base64image);
//               console.log($('#nuevaImagen12').val());
//           }
//       };
//       reader.readAsDataURL(input.files[0]);
//       $('#imagen_preview').show();
//   }}



// //imagen13
// const wrapper13 = document.querySelector(".wrapper13");
// const fileName13 = document.querySelector(".file-name13");
// const defaultBtn13 = document.querySelector("#img13");
// const customBtn13 = document.querySelector("#custom-btn13");
// const cancelBtn13 = document.querySelector("#cancel-btn13 i");
// const img13 = document.querySelector("#colocar_img13");
// const esconder13 = document.querySelector(".content13");
// let regExp13 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive13(){
//   defaultBtn13.click();
// }
// defaultBtn13.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img13.src = result;
//       wrapper13.classList.add("active13");
//     }
//     cancelBtn13.addEventListener("click", function(){
//       img13.src = "";
//       wrapper13.classList.remove("active13");
//     })
//     reader.readAsDataURL(file);

//     esconder13.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp13);
//     fileName13.textContent = valueStore;
//   }
// });


// //funcion para transformar la imagen 13 a base64 y lo manda al textarea 

// var imagen = [];
    
// function revisarImagen13(input, num){
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
//               $('#nuevaImagen13').val(base64image);
//               console.log($('#nuevaImagen13').val());
//           }
//       };
//       reader.readAsDataURL(input.files[0]);
//       $('#imagen_preview').show();
//   }}



// //imagen14
// const wrapper14 = document.querySelector(".wrapper14");
// const fileName14 = document.querySelector(".file-name14");
// const defaultBtn14 = document.querySelector("#img14");
// const customBtn14 = document.querySelector("#custom-btn14");
// const cancelBtn14 = document.querySelector("#cancel-btn14 i");
// const img14 = document.querySelector("#colocar_img14");
// const esconder14 = document.querySelector(".content14");
// let regExp14 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive14(){
//   defaultBtn14.click();
// }
// defaultBtn14.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img14.src = result;
//       wrapper14.classList.add("active14");
//     }
//     cancelBtn14.addEventListener("click", function(){
//       img14.src = "";
//       wrapper14.classList.remove("active14");
//     })
//     reader.readAsDataURL(file);

//     esconder14.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp14);
//     fileName14.textContent = valueStore;
//   }
// });

// //funcion para transformar la imagen 14 a base64 y lo manda al textarea 

// var imagen = [];
    
// function revisarImagen14(input, num){
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
//               $('#nuevaImagen14').val(base64image);
//               console.log($('#nuevaImagen14').val());
//           }
//       };
//       reader.readAsDataURL(input.files[0]);
//       $('#imagen_preview').show();
//   }}



// //imagen15
// const wrapper15 = document.querySelector(".wrapper15");
// const fileName15 = document.querySelector(".file-name15");
// const defaultBtn15 = document.querySelector("#img15");
// const customBtn15 = document.querySelector("#custom-btn15");
// const cancelBtn15 = document.querySelector("#cancel-btn15 i");
// const img15 = document.querySelector("#colocar_img15");
// const esconder15 = document.querySelector(".content15");
// let regExp15 = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
// function defaultBtnActive15(){
//   defaultBtn15.click();
// }
// defaultBtn15.addEventListener("change", function(){
//   const file = this.files[0];
//   if(file){
//     const reader = new FileReader();
//     reader.onload = function(){
//       const result = reader.result;
//       img15.src = result;
//       wrapper15.classList.add("active15");
//     }
//     cancelBtn15.addEventListener("click", function(){
//       img15.src = "";
//       wrapper15.classList.remove("active15");
//     })
//     reader.readAsDataURL(file);

//     esconder15.style.display = 'none';
//   }
//   if(this.value){
//     let valueStore = this.value.match(regExp15);
//     fileName15.textContent = valueStore;
//   }
// });

// //funcion para transformar la imagen 15 a base64 y lo manda al textarea 

// var imagen = [];
    
// function revisarImagen15(input, num){
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
//               $('#nuevaImagen15').val(base64image);
//               console.log($('#nuevaImagen15').val());
//           }
//       };
//       reader.readAsDataURL(input.files[0]);
//       $('#imagen_preview').show();
//   }}


















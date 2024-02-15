<!DOCTYPE html>
<html lang="en">
<head>
 <!--METAS-->
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<!--LINKS-->
<script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/intros_agregar_loc_style.css') }}">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Poppins:ital,wght@1,300&display=swap');
  body{
    background-color: whitesmoke
  }
</style>

</head>
  <body>

    <div class="titulo">
      <p >¡WoW! Ya Casi Terminas</p>
    </div>


    <div class="titulo">
      <p>Ahora Vamos A Registrar Los Locales Que Tiene El Lugar.</p>
    </div> 

    <div class="sub">
    <p>
      Para comenzar se tomara el numero total de locales que pusiste en el registro de la locacion.
    </p>
    <p> 
      Tu deber sera configurar los datos principales,el numero y tipo de baños, servicios con los que cuentan los locales y unas fotografias de cada uno de los locales.
    </p>

  </div>  
      <div class="centrar">
          <a href="{{route('viewlocal', $idlocacion)}}" class="boton_entera"> Continuar</a>
      </div>
         
    <div class="plano">
      <img src="{{asset('images/plano_local.jpg')}}" alt="">
  </body>
  

</html>




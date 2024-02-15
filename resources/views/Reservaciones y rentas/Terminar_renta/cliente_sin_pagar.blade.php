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

  .alto{
    color: crimson;
    font-size: 150%;
  }
</style>

</head>
  <body>

    <div class="titulo">
      <p class="alto">¡ALTO!</p>
    </div>

    <div class="titulo">
      <p>Se detecto que este cliente no ha pagado el monto total de renta. por favor pidele que pague para que pueda irse.</p>
    </div> 
         
    <div class="plano">
      <img src="{{asset('images/sin_paga.jpg')}}" alt="">
  </body>
  

</html>


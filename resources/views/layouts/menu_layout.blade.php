<!DOCTYPE html>
<html lang="es">
<head>
 <!--METAS-->
 <meta charset="UTF-8">
 <meta name="csrf-token" content="{{csrf_token()}}">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <title>@yield('title')</title>
<!--LINKS-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/animations.min.css" integrity="sha512-GKHaATMc7acW6/GDGVyBhKV3rST+5rMjokVip0uTikmZHhdqFWC7fGBaq6+lf+DOS5BIO8eK6NcyBYUBCHUBXA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css" integrity="sha512-cn16Qw8mzTBKpu08X0fwhTSv02kK/FojjNLz0bwp2xJ4H+yalwzXKFw/5cLzuBZCxGWIA+95X4skzvo8STNtSg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('assets/Menu_style.css') }}" >
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
@yield('css')
</head>

  <!--=============== HEADER ===============-->
  <header class="header">
    <div class="interno container">
       <div class="lol__data">
           <a href="{{route('home')}}" class="lol__logo">
               <i class="ri-home-smile-fill"></i> Alohate
           </a>
          
          <div class="lol__toggle" id="lol-toggle">
             <i class="ri-menu-line lol__burger"></i>
             <i class="ri-close-line lol__close"></i>
          </div>
          <a href=""><img src="{{asset('images/bell.png')}}" alt="" id="bell"></a>
       </div>

       <!--=============== NAV MENU ===============-->
       <div class="lol__menu" id="lol-menu">
          <ul class="lol__list">
             <li><a href="{{route('locacion') }}" class="lol__link">Locaciones</a></li>

             <!--=============== DROPDOWN 1 ===============-->
             <li class="dropdown__item">
                <div class="lol__link">
                   Cotizaciones <i class="ri-arrow-down-s-line dropdown__arrow"></i>
                </div>

                <ul class="dropdown__menu">
                   <li>
                      <a href="#" class="dropdown__link">
                         <i class="ri-pie-chart-line"></i> Cotizaciones
                      </a>                          
                   </li>

                   <li>
                      <a href="#" class="dropdown__link">
                         <i class="ri-arrow-up-down-line"></i> Citas
                      </a>
                   </li>
                </ul>
             </li>
             
             <li><a href="{{route('reservaciones_renta')}}" class="lol__link">Reservaciones y Rentas</a></li>
             <li><a href="{{route('pagos.index')}}" class="lol__link">Cobros y Adeudos de Renta</a></li>
             <li><a href="#" class="lol__link">Clientes</a></li>

             <!--=============== DROPDOWN 2 ===============-->
             <li class="dropdown__item">
                <div class="lol__link">
                   Servicios <i class="ri-arrow-down-s-line dropdown__arrow"></i>
                </div>

                <ul class="dropdown__menu">
                   <li>
                      <a href="#" class="dropdown__link">
                         <i class="ri-lock-line"></i> Servicios
                      </a>
                   </li>

                   <li>
                      <a href="#" class="dropdown__link">
                         <i class="ri-message-3-line"></i> Gastos Esporadicos
                      </a>
                   </li>
                </ul>
             </li>

             <li><a href="{{route('limpieza.index')}}" class="lol__link">Reportes de Limp y MTTO</a></li>
             @if(!empty(Cookie::get('puesto')))
               @if(Cookie::get('puesto') == "ADMIN" || Cookie::get('puesto') == "OPERADOR")               
                  <li><a href="{{route('user.index')}}" class="lol__link">Gestion de colaboradores</a></li>
               @endif
             @endif
             <li><a href="#" class="lol__link">Checador de entrada/salida</a></li>
             <li><a href="#" class="lol__link">Listas de asistencias</a></li>
             <li><a href="#" class="lol__link">Historial LOG</a></li>
             <li><a href="#" class="lol__link">Reportes</a></li>
             <li>
               <form action="{{route('login.cerrarSesion')}}" method="post">
                  @csrf
                  <button type="submit" class="lol__link" title="Cerrar sesión">
                     <i class='bx bx-log-out-circle'></i>
                     Cerrar sesión
                  </button>
               </form>
            </li>
          </ul>
       </div>
      </div>
 </header>
<body>
   @if (session()->has('message'))
       <div class="alert alert-success alert-dismissible fade show d-flex" role="alert" style="width: 80%; margin-left: 10%;">
           <i class='bx bx-message-check bx-tada' ></i>
           <div>
               {{ session()->get('message') }}
           </div>
           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
       </div>
   @else
       @if (session()->has('error'))
         <div class="alert alert-danger alert-dismissible fade show d-flex" role="alert" style="width: 80%; margin-left: 10%;">
             <i class='bx bxs-error-alt bx-flashing' ></i>
             <div>
                 {{ session()->get('error') }}
             </div>
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>
       @endif
   @endif

   @if ($errors->any())
       <div class="alert alert-danger alert-dismissible fade show d-flex" role="alert" style="width: 80%; margin-left: 10%;">
         <i class='bx bxs-error-alt bx-flashing' ></i>
           <ul>
               @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
               @endforeach
           </ul>
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
       </div>
   @endif
   @yield('MenuPrincipal')
</body>
<script type="text/javascript" src="{{ url('js/Menu_main.js')}}"></script>
<!--scripts-->
<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/dist/boxicons.js" integrity="sha512-kWH92pHUC/rcjpSMu19lT+H6TlZwZCAftg9AuSw+AVYSdEKSlXXB8o6g12mg5f+Pj5xO40A7ju2ot/VdodCthw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@yield('js')
<script>
   $(document).ready(function () {
      @if(!empty(Cookie::get('puesto')))
      @else
         window.location.href = "{{route('login')}}";
      @endif
   });
</script>
</html>
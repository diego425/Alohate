<!DOCTYPE html>
<html lang="en">

<head>
  <!--METAS-->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LOGIN</title>
  <!--LINKS-->
  <link rel="stylesheet" href="{{ asset('assets/Login_style.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <!--scripts-->
  <script src="https://kit.fontawesome.com/110428e727.js" crossorigin="anonymous"></script>
</head>

<body>
  @error('invalid_credentials')
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <small>
      {{$message}}
    </small>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @enderror
  <div class="wrapper">
    <section class="vh-100 gradient-custom">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card bg-dark text-white" style="border-radius: 1rem;">
              <div class="card-body p-5 text-center">
                <div class="mb-md-5 mt-md-4 pb-5">
                  @if (session()->has('message'))
                  <div class="alert alert-success alert-dismissible fade show d-flex" role="alert" style="width: 80%; margin-left: 10%;">
                    <i class='bx bx-message-check bx-tada'></i>
                    <div>
                      {{ session()->get('message') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  @else
                  @if (session()->has('error'))
                  <div class="alert alert-danger alert-dismissible fade show d-flex" role="alert" style="width: 80%; margin-left: 10%;">
                    <i class='bx bxs-error-alt bx-flashing'></i>
                    <div>
                      {{ session()->get('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  @endif
                  @endif
                  <h2 class="fw-bold mb-2 text-uppercase"></h2>
                  <p class="text-white-50 mb-5">Enviamos un código de un solo uso al correo del administrador</p>
                  <form action="{{route('login.verificarCodigo')}}" method="post" id="formulario">
                    @csrf
                    <input type="hidden" name="user" value="{{$user}}">
                    <div class="form-outline form-white mb-4">
                      <label class="form-label" for="codigo">Codigo</label>
                      <input type="number" id="codigo" name="codigo" minlength="0" maxlength="7" class="form-control form-control-lg @error('codigo') is-invalid @enderror" placeholder="Ingrese el código">
                      @error('codigo')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <button class="btn btn-outline-light btn-lg px-5" type="submit" id="submit">Verificar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

</html>
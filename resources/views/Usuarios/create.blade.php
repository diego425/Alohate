@extends('layouts.menu_layout')
@section('title', 'Crear usuario')
@section('MenuPrincipal')
<div class="container">
    <div class="card">
        <div class="d-grid gap-2 d-md-block">
            <a class="btn btn-danger" style="position: fixed;" type="button" href="{{route('user.index')}}">
                <i class='bx bx-arrow-back'></i>
            </a>
        </div>
        <br>
        <div class="card-body">
            <form class="row g-3 needs-validation" action="{{route('user.store')}}" method="POST" novalidate>
                @csrf
                <div class="col-md-4">
                    <label for="user" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="user" name="user" value="{{ old('user') }}" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Campo requerido.
                    </div>
                </div>
                <br>
                <div class="col-md-4">
                    <label for="Nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="Nombre" name="Nombre" value="{{ old('Nombre') }}" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Campo requerido.
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="Apellido_pat" class="form-label">Apellido paterno</label>
                    <input type="text" class="form-control" id="Apellido_pat" name="Apellido_pat" value="{{ old('Apellido_pat') }}" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Campo requerido.
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="Apellido_mat" class="form-label">Apellido materno</label>
                    <input type="text" class="form-control" id="Apellido_mat" name="Apellido_mat" value="{{ old('Apellido_mat') }}" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Campo requerido.
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="Numero_cel" class="form-label">Telefono</label>
                    <input type="number" class="form-control" id="Numero_cel" name="Numero_cel" value="{{ old('Numero_cel') }}" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Campo requerido.
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="Calle" class="form-label">Calle</label>
                    <input type="text" class="form-control" id="Calle" name="Calle" value="{{ old('Calle') }}">
                </div>
                <div class="col-md-4">
                    <label for="Numero_casa" class="form-label">Numero de casa</label>
                    <input type="number" class="form-control" id="Numero_casa" name="Numero_casa" value="{{ old('Numero_casa') }}">
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Correo</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text">@</span>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" aria-describedby="inputGroupPrepend" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" value="" maxlength="8" minlength="0" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Campo requerido.
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="id_rol" class="form-label">Rol</label>
                    <select class="form-select" id="id_rol" name="id_rol" required>
                        <option value="">Seleccione una opción</option>
                        @foreach($roles as $rol)
                        <option value="{{$rol->id_rol}}" {{$rol->id_rol = old('user') ? 'selected':''}}>{{$rol->nameRol}}</option>
                        @endforeach
                    </select>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Campo requerido.
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="Estatus_col" class="form-label">Estado</label>
                    <select class="form-select" id="Estatus_col" name="Estatus_col" required>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Campo requerido.
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2 mt-3">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')
        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
@endsection
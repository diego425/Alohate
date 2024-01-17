@extends('layouts.menu_layout')
@section('title', 'Editar usuario')
@section('MenuPrincipal')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form class="row g-3 needs-validation" action="{{route('user.update',$users[0]->Id_colaborador)}}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-4">
                        <label for="user" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="user" name="user" value="{{$users[0]->user}}" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="Nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre" value="{{$users[0]->Nombre}}" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="Apellido_pat" class="form-label">Apellido paterno</label>
                        <input type="text" class="form-control" id="Apellido_pat" name="Apellido_pat" value="{{$users[0]->Apellido_pat}}" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="Apellido_mat" class="form-label">Apellido materno</label>
                        <input type="text" class="form-control" id="Apellido_mat" name="Apellido_mat" value="{{$users[0]->Apellido_mat}}" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="Numero_cel" class="form-label">Telefono</label>
                        <input type="number" class="form-control" id="Numero_cel" name="Numero_cel" value="{{$users[0]->Numero_cel}}" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="Calle" class="form-label">Calle</label>
                        <input type="text" class="form-control" id="Calle" name="Calle" value="{{$users[0]->Calle}}">
                    </div>
                    <div class="col-md-4">
                        <label for="Numero_casa" class="form-label">Numero de casa</label>
                        <input type="number" class="form-control" id="Numero_casa" name="Numero_casa" value="{{$users[0]->Numero_casa}}">
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">Correo</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">@</span>
                            <input type="email" class="form-control" id="email" name="email" value="{{$users[0]->email}}" aria-describedby="inputGroupPrepend" required>
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
                        <input type="password" class="form-control" id="password" name="password" value="{{$users[0]->password}}" required>
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
                            @foreach($roles as $rol)
                                <option value="{{$rol->id_rol}}" {{$rol->id_rol == $users[0]->id_rol ? 'selected': ''}}>{{$rol->nameRol}}</option>
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
                            <option value="activo" {{"activo" == $users[0]->Estatus_col ? 'selected': ''}}>Activo</option>
                            <option value="inactivo" {{"inactivo" == $users[0]->Estatus_col ? 'selected': ''}}>Inactivo</option>
                        </select>
                        <div class="valid-feedback"> 
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Guardar</button>
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
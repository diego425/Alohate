@extends('layouts.menu_layout')
@section('title', 'Editar usuario')
@section('MenuPrincipal')
    <div class="container">
        <div class="card">
            <div class="d-grid gap-2 d-md-block">
                <a class="btn btn-danger" style="position: fixed;" type="button" href="{{ route('user.index') }}">
                    <i class='bx bx-arrow-back'></i>
                </a>
            </div>
            <br>
            <div class="card-body">
                <form class="row g-3 needs-validation" action="{{ route('user.update', $users[0]->Id_colaborador) }}"
                    method="POST" novalidate>
                    @csrf
                    <div class="col-md-4">
                        <label for="user" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="user" name="user"
                            value="{{ $users[0]->user }}" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="Nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre"
                            value="{{ $users[0]->Nombre }}" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="Apellido_pat" class="form-label">Apellido paterno</label>
                        <input type="text" class="form-control" id="Apellido_pat" name="Apellido_pat"
                            value="{{ $users[0]->Apellido_pat }}" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="Apellido_mat" class="form-label">Apellido materno</label>
                        <input type="text" class="form-control" id="Apellido_mat" name="Apellido_mat"
                            value="{{ $users[0]->Apellido_mat }}" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="Numero_cel" class="form-label">Telefono</label>
                        <input type="number" class="form-control" id="Numero_cel" name="Numero_cel"
                            value="{{ $users[0]->Numero_cel }}" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="Calle" class="form-label">Calle</label>
                        <input type="text" class="form-control" id="Calle" name="Calle"
                            value="{{ $users[0]->Calle }}">
                    </div>
                    <div class="col-md-4">
                        <label for="Numero_casa" class="form-label">Numero de casa</label>
                        <input type="number" class="form-control" id="Numero_casa" name="Numero_casa"
                            value="{{ $users[0]->Numero_casa }}">
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">Correo</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">@</span>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ $users[0]->email }}" aria-describedby="inputGroupPrepend" required>
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
                        <input type="password" class="form-control" id="password" name="password" value="password"
                            maxlength="8" minlength="0" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="id_rol" class="form-label">Rol</label>
                        <select class="form-select @error('id_rol') is-invalid @enderror" id="id_rol" name="id_rol"
                            required>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id_rol }}"
                                    {{ $rol->id_rol == $users[0]->id_rol ? 'selected' : '' }}>{{ $rol->nameRol }}</option>
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
                            <option value="activo" {{ 'activo' == $users[0]->Estatus_col ? 'selected' : '' }}>Activo
                            </option>
                            <option value="inactivo" {{ 'inactivo' == $users[0]->Estatus_col ? 'selected' : '' }}>Inactivo
                            </option>
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Campo requerido.
                        </div>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="foto" class="form-label">Fotografia</label>
                        <input type="file" class="form-control" aria-label="file example" id="foto"
                            accept="image/*" onchange="revisarImagen(this,1);">
                        <input type="textarea" name="Fotografia" id="Fotografia" value="" hidden>
                    </div>
                    @if (!empty($users[0]->Fotografia))
                        <div class="mb-3 col-md-4">
                            <img class="xzoom" width="200" src="{{$users[0]->Fotografia}}" xoriginal="" alt="no disponible"
                                height="200" id="imagen_preview"/>
                        </div>
                    @else                        
                        <div class="mb-3 col-md-4">
                            <img class="xzoom" width="200" src="" xoriginal="" alt="no disponible"
                                height="200" id="imagen_preview" style="display: none;"/>
                        </div>                        
                    @endif
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
@endsection
@section('js')
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

        async function revisarImagen(input, num) {
            var imagen = [];
            var id_preview = "imagen_preview";
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onloadend = function(e) {
                    var id_preview_text = "#" + id_preview;
                    var base64image = e.target.result;
                    $("body").append(
                        "<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
                    var canvas = document.getElementById("tempCanvas");
                    var ctx = canvas.getContext("2d");
                    var cw = canvas.width;
                    var ch = canvas.height;
                    var maxW = 1000;
                    var maxH = 1000;
                    var img = new Image;
                    img.src = this.result;
                    img.onload = function() {
                        var iw = img.width;
                        var ih = img.height;
                        var scale = Math.min((maxW / iw), (maxH / ih));
                        var iwScaled = iw * scale;
                        var ihScaled = ih * scale;
                        canvas.width = iwScaled;
                        canvas.height = ihScaled;
                        ctx.drawImage(img, 0, 0, iwScaled, ihScaled);
                        base64image = canvas.toDataURL("image/jpeg");
                        console.log(base64image);
                        $(id_preview_text).attr('src', base64image).width(250).height(157);
                        $(id_preview_text).attr('xoriginal', base64image);
                        imagen[num] = base64image;
                        $("#tempCanvas").remove();
                        $('#Fotografia').val(base64image);
                    }
                };
                reader.readAsDataURL(input.files[0]);
                $('#imagen_preview').show();
            }
        }
    </script>
@endsection

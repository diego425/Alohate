@extends('layouts.menu_layout')
@section('title', 'Editar reporte')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        h6 {
            text-align: center;
        }

        .checkSeleccionar {
            cursor: pointer;
        }

        .pointer {
            cursor: pointer;
        }

        .enmedio {
            position: relative;
            margin-left: 40%;
        }

        .bold {
            font-weight: bold;
        }

        img {
            width: 25%;
        }

        @media screen and (max-width: 600px) {
            img {
                width: 50%;
            }

            .form-switch .form-check-input {
                margin-left: 50%;
            }

            #tablaLugares {
                border: 0;
            }

            #tablaLugares thead {
                display: none;
            }

            #tablaLugares tr {
                border-bottom: 3px solid #ddd;
                display: block;
                margin-bottom: .625em;
            }

            #tablaLugares td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .8em;
                text-align: right;
            }

            #tablaLugares td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }

            #tablaLugares td:last-child {
                border-bottom: 0;
            }

            .flex {
                display: table-cell;
            }

            .form-switch .form-check-input {
                margin-left: 50%;
            }

            h6 {
                font-size: 1em;
            }

            #tablaLugares_filter {
                width: 1rem;
            }

            .numerosTabla {
                visibility: hidden;
            }

            .btnFiltro {
                margin-left: 1em;
            }

            .enmedio {
                position: relative;
                margin-left: 0%;
            }

            h6 {
                text-align: center;
                font-size: 0.8em;
                margin-top: 1em;
            }

            .container {
                font-size: 0.8em;
            }

            button {
                margin-left: 35%;
                font-size: small;
            }
        }
    </style>
@endsection
@section('MenuPrincipal')
    <div class="container">
        <div class="card">
            <div class="d-grid gap-2 d-md-block">
                <a class="btn btn-danger" style="position: fixed;" type="button" href="{{ route('limpieza.index') }}">
                    <i class='bx bx-arrow-back'></i>
                </a>
            </div>
            <br>
            <div class="card-title">
                <h6>¿Tu reporte es de limpieza o mantenimiento?</h6>
            </div>
            <div class="card-body">
                <form class="row g-3 needs-validation" action="{{ route('limpieza.update',$reporte["Id_reporte_ml"]) }}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-12">
                        <div class="enmedio group-input">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input pointer" type="radio" id="Limpieza" name="Tipo_reporte"
                                    value="Limpieza" {{ $reporte['Tipo_reporte'] == 'Limpieza' ? 'checked' : '' }} required>
                                <label class="form-check-label pointer" for="Limpieza">Limpieza</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input pointer" type="radio" id="Mantenimiento"
                                    name="Tipo_reporte" value="Mantenimiento"
                                    {{ $reporte['Tipo_reporte'] == 'Mantenimiento' ? 'checked' : '' }} required>
                                <label class="form-check-label pointer" for="Mantenimiento">Mantenimiento</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input pointer" type="radio" id="Otro" name="Tipo_reporte"
                                    value="Otro" {{ $reporte['Tipo_reporte'] == 'Otro' ? 'checked' : '' }}
                                    required>
                                <label class="form-check-label pointer" for="Otro">Otro</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-ms-12 mantenimiento" style="display: none;">
                        <label for="Categoria_mtto" class="form-label">Tipo de mantenimiento</label>
                        <input type="text" class="form-control" id="Categoria_mtto" name="Categoria_mtto"
                            value="{{ old('Categoria_mtto') }}">
                    </div>
                    @if (!empty($id) && !empty($tipoLocacion) && !empty($Id_locacion))
                        <h6 id="nombreLugar" name="nombreLugar">{{ $nombreLugar }}</h6>
                        <input type="hidden" id="idLugar" name="idLugar" value="{{ $id }}">
                        <input type="hidden" id="tipoLocacion" name="tipoLocacion" value="{{ $tipoLocacion }}">
                        <input type="hidden" id="Id_locacion" name="Id_locacion" value="{{ $Id_locacion }}">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Seleccionar otro lugar
                            </button>
                        </div>
                    @else
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Seleccionar otro lugar
                            </button>
                        </div>
                        <h6 id="nombreLugar" name="nombreLugar">{{ $reporte['nombreLugar'] }}</h6>
                        <input type="hidden" id="idLugar" name="idLugar" value="{{ $reporte['idLugar'] }}">
                        <input type="hidden" id="tipoLocacion" name="tipoLocacion" value="{{ $reporte['tipoLocacion'] }}">
                        <input type="hidden" id="Id_locacion" name="Id_locacion" value="{{ $reporte['Id_locacion'] }}">
                    @endif
                    <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Ingrese la descripción" id="Descripcion_Reporte" name="Descripcion_Reporte"
                                required>{{ $reporte['Descripcion_Reporte'] }}</textarea>
                            <label for="Descripcion_Reporte">Descripción del reporte</label>
                        </div>
                    </div>

                    <h6 class="card-title center bold">Fotografias antes de hacer el trabajo</h6>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="group-input">
                                                <img src="{{empty($fotosAntes[0]->foto1) == false ? $fotosAntes[0]->foto1 : '...'}}" class="" id="foto1Antes" alt="...">
                                                <br>
                                                <input type="file" name="" id="inputfoto1Antes" foto="foto1Antes"
                                                    accept="image/*" style="display: none;" onchange="revisarImagen(this);">
                                                <input type="textarea" name="foto1Antes" id="valfoto1Antes" value="" hidden>
                                                <button class="btn btn-primary" foto="foto1Antes" id="btnfoto1Antes" type="button"
                                                    onclick="clickBtn(this)">
                                                    <i class='bx bxs-camera-plus'></i>
                                                </button>
                                                <button class="btn btn-danger" foto="foto1Antes" id="quitarfoto1Antes"
                                                    type="reset" onclick="quitarFoto(this)" style="display: none;">
                                                    <i class='bx bx-camera-off'></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="group-input">
                                                <img src="{{empty($fotosAntes[0]->foto2) == false ? $fotosAntes[0]->foto2 : '...'}}" class="" id="foto2Antes" alt="...">
                                                <br>
                                                <input type="file" name="" id="inputfoto2Antes" foto="foto2Antes"
                                                    accept="image/*" style="display: none;" onchange="revisarImagen(this);">
                                                <input type="textarea" name="foto2Antes" id="valfoto2Antes" value="" hidden>
                                                <button class="btn btn-primary" foto="foto2Antes" type="button"
                                                    onclick="clickBtn(this)">
                                                    <i class='bx bxs-camera-plus'></i>
                                                </button>
                                                <button class="btn btn-danger" foto="foto2Antes" id="quitarfoto2Antes"
                                                    type="reset" onclick="quitarFoto(this)" style="display: none;">
                                                    <i class='bx bx-camera-off'></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="group-input">
                                                <img src="{{empty($fotosAntes[0]->foto3) == false ? $fotosAntes[0]->foto3 : '...'}}" class="" id="foto3Antes" alt="...">
                                                <br>
                                                <input type="file" name="" id="inputfoto3Antes" foto="foto3Antes"
                                                    accept="image/*" style="display: none;"
                                                    onchange="revisarImagen(this);">
                                                <input type="textarea" name="foto3Antes" id="valfoto3Antes" value=""
                                                    hidden>
                                                <button class="btn btn-primary" foto="foto3Antes" type="button"
                                                    onclick="clickBtn(this)">
                                                    <i class='bx bxs-camera-plus'></i>
                                                </button>
                                                <button class="btn btn-danger" foto="foto3Antes" id="quitarfoto3Antes"
                                                    type="reset" onclick="quitarFoto(this)" style="display: none;">
                                                    <i class='bx bx-camera-off'></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <h6 class="card-title center bold">Fotografias del trabajo terminado</h6>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="group-input">
                                                <img src="{{empty($fotosDespues[0]->foto1) == false ? $fotosDespues[0]->foto1 : '...'}}" class="" id="foto1Despues" alt="...">
                                                <br>
                                                <input type="file" name="" id="inputfoto1Despues" foto="foto1Despues"
                                                    accept="image/*" style="display: none;" onchange="revisarImagen(this);">
                                                <input type="textarea" name="foto1Despues" id="valfoto1Despues" value="" hidden>
                                                <button class="btn btn-primary" foto="foto1Despues" id="btnfoto1Despues" type="button"
                                                    onclick="clickBtn(this)">
                                                    <i class='bx bxs-camera-plus'></i>
                                                </button>
                                                <button class="btn btn-danger" foto="foto1Despues" id="quitarfoto1Despues"
                                                    type="reset" onclick="quitarFoto(this)" style="display: none;">
                                                    <i class='bx bx-camera-off'></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="group-input">
                                                <img src="{{empty($fotosDespues[0]->foto2) == false ? $fotosDespues[0]->foto2 : '...'}}" class="" id="foto2Despues" alt="...">
                                                <br>
                                                <input type="file" name="" id="inputfoto2Despues" foto="foto2Despues"
                                                    accept="image/*" style="display: none;" onchange="revisarImagen(this);">
                                                <input type="textarea" name="foto2Despues" id="valfoto2Despues" value="" hidden>
                                                <button class="btn btn-primary" foto="foto2Despues" type="button"
                                                    onclick="clickBtn(this)">
                                                    <i class='bx bxs-camera-plus'></i>
                                                </button>
                                                <button class="btn btn-danger" foto="foto2Despues" id="quitarfoto2Despues"
                                                    type="reset" onclick="quitarFoto(this)" style="display: none;">
                                                    <i class='bx bx-camera-off'></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="group-input">
                                                <img src="{{empty($fotosDespues[0]->foto3) == false ? $fotosDespues[0]->foto3 : '...'}}" class="" id="foto3Despues" alt="...">
                                                <br>
                                                <input type="file" name="" id="inputfoto3Despues" foto="foto3Despues"
                                                    accept="image/*" style="display: none;"
                                                    onchange="revisarImagen(this);">
                                                <input type="textarea" name="foto3Despues" id="valfoto3Despues" value=""
                                                    hidden>
                                                <button class="btn btn-primary" foto="foto3Despues" type="button"
                                                    onclick="clickBtn(this)">
                                                    <i class='bx bxs-camera-plus'></i>
                                                </button>
                                                <button class="btn btn-danger" foto="foto3Despues" id="quitarfoto3Despues"
                                                    type="reset" onclick="quitarFoto(this)" style="display: none;">
                                                    <i class='bx bx-camera-off'></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2 mt-3">
                                <button class="btn btn-primary" type="submit">Actualizar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">todos los lugares</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <div class="overflow-auto">
                                <table class="table table-hover table-striped" id="tablaLugares">
                                    <thead>
                                        <tr>
                                            <th class="numerosTabla" scope="col">#</th>
                                            <th scope="col">Locación</th>
                                            <th scope="col">Lugar</th>
                                            <th scope="col">Tipo</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Colaborador</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        <!-- {{ $i = 1 }}-->
                                        @foreach ($locaciones as $locacion)
                                            @if ($locacion->mostrar == 'mostrar')
                                                @if ($locacion->tipoLocacion == 'Entera')
                                                    <tr class="{{ $locacion->tipoLocacion }} table-warning">
                                                    @else
                                                    <tr class="{{ $locacion->tipoLocacion }}">
                                                @endif
                                                <th class="numerosTabla" data-label="#">
                                                    <strong>{{ $i++ }}</strong>
                                                </th>
                                                <td data-label="Nombre de locación:">{{ $locacion->nombreLocacion }}</td>
                                                <td data-label="Nombre del lugar:">{{ $locacion->Nombre }}</td>
                                                <td data-label="Tipo de lugar:">
                                                    <strong>{{ $locacion->tipoLocacion }}</strong>
                                                </td>
                                                <td data-label="Estado del lugar:">{{ $locacion->Nombre_estado }}</td>
                                                <td data-label="Colaborador:">
                                                    {{ $locacion->NombreCola . ' ' . $locacion->Apellido_pat }}
                                                </td>
                                                <td>
                                                    <button class="tomarLugar btn btn-outline-danger" type="button"
                                                        colaborador="{{ $locacion->NombreCola . ' ' . $locacion->Apellido_pat }}"
                                                        id="{{ $locacion->tipoLocacion }}{{ $locacion->id }}"
                                                        tipo="{{ $locacion->tipoLocacion }}"
                                                        id-lugar="{{ $locacion->id }}"
                                                        Id_locacion="{{ $locacion->Id_locacion }}"
                                                        locacion="{{ $locacion->nombreLocacion }}"
                                                        lugar="{{ $locacion->Nombre }}" data-bs-dismiss="modal">
                                                        <i class='bx bx-check-circle'></i>
                                                    </button>
                                                </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var table = $('#tablaLugares').DataTable();

            function myFunction(x) {
                if (x.matches) { // If media query matches
                    table.destroy();
                    table = $('#tablaLugares').DataTable({
                        "order": [
                            [0, "asc"]
                        ],
                        responsive: true,
                        scrollCollapse: true,
                        scrollY: '37em',
                        paginate: false,
                        "lengthMenu": [5, 10, 15],
                        "language": {
                            "lengthMenu": "Mostrar _MENU_ registros por pagina",
                            "zeroRecords": "Sin datos",
                            "info": "Mostrando pagina _PAGE_ de _PAGES_",
                            "infoEmpty": "No records available",
                            "infoFiltered": "(filtrado de _MAX_ total registros)",
                            'search': 'Buscar',
                            'paginate': {
                                'next': 'Siguiente',
                                'previous': 'Anterior'
                            }
                        }
                    });
                } else {
                    table.destroy();
                    table = $('#tablaLugares').DataTable({
                        "order": [
                            [0, "asc"]
                        ],
                        responsive: true,
                        stateSave: true,
                        scrollCollapse: true,
                        scrollY: '30em',
                        "lengthMenu": [5, 10, 15],
                        "language": {
                            "lengthMenu": "Mostrar _MENU_ registros por pagina",
                            "zeroRecords": "Sin datos",
                            "info": "Mostrando pagina _PAGE_ de _PAGES_",
                            "infoEmpty": "No records available",
                            "infoFiltered": "(filtrado de _MAX_ total registros)",
                            'search': 'Buscar',
                            'paginate': {
                                'next': 'Siguiente',
                                'previous': 'Anterior'
                            }
                        }
                    });
                }
            }

            // Create a MediaQueryList object
            var x = window.matchMedia("(max-width: 600px)")

            // Call listener function at run time
            myFunction(x);

            // Attach listener function on state changes
            x.addEventListener("change", function() {
                myFunction(x);
            });
        });

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

        $(".tomarLugar").click(function(e) {
            id = $(this).attr("id-lugar");
            Id_locacion = $(this).attr("Id_locacion");
            tipo = $(this).attr("tipo");
            locacion = $(this).attr("locacion");
            lugar = $(this).attr("lugar");
            igual = $(this).attr("igual");

            console.log(id, tipo, locacion, lugar);
            $("#idLugar").val(id);
            $("#tipoLocacion").val(tipo);
            $("#Id_locacion").val(Id_locacion);
            if (tipo == "Entera") {
                $("#nombreLugar").text("" + tipo + ": " + lugar);
            } else {
                $("#nombreLugar").text(locacion + ", " + tipo + ": " + lugar);
            }
        });

        $("[name=Tipo_reporte]").change(function(e) {
            const tipo = $(this).val();
            console.log(tipo);
            if (tipo == "Mantenimiento") {
                $(".mantenimiento").show();
            } else {
                $("#Categoria_mtto").val("");
                $(".mantenimiento").hide();
            }
        });

        async function clickBtn(input) {
            const foto = $(input).attr("foto");
            console.log(foto);
            $("#input" + foto).click();
        }

        async function revisarImagen(input) {
            var id_preview = $(input).attr("foto");
            console.log(input);
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
                        $(id_preview_text).attr('src', base64image);
                        $(id_preview_text).attr('xoriginal', base64image);
                        $("#tempCanvas").remove();
                        $('#val' + id_preview).val(base64image);
                        $("#btn" + id_preview).hide();
                        $("#quitar" + id_preview).show();
                    }
                };
                reader.readAsDataURL(input.files[0]);
                $('#imagen_preview').show();
            }
        }

        async function quitarFoto(input) {
            const foto = $(input).attr("foto");
            console.log(foto);
            $("#" + foto).attr("src", "");
            $("#" + foto).attr("xoriginal", "");
            $("#val" + foto).val("");
            $("#quitar" + foto).hide();
            $("#btn" + foto).show();
        }
    </script>
@endsection

@extends('layouts.menu_layout')
@section('title', 'Realizar tarea')
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

        @media only screen and (min-height: 400px) {
            .dataTables_scrollBody {
                position: relative;
                overflow: auto;
                max-height: 20em;
                width: 100%;
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
            <div class="card-body">
                @if ($reporte->Estatus == 'Pendiente')
                    <h6 class="bold">Fotografias antes de hacer el trabajo</h6>
                @else
                    <h6 class="bold">Fotografias del trabajo terminado</h6>
                @endif
                <form class="row g-3 needs-validation" action="{{ route('limpieza.guardarTarea') }}" method="POST"
                    novalidate>
                    @csrf
                    @if ($reporte->Estatus == 'Pendiente')
                        <input type="hidden" name="Tipo" value="Antes">
                    @else
                        <input type="hidden" name="Tipo" value="Despues">
                    @endif
                    <input type="hidden" name="id" value="{{ $reporte->Id_reporte_ml }}">
                    <input type="hidden" name="Estatus" value="{{ $reporte->Estatus }}">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="group-input">
                                                <img src="..." class="" id="foto1" alt="...">
                                                <br>
                                                <input type="file" name="" id="inputfoto1" foto="foto1"
                                                    accept="image/*" style="display: none;" onchange="revisarImagen(this);">
                                                <input type="textarea" name="foto1" id="valfoto1" value="" hidden>
                                                <button class="btn btn-primary" foto="foto1" id="btnfoto1" type="button"
                                                    onclick="clickBtn(this)">
                                                    <i class='bx bxs-camera-plus'></i>
                                                </button>
                                                <button class="btn btn-danger" foto="foto1" id="quitarfoto1"
                                                    type="reset" onclick="quitarFoto(this)" style="display: none;">
                                                    <i class='bx bx-camera-off'></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="group-input">
                                                <img src="..." class="" id="foto2" alt="...">
                                                <br>
                                                <input type="file" name="" id="inputfoto2" foto="foto2"
                                                    accept="image/*" style="display: none;" onchange="revisarImagen(this);">
                                                <input type="textarea" name="foto2" id="valfoto2" value="" hidden>
                                                <button class="btn btn-primary" foto="foto2" type="button"
                                                    onclick="clickBtn(this)">
                                                    <i class='bx bxs-camera-plus'></i>
                                                </button>
                                                <button class="btn btn-danger" foto="foto2" id="quitarfoto2"
                                                    type="reset" onclick="quitarFoto(this)" style="display: none;">
                                                    <i class='bx bx-camera-off'></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="group-input">
                                                <img src="..." class="" id="foto3" alt="...">
                                                <br>
                                                <input type="file" name="" id="inputfoto3" foto="foto3"
                                                    accept="image/*" style="display: none;"
                                                    onchange="revisarImagen(this);">
                                                <input type="textarea" name="foto3" id="valfoto3" value=""
                                                    hidden>
                                                <button class="btn btn-primary" foto="foto3" type="button"
                                                    onclick="clickBtn(this)">
                                                    <i class='bx bxs-camera-plus'></i>
                                                </button>
                                                <button class="btn btn-danger" foto="foto3" id="quitarfoto3"
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
                                @if ($reporte->Estatus == 'Pendiente')
                                    <button class="btn btn-primary" type="submit">Guardar</button>
                                @else
                                    <button class="btn btn-primary" type="submit">Terminar</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
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

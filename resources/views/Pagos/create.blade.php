@extends('layouts.menu_layout')
@section('title', 'COBROS DE RENTA')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        @media screen and (max-width: 600px) {
            img {
                width: 50%;
                margin-left: 25%;
            }

            .form-switch .form-check-input {
                margin-left: 50%;
            }

            h6 {
                font-size: 1em;
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

        .checkSeleccionar {
            cursor: pointer;
        }
    </style>
@endsection
@section('MenuPrincipal')
    <div class="container">
        <div class="card">
            <div class="card-body row">
                <h6 class="card-title center bold">Datos del servicio</h6>
                <div class="user-detail-card justify" data-mh="card-one">
                    <p><span>Tiempo predefinido de estancia :</span> {{ $cobros[0]->Periodo_total }}</p>
                    <p><span>Personas extras :</span> {{ $cobros[0]->Cobro_persona_extra }}</p>
                    <p><span>Resta de monto de anticipo :</span> {{ $cobros[0]->Monto_pagado_anticipo }}</p>
                    <p><span>Deposito de garantia :</span> {{ $cobros[0]->Periodo_total }}</p>
                    {{-- <p><span>Cargo por uso de cochera :</span></p> --}}
                    <hr>
                    <p><span>Monto total a cobrar :</span> {{ $cobros[0]->Monto_total }}</p>
                </div>

                <h6 class="card-title center bold">Registrar pago del servicio</h6>
                <div class="user-detail-card justify" data-mh="card-one">
                    <form action="{{route('pagos.store',$cobros[0]->Id_cobro_renta)}}" method="post" class="row g-3 needs-validation" novalidate>
                        @csrf
                        <div class="form-floating mb-3 col-md-6">
                            <input type="number" class="form-control" name="Monto_pago" id="Monto_pago" placeholder="" required/>
                            <label for="Monto_pago">Monto del pago</label>
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <input type="date" class="form-control" name="Fecha_pago" id="Fecha_pago" placeholder=""
                                value="{{ date('Y-m-d') }}" required/>
                            <label for="Fecha_pago">Fecha de pago</label>
                        </div>
                        <div class="form-floating mb-3 col-md-4">
                            <select class="form-select" id="Metodo_pago" name="Metodo_pago"
                                aria-label="Floating label select example">
                                <option value="" selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <label for="floatingSelect">Metodo de pago</label>
                        </div>
                        <div class="form-floating mb-3 col-md-12">
                            <input type="text" class="form-control" name="Concepto_pago_renta" id="Concepto_pago_renta"
                                placeholder="" required/>
                            <label for="Concepto_pago_renta">Concepto</label>
                        </div>
                        <h6 class="card-title center bold">Foto del comprobante de pago</h6>
                        <div class="group-input col-mb-12">
                            <img src="..." class="" id="fotoComprobante" alt="...">
                            <br>
                            <input type="file" class="custom-file-input form-control"  name="imagen" id="inputfotoComprobante" foto="fotoComprobante" onchange="revisarImagen(this);" accept="image/*">
                            <input type="textarea" name="fotoComprobante" id="valfotoComprobante" value="" hidden>
                            <button class="btn btn-danger" foto="fotoComprobante" id="quitarfotoComprobante"
                                type="reset" onclick="quitarFoto(this)" style="display: none;">
                                <i class='bx bx-camera-off'></i>
                            </button>
                        </div>
                        <div class="group-input">
                            <button type="submit" class="btn btn-outline-warning">Cancelar</button>
                            <button type="submit" class="btn btn-outline-success">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        const cobros = @json($cobros);
        console.log(cobros);

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

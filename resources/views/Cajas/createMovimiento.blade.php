@extends('layouts.menu_layout')
@section('title', 'MOVIMIENTO CAJA CHICA')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    select,
    option {
        cursor: pointer;
    }

    img {
        width: 20%;
        margin-left: 10%;
    }

    @media screen and (max-width: 600px) {
        img {
            width: 50%;
            margin-left: 25%;
        }

        .agregar {
            width: 10%;
        }

        table {
            border: 0;
        }

        table thead {
            display: none;
        }

        table tr {
            border-bottom: 3px solid #ddd;
            display: block;
            margin-bottom: .625em;
        }

        table td {
            border-bottom: 1px solid #ddd;
            display: block;
            font-size: .8em;
            text-align: right;
        }

        table td::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }

        table td:last-child {
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
            margin-left: 0em;
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
        <div class="card-body">
            <div class="d-grid gap-2 d-md-block">
                <a class="btn btn-danger" style="position: fixed;" type="button" href="{{ url()->previous() }}">
                    <i class='bx bx-arrow-back'></i>
                </a>
            </div>
            <br><br>
            <div class="card-title">
                <h6 class="card-title center bold" style="text-align: center;">Detalles de la caja chica</h6>
                <div class="user-detail-card justify" data-mh="card-one">
                    <p><span>Caja :</span> {{ $caja[0]->idCajas }}</p>
                    <p><span>Encargado :</span> {{ $colaboradores[0]->Nombre." ".$colaboradores[0]->Apellido_pat." ".$colaboradores[0]->Apellido_mat }}</p>
                    <p><span>Saldo actual:</span> {{ $caja[0]->Saldo }}</p>
                </div>
            </div>
            <form class="row g-3 needs-validation" action="{{ route('caja.storeMovimiento') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="idCajas" value="{{$caja[0]->idCajas}}">
                <div class="col-md-10 col-xl-12 col-sm-6">
                    <label for="TipoMovimiento" class="form-label">Tipo movimiento</label>
                    <select class="form-select" id="TipoMovimiento" name="TipoMovimiento" required>
                        <option selected disabled value="">Elija una opción</option>
                        <option value="Ingreso">Ingreso</option>
                        <option value="Egreso">Egreso</option>
                    </select>
                    <div class="invalid-feedback">
                        Campo obligatorio.
                    </div>
                </div>
                <div class="col-md-10 col-xl-12 col-sm-6">
                    <label for="Monto" class="form-label">Monto</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend"><i class='bx bx-dollar'></i></span>
                        <input type="text" class="form-control" id="Monto" name="Monto" onkeypress="return /[0-9.]/i.test(event.key)" aria-describedby="inputGroupPrepend" required>
                        <div class="invalid-feedback">
                            Campo obligatorio.
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-xl-12 col-sm-6">
                    <label for="Operacion" class="form-label">Operacion</label>
                    <input type="text" class="form-control" list="listOperacion" id="Operacion" name="Operacion" value="{{old('Operacion')}}" required>
                    <datalist id="listOperacion">
                        <option value="Saldo inicial">
                        <option value="Ingreso a caja chica">
                        <option value="Movilidad">
                        <option value="Viáticos">
                        <option value="Servicio Público">
                        <option value="Útiles de escritorio">
                        <option value="Útiles de aseo">
                        <option value="Mantenimiento">
                        <option value="Recibo de agua">
                        <option value="Recibo de luz">
                    </datalist>
                </div>
                <div class="col-md-10 col-xl-12 col-sm-6">
                    <img src="..." class="" id="fotoComprobante" alt="...">
                    <br>
                    <label for="Comprobante" class="form-label">Comprobante</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupComprobante"><i class='bx bx-camera'></i></span>
                        <input type="file" class="form-control" id="inputfotoComprobante" foto="fotoComprobante" name="imagen" onchange="revisarImagen(this);" accept="image/*" aria-describedby="inputGroupComprobante">
                        <input type="textarea" name="fotoComprobante" id="valfotoComprobante" value="" hidden>
                        <button class="btn btn-danger" foto="fotoComprobante" id="quitarfotoComprobante" type="reset" onclick="quitarFoto(this)" style="display: none;">
                            <i class='bx bx-camera-off'></i>
                        </button>
                        <div class="invalid-feedback">
                            Campo obligatorio.
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xl-6 col-sm-2">
                    <label for="Serie" class="form-label">Serie factura</label>
                    <input type="text" class="form-control" id="Serie" name="Serie" value="{{old('Serie')}}">
                </div>
                <div class="col-md-4 col-xl-6 col-sm-2">
                    <label for="Folio" class="form-label">Folio factura</label>
                    <input type="text" class="form-control" id="Folio" name="Folio" value="{{old('Folio')}}">
                </div>
                <div class="col-md-12 col-xl-12 col-sm-12">
                    <label for="Observacion" class="form-label">Observacion (opcional)</label>
                    <textarea type="textarea" class="form-control" id="Observacion" name="Observacion" placeholder="Escribe una observación de ser necesario."></textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary btn-guardar" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Busqueda</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="search">
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-primary" href="{{ route('pagos.index') }}"><i class='bx bx-refresh'></i> Reset</a>
                    <button type="submit" class="btn btn-success" data-bs-dismiss="modal" form="formulario"><i class='bx bx-search'></i> Buscar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" integrity="sha512-6JR4bbn8rCKvrkdoTJd/VFyXAN4CE9XMtgykPWgKiHjou56YDJxWsi90hAeMTYxNwUnKSQu9JPc3SQUg+aGCHw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    $(document).ready(function() {
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
                    } else {
                        $(".btn-guardar").remove();
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })();

        tinymce.init({
            selector: '#Observacion',
            height: "300",
            menubar: false,
            toolbar: 'undo redo | styleselect | formatselect | fontsizeselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat',
            content_style: 'body { font-family:Helvetica; }'
        });

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
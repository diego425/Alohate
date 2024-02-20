@extends('layouts.menu_layout')
@section('title', 'GENERAL')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.js"
        integrity="sha512-is1ls2rgwpFZyixqKFEExPHVUUL+pPkBEPw47s/6NDQ4n1m6T/ySeDW3p54jp45z2EJ0RSOgilqee1WhtelXfA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .center {
            margin: auto;
            width: 50%;
            padding: 10px;
        }

        @media screen and (max-width: 600px) {
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

            .numerosTabla {
                visibility: hidden;
            }

            .btnFiltro {
                margin-left: 1em;
            }

            .center {
                margin: auto;
                width: 50%;
                padding: 10px;
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
                    <a class="btn btn-danger" style="position: absolute;" type="button" href="{{ route('pagos.index') }}">
                        <i class='bx bx-arrow-back'></i>
                    </a>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-info me-md-2" onclick="printAll()">
                        <i class='bx bx-printer'>Generar todos</i>
                    </button>
                </div>
                <br>
                <hr>
                <table class="table table-primary" id="tableLugares">
                    <thead>
                        <tr>
                            <th style="display: none;">id</th>
                            <th scope="col">Locación</th>
                            <th scope="col">Lugar</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locaciones as $locacion)
                            @if ($locacion->mostrar == 'mostrar')
                                @if ($locacion->tipoLocacion == 'Entera')
                                    <tr class="{{ $locacion->tipoLocacion }} table-warning">
                                @else
                                    <tr class="{{ $locacion->tipoLocacion }}">
                                @endif
                                <td style="display: none;">{{ $locacion->id }}</td>
                                <td data-label="Nombre de locación:">{{ $locacion->nombreLocacion }}</td>
                                <td data-label="Nombre del lugar:">{{ $locacion->Nombre }}</td>
                                <td data-label="Tipo de lugar:"><strong>{{ $locacion->tipoLocacion }}</strong></td>
                                <td data-label="Estado del lugar:">{{ $locacion->Nombre_estado }}</td>
                                <td data-label="Generar QR">
                                    <button class="btn btn-outline-success generarQR"
                                        ruta="{{ route('pagos.pagoClienteDirecto', [$locacion->tipoLocacion, $locacion->Id_locacion, $locacion->id]) }}"
                                        locacion="{{ $locacion->nombreLocacion }}" lugar="{{ $locacion->Nombre }}">
                                        <i class='bx bx-qr'></i>
                                    </button>
                                </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-fullscreen-lg-down modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="areaImpresion">
                        <div class="card">
                            <div id="qrcode" class="center" style="margin: auto;width: 50%;padding: 10px;"></div>
                            <div class="card-body">
                                <p class="card-text" id="textCard" style="text-align: center;"></p>
                                <p class="card-text" id="textLink" style="text-align: center;"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-info printQR">
                            <i class='bx bx-printer'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ModalAll -->
        <div class="modal fade" id="staticBackdropAll" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropAllLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-fullscreen-lg-down modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropAllLabel"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="areaImpresionAll">
                        @foreach ($locaciones as $locacion)
                            <div class="card">
                                <div id="qrcode{{ $locacion->id }}{{ $locacion->tipoLocacion }}" class="center"
                                    style="margin: auto;width: 50%;padding: 10px;"></div>
                                <div class="card-body">
                                    <p class="card-text" id="textCard{{ $locacion->id }}{{ $locacion->tipoLocacion }}"
                                        style="text-align: center;">
                                        {{ $locacion->nombreLocacion }} : {{ $locacion->Nombre }}</p>
                                    <p class="card-text" id="textLink{{ $locacion->id }}{{ $locacion->tipoLocacion }}"
                                        style="text-align: center;">
                                        {{ route('pagos.pagoClienteDirecto', [$locacion->tipoLocacion, $locacion->Id_locacion, $locacion->id]) }}
                                    </p>
                                </div>
                            </div>
                            <br><br><br><br>
                            <hr>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-info printQRAll">
                            <i class='bx bx-printer'></i>
                        </button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"
        integrity="sha256-+0Qf8IHMJWuYlZ2lQDBrF1+2aigIRZXEdSvegtELo2I=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            data = @json($locaciones);
            console.log(data);

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
            })();

            var table = $('#tableLugares').DataTable({
                responsive: true,
                autoWidth: false,
                "lengthMenu": [5,10,20],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por pagina",
                    "zeroRecords": "Cargando depositos - esperé",
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

            var qrcode = new QRCode("qrcode", {
                text: "google.com",
                width: 400,
                height: 400,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            $(".generarQR").click(function(e) {
                console.log('click');
                qrcode.clear(); // clear the code.
                const locacion = $(this).attr("locacion");
                const lugar = $(this).attr("lugar");
                const ruta = $(this).attr("ruta");

                qrcode.makeCode(ruta);
                $("img").addClass("center");
                $("#textCard").text(locacion + ": " + lugar);
                $("#textLink").text(ruta);
                let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('staticBackdrop')); // Returns a Bootstrap modal instance
                //titulo, texto, url
                notificar("cuartosags.com", "Se genero QR de pago", ruta);
                // Show or hide:
                modal.show();
            });

            $(".printQR").click(function(e) {
                console.log("print");
                printdiv("areaImpresion");
            });

            $(".printQRAll").click(function(e) {
                console.log("print");
                printdiv("areaImpresionAll");
            });

            function printdiv(printdivname) {
                var divContents = $("#" + printdivname).html();
                var printWindow = window.open('', '', 'height=400,width=800');
                printWindow.document.write('<html><head><title>DIV Contents</title>');
                printWindow.document.write('</head><body >');
                printWindow.document.write(divContents);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            }

        });

        async function printAll() {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                await processPrintALL(element, index).then((result) => {
                    console.log(result);
                }).catch((err) => {
                    console.log(err);
                });
            }

            let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById(
                'staticBackdropAll')); // Returns a Bootstrap modal instance
            modal.show();
        }

        function processPrintALL(element, index) {
            return new Promise((resolve, reject) => {
                const ruta = $("#textLink" + element.id + element.tipoLocacion).text();

                const qrcodeAll = new QRCode("qrcode" + element.id + element.tipoLocacion + "", {
                    text: ruta,
                    width: 400,
                    height: 400,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });

                return resolve();
            });
        }
    </script>
@endsection

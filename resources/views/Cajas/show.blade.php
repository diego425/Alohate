@extends('layouts.menu_layout')
@section('title', 'MOVIMIENTO CAJA CHICA')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://fengyuanchen.github.io/viewerjs/css/viewer.css">
    <script src="https://fengyuanchen.github.io/viewerjs/js/viewer.js"></script>
    <style>
        select,
        option {
            cursor: pointer;
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
    @php
        setlocale(LC_MONETARY, 'es_MX');
    @endphp
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2 d-md-block">
                    <a class="btn btn-danger" style="position: fixed;" type="button" href="{{ route('caja.index') }}">
                        <i class='bx bx-arrow-back'></i>
                    </a>
                </div>
                <br><br>
                <div class="card-title">
                    <h6 class="card-title center bold" style="text-align: center;">Detalles de la caja chica</h6>
                    <div class="user-detail-card justify" data-mh="card-one">
                        <p><span>Caja :</span> {{ $caja[0]->idCajas }} &nbsp;&nbsp;&nbsp;&nbsp;<a
                                href="{{ route('caja.createMovimiento', $caja[0]->idCajas) }}"
                                class="btn btn-success">Agregar movimiento<i class='bx bx-detail'></i></a></p>
                        <p><span>Encargado :</span>
                            {{ $colaboradores[0]->Nombre . ' ' . $colaboradores[0]->Apellido_pat . ' ' . $colaboradores[0]->Apellido_mat }}
                        </p>
                        <p><span>Saldo actual:</span> <strong>{{ number_format($caja[0]->Saldo, 2, '.', ',') }}</strong></p>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <h6 class="card-title center bold" style="text-align: center;">Movimientos</h6>
                    <form class="row" action="" method="get" id="formularioBuscar">
                        <div class="col-sm-2 col-md-6 col-xl-6">
                            <label class="form-label" for="fecha">Fecha</label>
                            <input type="datetime-local" name="fecha" id="fecha"
                                value="{{ empty(old('fecha')) == true ? date('Y-m-d') : old('fecha') }}T00:00"
                                class="form-control">
                        </div>
                    </form>
                    <table class="table table-hover table-striped" id="tablaMovimientos">
                        <thead>
                            <tr class="">
                                <th>Fecha</th>
                                <th>Operación</th>
                                <th>Observación</th>
                                <th>Colaborador</th>
                                <th>Comprobante</th>
                                <th>Serie</th>
                                <th>Folio</th>
                                <th>Ingreso</th>
                                <th>Egreso</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movimientos as $key => $item)
                                <tr class="{{ $item->TipoMovimiento == 'Ingreso' ? 'table-success' : 'table-warning' }}">
                                    <td data-label="Fecha">{{ $item->dateInsert }}</td>
                                    <td data-label="Operación">{{ $item->Operacion }}</td>
                                    <td data-label="Observación">
                                        @if (!empty($item->Observacion))
                                            <?php echo htmlspecialchars_decode($item->Observacion); ?>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td data-label="Colaborador">
                                        {{ $item->Nombre . ' ' . $item->Apellido_pat . ' ' . $item->Apellido_mat }}</td>
                                    <td data-label="Comprobante">
                                        @if (!empty($item->Comprobante))
                                            <div class="gallery-container" id="gallery{{ $key }}">
                                                <img src="{{asset($path.$item->Comprobante)}}" alt="..." style="width: 60px;height: 70px;">
                                            </div>
                                        @endif
                                    </td>
                                    <td data-label="Serie">{{ empty($item->Serie) == false ? $item->Serie : '-' }}</td>
                                    <td data-label="Folio">{{ empty($item->Folio) == false ? $item->Folio : '-' }}</td>
                                    @if ($item->TipoMovimiento == 'Ingreso')
                                        <td data-label="Ingreso">{{ number_format($item->Monto, 2, '.', ',') }}</td>
                                        <td data-label="Egreso">-</td>
                                    @elseif($item->TipoMovimiento == 'Egreso')
                                        <td data-label="Ingreso">-</td>
                                        <td data-label="Egreso">{{ number_format($item->Monto, 2, '.', ',') }}</td>
                                    @endif
                                    <td data-label="Saldo">{{ number_format($item->SaldoActual, 2, '.', ',') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="table-info">Totales</td>
                                <td data-label="Ingreso" class="table-info">
                                    @php
                                        $totalIngreso = 0;
                                    @endphp
                                    @foreach ($movimientos as $item)
                                        @if ($item->TipoMovimiento == 'Ingreso')
                                            @php
                                                $totalIngreso += $item->Monto;
                                            @endphp
                                        @endif
                                    @endforeach
                                    {{ number_format($totalIngreso, 2, '.', ',') }}
                                </td>
                                <td data-label="Egreso" class="table-info">
                                    @php
                                        $totalEgreso = 0;
                                    @endphp
                                    @foreach ($movimientos as $item)
                                        @if ($item->TipoMovimiento == 'Egreso')
                                            @php
                                                $totalEgreso += $item->Monto;
                                            @endphp
                                        @endif
                                    @endforeach
                                    {{ number_format($totalEgreso, 2, '.', ',') }}
                                </td>
                                <td class="table-info">-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
                        <a type="button" class="btn btn-primary" href="{{ route('pagos.index') }}"><i
                                class='bx bx-refresh'></i> Reset</a>
                        <button type="submit" class="btn btn-success" data-bs-dismiss="modal" form="formulario"><i
                                class='bx bx-search'></i> Buscar</button>
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

            flatpickr("#fecha", {});

            $('#tablaMovimientos').DataTable({
                dom: 'Bfrtip',
                "order": [
                    [0, "desc"]
                ],
                buttons: [{
                    extend: 'print',
                    text: 'Imprimir',
                    key: {
                        key: 'p',
                        altkey: true
                    },
                    footer: true
                }],
                responsive: true,
                autoWidth: false,
                "lengthMenu": [50, 15, 20],
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

            $("#fecha").change(function(e) {
                console.log('change');
                $("#formularioBuscar").submit();
            });

            @foreach ($movimientos as $key => $item)
                @if (!empty($item->Comprobante))
                    new Viewer(document.getElementById('gallery{{ $key }}'));
                @endif
            @endforeach
        });
    </script>
@endsection

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
                margin-left: 1em;
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

        .checkSeleccionar {
            cursor: pointer;
        }
    </style>
@endsection
@section('MenuPrincipal')
    <div class="container">
        <div class="card">
            <div class="card-body row">
                <div class="col-sm-3">
                    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="{{ route('limpieza.index') }}"
                        method="get" role="search">
                        <input type="search" name="buscar" class="form-control" placeholder="Buscar..."
                            aria-label="Search">
                    </form>
                </div>
                <div class="col-sm-9 d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-small btn-primary small btnFiltro me-md-2" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <i class='bx bx-filter-alt'></i>
                        Filtros
                    </button>
                    <a class="btn btn-primary me-md-2" href="{{ route('limpieza.create') }}">
                        <i class='bx bx-add-to-queue'></i>
                        Agregar
                    </a>
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="tableCobros">
                <thead class="table-light">
                    <tr>
                        <th>Id</th>
                        <th>Fecha inicio</th>
                        <th>Fecha salida</th>
                        <th>Cliente</th>
                        <th>Lugar</th>
                        <th>Estatus</th>
                        <th>Total</th>
                        <th>Saldo</th>
                        <th colspan="4">Accion</th>
                    </tr>
                </thead>
                <caption>
                    Cobros
                </caption>
                <tbody class="table-group-divider">
                    @foreach ($cobros as $cobro)
                        @if ($cobro->Estado == 'Cobro completo')
                            <tr class="table-success">
                            @else
                            <tr class="">
                        @endif
                        <td data-label="Id" scope="row" class="Id">{{ $cobro->Id_cobro_renta }}</td>
                        <td data-label="Inicio">{{ $cobro->Start_date }}</td>
                        <td data-label="Salida">{{ $cobro->End_date }}</td>
                        <td data-label="Cliente" class="Cliente"></td>
                        <td data-label="Lugar">{{ $cobro->tipoLocacion }} :
                            {{ empty($cobro->lugarEspecifico[0]['Nombre']) == false ? $cobro->lugarEspecifico[0]['Nombre'] : '' }}
                        </td>
                        <td data-label="Estatus">{{ $cobro->Estado }}</td>
                        <td data-label="Total">{{ $cobro->Monto_total }}</td>
                        @if (!empty($cobro->Saldo))
                            <td data-label="Saldo">{{ $cobro->Saldo }}</td>
                        @else
                            <td data-label="Saldo">0</td>
                        @endif

                        @if ($cobro->Estado == 'Cobro completo')
                            <td class="flex" data-label="Detalle">
                                <form action="{{ route('pagos.show', $cobro->Id_cobro_renta) }}" method="get">
                                    <button class="btn btn-outline-light" type="submit" title="Mostrar detalle">
                                        <i class='bx bxs-show bx-burst' style='color:#1764e6'></i>
                                    </button>
                                </form>
                            </td>
                        @else
                            <td class="flex" data-label="Cobrar">
                                <form action="{{ route('pagos.create', $cobro->Id_cobro_renta) }}" method="get">
                                    <button class="btn btn-outline-light" type="submit" title="Registrar pago">
                                        <i class='bx bx-money' style='color:#3fe60d'></i>
                                    </button>
                                </form>
                            </td>
                            <td class="flex" data-label="Detalle">
                                <form action="{{ route('pagos.show', $cobro->Id_cobro_renta) }}" method="get">
                                    <button class="btn btn-outline-light" type="submit" title="Mostrar detalle">
                                        <i class='bx bxs-show bx-burst' style='color:#1764e6'></i>
                                    </button>
                                </form>
                            </td>
                            <td class="flex" data-label="Editar">
                                <form action="{{ route('pagos.edit', $cobro->Id_cobro_renta) }}" method="get">
                                    <button class="btn btn-outline-warning" type="submit" title="Editar">
                                        <i class='bx bx-edit-alt'></i>
                                    </button>
                                </form>
                            </td>
                            <td class="flex" data-label="Confirmar pagos">
                                <form action="{{ route('pagos.confirmarPago', $cobro->Id_cobro_renta) }}" method="get">
                                    <button class="btn btn-outline-warning" type="submit" title="Confirmar pagos">
                                        <i class='bx bxs-bank'></i>
                                    </button>
                                </form>
                            </td>
                        @endif
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                </tfoot>
            </table>
            <div class="d-flex flex-row-reverse">
                {{ $cobros->appends(Request::all())->render() }}
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
                            <form class="row col-lg-auto mb-sm-0 me-sm-3" action="#" method="get" role="search"
                                id="formulario">
                                <div class="col-sm-3">
                                    <label for="Id_locacion" class="form-label">Locacion</label>
                                    <select class="form-select" id="Id_locacion" name="Id_locacion">
                                        <option selected disabled value="">Elija...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione un lugar.
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="Tipo_reporte" class="form-label">Estatus de cobro</label>
                                    <select class="form-select" id="Tipo_reporte" name="Tipo_reporte">
                                        <option selected disabled value="">Elija...</option>
                                        <option value="7">Cobrar renta</option>
                                        <option value="8">Cobro completo</option>
                                        <option value="porConfirmar">Con pagos por confirmar</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="Fecha_del_reporte" class="form-label">Fecha</label>
                                    <input type="date" class="form-control" name="Fecha_del_reporte"
                                        id="Fecha_del_reporte">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal"
                            form="formulario">Buscar</button>
                        <a type="button" class="btn btn-primary" href="{{ route('limpieza.index') }}">Reset</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        const data = @json($cobros);
        console.log(data);
        $(document).ready(function() {
            $('#tableCobros > tbody  > tr').each(function(index) {
                data.data.forEach(element => {
                    cliente = JSON.parse(element.Title);
                    if ($('.Id', this).text() == element.Id_cobro_renta) {
                        $('.Cliente', this).text(cliente[0].Nombre);
                    }
                });
            });
        });
    </script>
@endsection

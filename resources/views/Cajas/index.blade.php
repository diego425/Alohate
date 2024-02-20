@extends('layouts.menu_layout')
@section('title', 'LISTADO DE CAJA CHICA')
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
            <div class="card-body row">
                <div class="col-sm-3">
                    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="{{ route('caja.index') }}" method="get"
                        role="search">
                        <input type="search" name="buscar" class="form-control" placeholder="Buscar..."
                            aria-label="Search">
                    </form>
                </div>
                @if (Cookie::get('puesto') == 'ADMIN')
                    <div class="col-sm-9 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a class="btn btn-danger me-md-2" href="{{ route('caja.create') }}" type="button"
                            title="Agregar caja chica">
                            <i class='bx bx-add-to-queue'></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="tableCobros">
                <thead class="table-light">
                    <tr>
                        <th>Id</th>
                        <th>Encargado</th>
                        <th>Saldo</th>
                        <th colspan="3">Accion</th>
                    </tr>
                </thead>
                <caption>
                    Cajas
                </caption>
                <tbody class="table-group-divider">
                    @foreach ($cajas as $item)
                        <tr>
                            <td>{{ $item->idCajas }}</td>
                            <td data-label="Encargado">{{ $item->Nombre . ' ' . $item->Apellido_pat . ' ' . $item->Apellido_mat }}</td>
                            <td data-label="Saldo">{{ $item->Saldo }}</td>
                            <td data-label="Agregar movimiento">
                                <a class="btn btn-warning" href="{{ route('caja.createMovimiento', $item->idCajas) }}"
                                    title="Agregar movimiento">
                                    <i class='bx bx-list-plus'></i>
                                </a>
                            </td>
                            <td data-label="Detalle">
                                <a class="btn btn-success" href="{{ route('caja.show', $item->idCajas) }}"
                                    title="Detalle de caja">
                                    <i class='bx bx-detail'></i>
                                </a>
                            </td>
                            <td data-label="Corte">
                                <a class="btn btn-danger" href="{{ route('caja.show', $item->idCajas) }}"
                                    title="Realizar corte">
                                    <i class='bx bx-cut'></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex flex-row-reverse">
                {{ $cajas->appends(Request::all())->render() }}
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
    <script></script>
@endsection

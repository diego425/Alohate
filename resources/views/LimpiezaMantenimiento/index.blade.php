@extends('layouts.menu_layout')
@section('title', 'REPORTES DE LIMPIEZA Y MTTO')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    @media screen and (max-width: 600px) {
        .agregar{
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
                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="{{route('limpieza.index')}}" method="get" role="search">
                    <input type="search" name="buscar" class="form-control" placeholder="Buscar..." aria-label="Search">
                </form>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-small btn-primary small btnFiltro me-md-2" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    <i class='bx bx-filter-alt'></i>
                    Filtros
                </button>
                <a class="btn btn-primary me-md-2" href="{{route('limpieza.create')}}">
                    <i class='bx bx-add-to-queue'></i>
                    Agregar
                </a>
            </div>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Id</th>
                    <th>Tipo</th>
                    <th>Locación</th>
                    <th>Lugar</th>
                    <th>Descripcion</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th colspan="3">Accion</th>
                </tr>
            </thead>
            <caption>
                Reportes
            </caption>
            <tbody class="table-group-divider">
                @foreach ($reportes as $reporte)
                <tr class="">
                    <td data-label="Id" scope="row">{{$reporte->Id_reporte_ml}}</td>
                    <td data-label="Tipo">{{$reporte->Tipo_reporte}}</td>
                    <td data-label="Locacion">{{$reporte->Nombre_locacion}}</td>
                    @if (!empty($reporte->lugarEspecifico[0]["Nombre"]))
                        <td data-label="Lugar especifico">{{$reporte->tipoLocacion.": ".$reporte->lugarEspecifico[0]["Nombre"]}}</td>
                    @else
                        <td></td>
                    @endif
                    <td data-label="Descripcion">{{$reporte->Descripcion_Reporte}}</td>
                    <td data-label="Fecha">{{$reporte->Fecha_del_reporte}}</td>
                    <td data-label="Estatus">{{$reporte->Estatus}}</td>
                    <td class="flex" data-label="Detalle">
                        <form action="{{route('limpieza.show',$reporte->Id_reporte_ml)}}" method="get">
                            <button class="btn btn-outline-light" type="submit" title="Mostrar detalle">
                                <i class='bx bxs-show bx-burst' style='color:#1764e6'></i>
                            </button>
                        </form>
                    </td>
                    <td class="flex" data-label="Editar">
                        <form action="{{route('limpieza.edit',$reporte->Id_reporte_ml)}}" method="get">
                            <button class="btn btn-outline-warning" type="submit" title="Editar">
                                <i class='bx bx-edit-alt'></i>
                            </button>
                        </form>
                    </td>
                    @if($reporte->Estatus != "Terminado")
                    <td class="flex" data-label="Realizar tarea">
                        <form action="{{route('limpieza.realizar',$reporte->Id_reporte_ml)}}" method="get">
                            <button class="btn btn-outline-success" type="submit" title="Realizar tarea">
                                <i class='bx bx-spreadsheet'></i>
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
            {{ $reportes->appends(Request::all())->render() }}
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
                        <form class="row col-lg-auto mb-sm-0 me-sm-3" action="#" method="get" role="search" id="formulario">
                            <div class="col-sm-3">
                                <label for="Tipo_reporte" class="form-label">Tipo de reporte</label>
                                <select class="form-select" id="Tipo_reporte" name="Tipo_reporte">
                                    <option selected disabled value="">Elija...</option>
                                    <option value="Limpieza">Limpieza</option>
                                    <option value="Mantenimiento">Mantenimiento</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="Fecha_del_reporte" class="form-label">Fecha</label>
                                <input type="date" class="form-control" name="Fecha_del_reporte" id="Fecha_del_reporte">
                            </div>
                            <div class="col-sm-3">
                                <label for="Estatus" class="form-label">Estatus</label>
                                <select class="form-select" id="Estatus" name="Estatus">
                                    <option selected disabled value="">Elija...</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Iniciado">Iniciado</option>
                                    <option value="Terminado">Terminado</option>
                                </select>
                            </div>
                            <div class="input-group mb-3 col">
                                <div class="col-sm-3">
                                    <label for="Id_locacion" class="form-label">Locacion</label>
                                    <select class="form-select" id="Id_locacion" name="Id_locacion">
                                        <option selected disabled value="">Elija...</option>
                                        @foreach ($locaciones as $locacion)
                                            <option value="{{$locacion->Id_locacion}}">{{$locacion->Nombre_locacion}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione un lugar.
                                    </div>
                                </div>
                                {{-- <div class="col-sm-1 mt-4 mb-3" style="margin-left: 1em;">
                                    <button type="submit" class="btn btn-danger">
                                        <i class='bx bx-search-alt'></i>
                                    </button>
                                </div> --}}
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-primary" href="{{route('limpieza.index')}}">
                    <i class='bx bx-refresh'></i> Reset</a>
                    <button type="submit" class="btn btn-success" data-bs-dismiss="modal" form="formulario">
                    <i class='bx bx-search'></i> Buscar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        const datos = @json($reportes);
        console.log(datos);
    </script>
@endsection
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
            <div class="col-sm-9">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a class="btn btn-primary me-md-2" href="{{route('limpieza.create')}}">
                        <i class='bx bx-add-to-queue'></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Id</th>
                    <th>Lugar</th>
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
                    <td data-label="Descripcion">{{$reporte->Descripcion_Reporte}}</td>
                    <td data-label="Fecha">{{$reporte->Fecha_del_reporte}}</td>
                    <td data-label="Estatus">{{$reporte->Estatus}}</td>
                    <td class="flex" data-label="Detalle">
                        <form action="{{route('limpieza.show',$reporte->Id_colaborador)}}" method="get">
                            <button class="btn btn-outline-light" type="submit" title="Mostrar detalle">
                                <i class='bx bxs-show bx-burst' style='color:#1764e6'></i>
                            </button>
                        </form>
                    </td>
                    <td class="flex" data-label="Editar">
                        <form action="{{route('limpieza.edit',$reporte->Id_colaborador)}}" method="get">
                            <button class="btn btn-outline-warning" type="submit" title="Editar">
                                <i class='bx bx-edit-alt'></i>
                            </button>
                        </form>
                    </td>
                    <td class="flex" data-label="Lugares">
                        <form action="{{route('limpieza.show',$reporte->Id_colaborador)}}" method="get">
                            <button class="btn btn-outline-success" type="submit" title="Realizar tarea">
                                <i class='bx bx-spreadsheet' ></i>
                            </button>
                        </form>
                    </td>
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
</div>
@endsection
@section('js')
@endsection
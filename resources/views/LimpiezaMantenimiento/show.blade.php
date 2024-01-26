@extends('layouts.menu_layout')
@section('title', 'DETALLE DE LIMPIEZA Y MTTO')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://fengyuanchen.github.io/viewerjs/css/viewer.css">
    <script src="https://fengyuanchen.github.io/viewerjs/js/viewer.js"></script>
    <style>
        .card-body {
            text-align: center;
        }

        .carousel-item{
            cursor: zoom-in;
        }

        .carousel{
            width: 50%;
            margin-left: 25%;
        }

        @media screen and (max-width: 600px) {
            .carousel{
                width: 100%;
                margin-left: 0%;
            }

            .card-body {
                text-align: start;
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

            h6 {
                text-align: center;
                font-size: 1em;
                margin-top: 1em;
            }

            .container {
                font-size: 0.8em;
            }

            img {
                width: 250px;
                height: 250px;
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

        .bold {
            font-weight: bold;
        }
    </style>
@endsection
@section('MenuPrincipal')
    <div class="container text-uppercase">
        <div class="card mb-3" style="max-width: 100%;">
            <div class="row g-0">
                <div class="d-grid gap-2 d-md-block">
                    <a class="btn btn-danger" style="position: fixed;" type="button" href="{{ route('limpieza.index') }}">
                        <i class='bx bx-arrow-back'></i>
                    </a>
                </div>
                <br>
                <div class="col-md-12">
                    <div class="card-body">
                        <h6 class="card-title center bold">Datos del reporte de {{ $reporte->Tipo_reporte }}</h6>
                        <div class="user-detail-card justify" data-mh="card-one">
                            <p><span>Id :</span>{{ $reporte->Id_reporte_ml }}</p>
                            <p><span>Locación :</span>{{ $reporte->Nombre_locacion }}</p>
                            <p><span>Tipo de lugar :</span>{{ $reporte->tipoLocacion }}</p>
                            @if (!empty($lugar[0]->Nombre))
                                <p><span>Lugar especifico :</span>{{ $lugar[0]->Nombre }}</p>
                            @endif
                            <p><span>Descripcion :</span> {{ $reporte->Descripcion_Reporte }}</p>
                            <p><span>Encargado del lugar. :</span> {{ $reporte->Colonia }}</p>
                            <p><span>Estatus :</span> {{ $reporte->Estatus }}</p>
                        </div>
                        <hr>
                        <h6 class="card-title center bold">Tiempo y fecha de realizacion</h6>
                        <div class="user-detail-card justify" data-mh="card-one">
                            @if (!empty($reporte->Fecha_inicio))
                                <p><span>Hora de inicio :</span>
                                    {{ date_format(date_create($reporte->Fecha_inicio), 'h:i:s A') }}</p>
                            @endif
                            @if (!empty($reporte->Fecha_termino))
                                <p><span>Hora de termino :</span>
                                    {{ date_format(date_create($reporte->Fecha_termino), 'h:i:s A') }}</p>
                            @endif
                            @if (!empty($reporte->tiempoTotal))
                                <p><span>Tiempo total de trabajo :</span> {{ $reporte->tiempoTotal }}</p>
                            @endif
                            @if (!empty($reporte->Fecha_inicio))
                                <p><span>Fecha de inicio :</span>
                                    {{ date_format(date_create($reporte->Fecha_inicio), 'd/m/Y') }}</p>
                            @endif
                            @if (!empty($reporte->Fecha_termino))
                                <p><span>Fecha de termino :</span>
                                    {{ date_format(date_create($reporte->Fecha_termino), 'd/m/Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @for ($i = 0; $i < 2; $i++)
                        @if (!empty($fotos[$i]['Tipo']))
                            <div id="carouselExampleInterval{{$i}}" class="carousel slide" data-bs-ride="carousel">
                                @if ($fotos[$i]['Tipo'] == 'Antes')
                                    <h6 class="card-title center bold">Fotografias antes de hacer el trabajo</h6>
                                @else
                                    <h6 class="card-title center bold">Fotografias del trabajo terminado</h6>
                                @endif
                                <div class="carousel-inner gallery-container" id="gallery{{$i}}">
                                    @for ($i2 = 1; $i2 < 4; $i2++)
                                        @if (!empty($fotos[$i]["foto$i2"]))
                                            <div class="carousel-item {{$i2 == 1 ? 'active':''}}">
                                                <img src="{{ $fotos[$i]["foto$i2"] }}" width="800" height="400" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100 pointer"
                                                    alt="...">
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleInterval{{$i}}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleInterval{{$i}}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')    
    <script>
        $(document).ready(function () {
            new Viewer(document.getElementById('gallery0'));
            new Viewer(document.getElementById('gallery1'));
        });

        $lugar = @json($lugar);
        console.log($lugar);
        $reporte = @json($reporte);
        console.log($reporte);
    </script>
@endsection

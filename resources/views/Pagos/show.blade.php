@extends('layouts.menu_layout')
@section('title', 'DETALLE DE RENTA')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://fengyuanchen.github.io/viewerjs/css/viewer.css">
    <script src="https://fengyuanchen.github.io/viewerjs/js/viewer.js"></script>
    <style>
        img {
            width: 10%;
            cursor: zoom-in;
        }

        h6 {
            text-align: center;
            margin-top: 1em;
        }

        @media screen and (max-width: 600px) {
            img {
                width: 10%;
                margin-left: 25%;
                cursor: zoom-in;
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

            .form-switch .form-check-input {
                margin-left: 50%;
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

            .flex {
                display: table-cell;
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
            <div class="d-grid gap-2 d-md-block">
                <a class="btn btn-danger" style="position: fixed;" type="button" href="{{ route('pagos.index') }}">
                    <i class='bx bx-arrow-back'></i>
                </a>
            </div>
            <br>
            <h6 class="card-title center bold">Datos generales</h6>
            <div class="user-detail-card justify" data-mh="card-one">
                @if (!empty($cobros[0]->lugarEspecifico[0]["Nombre"]))
                    <p><span>Cliente :</span> {{ $cobros[0]->Title[0]->Nombre }}</p>
                    <p><span>Lugar :</span> {{ $cobros[0]->tipoLocacion.": ".$cobros[0]->lugarEspecifico[0]["Nombre"] }}</p>
                @endif
            </div>
            <hr>
            <h6 class="card-title center bold">Detalles del cobro</h6>
            <div class="user-detail-card justify" data-mh="card-one">
                <p><span>Tiempo predefinido de estancia :</span> {{ $cobros[0]->Periodo_total }}</p>
                <p><span>Personas extras :</span> {{ $cobros[0]->Cobro_persona_extra }}</p>
                <p><span>Resta de monto de anticipo :</span> {{ $cobros[0]->Monto_pagado_anticipo }}</p>
                <p><span>Deposito de garantia :</span> {{ $cobros[0]->Periodo_total }}</p>
                <p><span>Estado :</span> {{ $cobros[0]->Estado }}</p>
                <hr>
                <p><span>Monto total a cobrar :</span> {{ $cobros[0]->Monto_total }}</p>
                <p><span>Total pagado :</span> {{ $cobros[0]->Saldo }}</p>
            </div>

            <h6 class="card-title center bold">Pagos asociados</h6>
            <div class="card-body row">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Fecha</th>
                                <th>Comprobante</th>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <caption>
                            Pagos asociados
                        </caption>
                        <tbody>
                            @foreach ($pagos as $key => $pago)
                                @if ($pago->Estatus_pago == 'Pendiente')
                                    <tr class="table-warning">
                                    @elseif($pago->Estatus_pago == 'Cancelado')
                                    <tr class="table-danger">
                                    @elseif($pago->Estatus_pago == 'Confirmado')
                                    <tr class="table-success">
                                    @else
                                    <tr class="">
                                @endif
                                <td scope="row" data-label="Id">{{ $pago->Id_pago_renta }}</td>
                                <td data-label="Fecha">{{ $pago->Fecha_pago }}</td>
                                <td data-label="Comprobante">
                                    @if (!empty($pago->Foto_comprobante_pago))
                                        <div class="gallery-container" id="gallery{{ $key }}">
                                            <img src="{{ asset('uploads/pagos/' . $pago->Foto_comprobante_pago) }}"
                                                alt="..." class="img-thumbnail">
                                        </div>
                                    @else
                                        No encontrado
                                    @endif
                                </td>
                                <td data-label="Concepto" class="table-danger">{{ $pago->Concepto_pago_renta }}</td>
                                <td data-label="Monto" class="table-danger">{{ $pago->Monto_pago }}</td>
                                <td data-label="Estado" class="table-info">{{ $pago->Estatus_pago }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @foreach ($pagos as $key => $pago)
            @if (!empty($pago->Foto_comprobante_pago))
                new Viewer(document.getElementById('gallery{{ $key }}'));
            @endif
        @endforeach
    </script>
@endsection

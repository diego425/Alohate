@extends('layouts.menu_layout')
@section('title', 'COBROS DE RENTA')
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
        }

        @media screen and (max-width: 600px) {
            img {
                width: 50%;
                margin-left: 25%;
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
            <div class="card-body row">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="row">Id</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Comprobante</th>
                                <th scope="col">Monto</th>
                                <th scope="col" colspan="2">Accion</th>
                            </tr>
                        </thead>
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
                                <td data-label="Monto" class="table-danger">{{ $pago->Monto_pago }}</td>
                                @if ($pago->Estatus_pago == 'Pendiente')
                                    <td class="flex" data-label="Confirmar">
                                        <form
                                            action="{{ route('pagos.updatepago', [$pago->Id_pago_renta, $pago->Id_cobro_renta, 'Confirmar']) }}"
                                            method="post" class="formulario">
                                            @csrf
                                            <button class="btn btn-outline-success" type="submit">
                                                <i class='bx bx-check-double'></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="flex" data-label="Cancelar">
                                        <form
                                            action="{{ route('pagos.updatepago', [$pago->Id_pago_renta, $pago->Id_cobro_renta, 'Cancelar']) }}"
                                            method="post" class="formulario">
                                            @csrf
                                            <button class="btn btn-outline-danger" type="submit">
                                                <i class='bx bx-block'></i>
                                            </button>
                                        </form>
                                    </td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif
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

        $(".formulario").submit(function (e) { 
            e.preventDefault();
            Swal.fire({
                title: "¿Está seguro de continuar?",
                text: "No podra revertir los cambios.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, continua"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    // and when you done:
                    e.currentTarget.submit();
                } else {
                    Swal.fire("Cambios no guardados.", "", "info");
                }
            });
        });
    </script>
@endsection

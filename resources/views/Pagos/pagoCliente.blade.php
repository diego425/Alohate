<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/animations.min.css"
        integrity="sha512-GKHaATMc7acW6/GDGVyBhKV3rST+5rMjokVip0uTikmZHhdqFWC7fGBaq6+lf+DOS5BIO8eK6NcyBYUBCHUBXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css"
        integrity="sha512-cn16Qw8mzTBKpu08X0fwhTSv02kK/FojjNLz0bwp2xJ4H+yalwzXKFw/5cLzuBZCxGWIA+95X4skzvo8STNtSg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Realizar pago</title>
</head>
<style>
    img {
        width: 50%;
        margin-left: 25%;
    }

    table {
        font-size: .8em;
    }

    #mainPrinc{
        display: none;
    }

    .copiar{
        background-color: aquamarine;
        width: 2em;
        text-align: center;
        height: 2em;
    }

    @media screen and (max-width: 600px) {
        img {
            width: 50%;
            margin-left: 25%;
        }

        .agregar {
            width: 10%;
        }

        table td input {
            box-shadow: inset 0 0 0 32px c5e8ef !important;
            border-block: none;
            outline: none;
            border-color: inherit;
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

        #mainPrinc{
            font-size: .8em;
        }
    }
</style>

<body>
    <main class="main" id="mainPrinc">
        @if ($totalapagar <= 0)
            <section class="pt-5 pb-9">
                <div class="container-small container container-fluid">
                    <div class="alert alert-success alert-dismissible fade show d-flex" role="alert"
                        style="width: 80%; margin-left: 10%;">
                        <i class='bx bx-like bx-tada' style='color:#07e9f1'></i>
                        <div>
                            Sin pagos pendientes
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            @if (!empty($datosLugar[0]->Nombre_locacion))
                                <address>
                                    <strong>{{ $datosLugar[0]->Nombre_locacion }}</strong><br>
                                    {{ $datosLugar[0]->Nombre }}<br>
                                </address>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @else
            <section class="pt-5 pb-9">
                <div class="container-small container container-fluid">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show d-flex" role="alert"
                            style="width: 80%; margin-left: 10%;">
                            <i class='bx bx-message-check bx-tada'></i>
                            <div>
                                {{ session()->get('message') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @else
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show d-flex" role="alert"
                                style="width: 80%; margin-left: 10%;">
                                <i class='bx bxs-error-alt bx-flashing'></i>
                                <div>
                                    {{ session()->get('error') }}
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                    @endif
                    <div class="row justify-content-between">
                        <div class="col-lg-5 col-xl-4">
                            <div class="card mt-3 mt-lg-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h3 class="mb-0"><i class='bx bx-home-alt-2'></i></h3>
                                        @if (!empty($datosLugar[0]->Nombre_locacion))
                                            <address>
                                                <strong>{{ $datosLugar[0]->Nombre_locacion }}</strong><br>
                                                {{ $datosLugar[0]->Nombre }}<br>
                                            </address>
                                        @endif
                                    </div>
                                    <div class="border-dashed border-bottom mt-4">
                                        <div class="ms-n2">
                                            <div class="row align-items-center mb-2 g-3">
                                                <div class="col-2 col-md-5 col-lg-6">
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="fw-semi-bold text-1000 lh-base">Renta</h6>
                                                    </div>
                                                </div>
                                                <div class="col-2 col-md-2 col-lg-1">
                                                    <h6 class="fs--2 mb-0">x1</h6>
                                                </div>
                                                <div class="col-4 ps-0">
                                                    <span
                                                        class="mb-0 fw-semi-bold text-end">${{ $cobros[0]->Monto_total }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-dashed border-bottom mt-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <h6 class="text-900 fw-semi-bold">Subtotal </h6>
                                            <h6 class="text-900 fw-semi-bold">${{ $cobros[0]->Monto_total }}</h6>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <h6 class="text-900 fw-semi-bold">Pagado: </h6>
                                            <h6 class="text-danger fw-semi-bold">-${{ $cobros[0]->Saldo }}</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between border-dashed-y pt-3">
                                        <h5 class="mb-0">Total :</h5>
                                        <h5 class="mb-0">${{ $totalapagar }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-xl-7">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                            aria-expanded="false" aria-controls="flush-collapseOne"><i
                                                class='bx bxs-bank'></i>
                                            Transferencia
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <div class="registroPago border border-danger-subtle">
                                                <div class="table-responsive tablaCuentasBancarias">
                                                    <button class="btn btn-outline-dark center"
                                                        id="ocultarCuentasBancarias">
                                                        Ocultar cuentas
                                                        <i class='bx bx-low-vision'></i>
                                                    </button>
                                                    <table
                                                        class="table table-info table-striped table-hover table-borderless align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Banco</th>
                                                                <th>Titular</th>
                                                                <th>CLABE</th>
                                                                <th>Número de cuenta</th>
                                                                <th>Número de tarjeta</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-group-divider">
                                                            @foreach ($cuentas as $item)
                                                                @if ($item->tipo_cuenta)
                                                                    <tr>
                                                                        <td data-label="Banco">
                                                                            {{ $item->Nombre_Abreviado }}</td>
                                                                        <td data-label="Titular">{{ $item->Nombre }}
                                                                        </td>
                                                                        <td data-label="CLABE">
                                                                            <input type="text"
                                                                                id="CLABE{{ $item->id_cuenta }}"
                                                                                value="{{ $item->CLABE }}" readonly>
                                                                            <button class="btn btn-outline-info copiar"
                                                                                valor="{{ $item->CLABE }}"
                                                                                onclick="copiar('{{ $item->CLABE }}','CLABE{{ $item->id_cuenta }}')">
                                                                                <i class='bx bx-copy-alt'></i>
                                                                            </button>
                                                                        </td>
                                                                        <td data-label="Número de cuenta">
                                                                            <input type="text"
                                                                                id="cuenta{{ $item->id_cuenta }}"
                                                                                value="{{ $item->cuenta }}" readonly>
                                                                            <button class="btn btn-outline-info copiar"
                                                                                valor="{{ $item->cuenta }}"
                                                                                onclick="copiar('{{ $item->cuenta }}','cuenta{{ $item->id_cuenta }}')">
                                                                                <i class='bx bx-copy-alt'></i>
                                                                            </button>
                                                                        </td>
                                                                        <td data-label="Número de tarjeta">
                                                                            <input type="text"
                                                                                id="tarjeta{{ $item->id_cuenta }}"
                                                                                value="{{ $item->tarjeta }}" readonly>
                                                                            <button class="btn btn-outline-info copiar"
                                                                                valor="{{ $item->tarjeta }}"
                                                                                onclick="copiar('{{ $item->tarjeta }}','tarjeta{{ $item->id_cuenta }}')">
                                                                                <i class='bx bx-copy-alt'></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                {{-- <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <button type="button" class="btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                        <i class='bx bx-show-alt'></i> Mostrar Cuentas
                                                    </button>
                                                </div> --}}
                                                <h6 class="card-title center bold">Registrar pago del servicio</h6>
                                                <div class="user-detail-card justify" data-mh="card-one">
                                                    <form
                                                        action="{{ route('pagos.store', $cobros[0]->Id_cobro_renta) }}"
                                                        method="post" class="row g-3 needs-validation text-small small" novalidate>
                                                        @csrf
                                                        <div class="form-floating mb-3 col-md-6">
                                                            <input type="number" class="form-control"
                                                                name="Monto_pago" id="Monto_pago" placeholder=""
                                                                value="{{ $totalapagar }}" readonly required />
                                                            <label for="Monto_pago">Monto del pago</label>
                                                        </div>
                                                        <div class="form-floating mb-3 col-md-6">
                                                            <input type="date" class="form-control"
                                                                name="Fecha_pago" id="Fecha_pago" placeholder=""
                                                                value="{{ date('Y-m-d') }}" required />
                                                            <label for="Fecha_pago">Fecha de pago</label>
                                                        </div>
                                                        <div class="form-floating mb-3 col-md-4"
                                                            style="display: none">
                                                            <select class="form-select" id="Metodo_pago"
                                                                name="Metodo_pago"
                                                                aria-label="Floating label select example">
                                                                {{-- <option value="" selected disabled>Seleccione una opción</option> --}}
                                                                <option value="PUE">(PUE) Pago en una sola
                                                                    exhibición
                                                                </option>
                                                                {{-- <option value="PIP">(PIP) Pago inicial y en parcialidades</option>
                                                                <option value="PPD">(PPD) Pago en parcialidades o diferido</option> --}}
                                                            </select>
                                                            <label for="Metodo_pago">Metodo de pago</label>
                                                        </div>
                                                        <div class="mb-12 col-md-12">
                                                            <label for="c_FormaPago">Forma de pago</label>
                                                            <br>
                                                            <select
                                                                class="form-select js-example-basic-single js-example-responsive"
                                                                id="c_FormaPago" name="c_FormaPago"
                                                                aria-label="Floating label select example" required>
                                                                <option value="" selected disabled>Seleccione una
                                                                    opción</option>
                                                                @foreach ($formaPagos as $item)
                                                                    <option value="{{ $item->c_FormaPago }}">
                                                                        {{ $item->Descripcion }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-floating mb-3 col-md-12">
                                                            <input type="text" class="form-control"
                                                                name="Concepto_pago_renta" id="Concepto_pago_renta"
                                                                placeholder=""
                                                                value="Renta{{ $cobros[0]->Id_cobro_renta }}"
                                                                required />
                                                            <label for="Concepto_pago_renta">Concepto</label>
                                                        </div>
                                                        <h6 class="card-title center bold">Foto del comprobante de pago
                                                        </h6>
                                                        <div class="group-input col-mb-12">
                                                            <img src="..." class="" id="fotoComprobante"
                                                                alt="...">
                                                            <br>
                                                            <input type="file"
                                                                class="custom-file-input form-control" name="imagen"
                                                                id="inputfotoComprobante" foto="fotoComprobante"
                                                                onchange="revisarImagen(this);" accept="image/*"
                                                                required>
                                                            <input type="textarea" name="fotoComprobante"
                                                                id="valfotoComprobante" value="" hidden>
                                                            <button class="btn btn-danger" foto="fotoComprobante"
                                                                id="quitarfotoComprobante" type="reset"
                                                                onclick="quitarFoto(this)" style="display: none;">
                                                                <i class='bx bx-camera-off'></i>
                                                            </button>
                                                        </div>
                                                        <div class="group-input">
                                                            <button type="submit"
                                                                class="btn btn-outline-success">Registrar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                            aria-expanded="false" aria-controls="flush-collapseTwo">
                                            <i class='bx bx-money'></i> Efectivo
                                        </button>
                                    </h2>
                                    <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            Para pagos en efectivo acuda a recepción
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                            aria-expanded="false" aria-controls="flush-collapseThree">
                                            Otras formas de pago
                                        </button>
                                    </h2>
                                    <div id="flush-collapseThree" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body row">
                                            @if (empty($cobros[0]->preference_mp))
                                                <div class="col-sm-6 mt-3">
                                                    <form action="{{ route('mp.generarLinkPago') }}" method="post"
                                                        class="raw">
                                                        @csrf
                                                        <input type="hidden" value="SinEnvio" name="type">
                                                        <input type="hidden"
                                                            value="{{ $cobros[0]->Id_cobro_renta }}" name="IdRenta">
                                                        <input type="hidden" value="Habitacion 1 del Hotel Medrano"
                                                            name="title">
                                                        <button type="submit" class="btn btn-info">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="189"
                                                                height="24" viewBox="0 0 100 24" fill="none">
                                                                <path
                                                                    d="M16.7796 0.432983C7.68616 0.432983 0.312622 5.14842 0.312622 10.9666C0.312622 16.7848 7.68616 21.9569 16.7796 21.9569C25.8731 21.9569 33.2466 16.7848 33.2466 10.9666C33.2466 5.15253 25.8772 0.432983 16.7796 0.432983Z"
                                                                    fill="#00B1EA" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #008ebb;"></path>
                                                                <path
                                                                    d="M11.4223 7.71186C11.4141 7.72832 11.2536 7.8929 11.3565 8.02869C11.6116 8.35375 12.3975 8.53891 13.1917 8.36198C13.6649 8.255 14.2738 7.77358 14.8581 7.3045C15.4959 6.7984 16.1296 6.29229 16.7632 6.09067C17.438 5.87671 17.866 5.96723 18.154 6.05364C18.4667 6.14828 18.8329 6.35401 19.4213 6.79428C20.524 7.62545 24.9638 11.4974 25.7332 12.1681C26.3504 11.8883 29.2924 10.6991 33.0245 9.87618C32.6994 7.88879 31.502 5.99192 29.6669 4.5024C27.1076 5.57633 23.7747 6.21822 20.7174 4.72871C20.701 4.72459 19.0469 3.93869 17.4174 3.97983C14.9939 4.03744 13.9405 5.08668 12.8296 6.19765L11.4223 7.71186Z"
                                                                    fill="white" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #e8e6e3;"></path>
                                                                <path
                                                                    d="M25.5524 12.5918C25.4989 12.5466 20.335 8.02451 19.1623 7.14397C18.4834 6.63786 18.1089 6.50619 17.7139 6.45682C17.5082 6.43213 17.2243 6.46916 17.0226 6.52265C16.4795 6.67078 15.7676 7.14809 15.134 7.65008C14.4797 8.17264 13.8625 8.66229 13.2906 8.78985C12.5582 8.95444 11.6653 8.76105 11.2579 8.48536C11.0934 8.37426 10.9781 8.24671 10.9205 8.11504C10.7683 7.76529 11.0481 7.48549 11.0933 7.44023L12.5212 5.89722C12.6857 5.73263 12.8544 5.56804 13.0231 5.40346C12.5623 5.46518 12.1385 5.58039 11.7229 5.6956C11.2045 5.83961 10.7107 5.97951 10.2046 5.97951C9.99473 5.97951 8.87142 5.79435 8.65746 5.73675C7.36544 5.38288 5.90473 5.04136 4.21359 4.25134C2.18505 5.76143 0.864223 7.61305 0.473328 9.69097C0.765471 9.76915 1.5267 9.94196 1.72421 9.98722C6.31209 11.0077 7.73989 12.0569 7.99911 12.2791C8.27891 11.9664 8.68625 11.7689 9.13475 11.7689C9.64086 11.7689 10.1017 12.024 10.3774 12.419C10.6407 12.2133 11.0028 12.0322 11.4719 12.0322C11.6859 12.0322 11.9039 12.0734 12.1261 12.1474C12.6446 12.3244 12.912 12.67 13.0478 12.9827C13.2206 12.9045 13.4346 12.8469 13.6856 12.8469C13.9325 12.8469 14.1917 12.9045 14.4468 13.0156C15.2945 13.3777 15.4261 14.2089 15.3479 14.8343C15.4097 14.8261 15.4673 14.8261 15.529 14.8261C16.533 14.8261 17.3477 15.6408 17.3477 16.6448C17.3477 16.9534 17.2695 17.2455 17.1296 17.5048C17.4012 17.657 18.0966 18.0068 18.7096 17.9286C19.1952 17.8669 19.3844 17.7023 19.4503 17.6076C19.4955 17.5418 19.5449 17.4677 19.4997 17.4142L18.2035 15.9782C18.2035 15.9782 17.9896 15.7766 18.0595 15.6984C18.1336 15.6202 18.2653 15.7313 18.3558 15.8095C19.0141 16.3609 19.8165 17.1879 19.8165 17.1879C19.8288 17.1962 19.8823 17.3031 20.1827 17.3566C20.4378 17.4019 20.8904 17.3772 21.2073 17.118C21.2854 17.0521 21.3636 16.9699 21.4336 16.8876C21.4295 16.8917 21.4253 16.8958 21.4171 16.8999C21.7504 16.4761 21.3801 16.0482 21.3801 16.0482L19.87 14.3529C19.87 14.3529 19.6519 14.1513 19.726 14.0731C19.7918 14.0032 19.9317 14.1101 20.0264 14.1883C20.5037 14.5874 21.1826 15.2664 21.8286 15.9C21.9561 15.9947 22.524 16.3444 23.277 15.8507C23.7337 15.5503 23.8283 15.18 23.8119 14.9043C23.779 14.5381 23.4909 14.2747 23.4909 14.2747L21.4254 12.2009C21.4254 12.2009 21.2073 12.0158 21.2854 11.9211C21.3472 11.8429 21.4912 11.954 21.5817 12.0322C22.24 12.5836 24.0176 14.213 24.0176 14.213C24.0423 14.2295 24.6595 14.6697 25.4166 14.1842C25.6882 14.0114 25.861 13.7481 25.8774 13.4436C25.9268 12.9045 25.5524 12.5918 25.5524 12.5918Z"
                                                                    fill="white" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #e8e6e3;"></path>
                                                                <path
                                                                    d="M15.5409 15.2212C15.22 15.2171 14.8702 15.4064 14.825 15.3817C14.8003 15.3652 14.8455 15.2377 14.8743 15.1636C14.9073 15.0895 15.327 13.8222 14.2983 13.3819C13.5124 13.0445 13.031 13.4231 12.8664 13.5959C12.8252 13.6412 12.8047 13.637 12.8006 13.5794C12.7841 13.349 12.6812 12.7318 12.0023 12.5261C11.0312 12.2298 10.4099 12.9046 10.2494 13.1515C10.1795 12.6001 9.71041 12.1681 9.13847 12.1681C8.51715 12.1681 8.01105 12.6701 8.01105 13.2914C8.01105 13.9127 8.51304 14.4188 9.13847 14.4188C9.43884 14.4188 9.71453 14.2995 9.91615 14.1061C9.92438 14.1102 9.92438 14.1226 9.92027 14.1431C9.87089 14.4229 9.78449 15.4352 10.8461 15.8466C11.2699 16.0112 11.632 15.8878 11.9324 15.6779C12.0229 15.6162 12.0352 15.6409 12.0229 15.7273C11.9858 15.9865 12.0352 16.5461 12.8129 16.8588C13.4054 17.1016 13.7593 16.8547 13.9897 16.6408C14.0885 16.5502 14.1173 16.5626 14.1214 16.7066C14.1502 17.4596 14.7756 18.0562 15.5327 18.0603C16.3145 18.0603 16.9481 17.4267 16.9523 16.6449C16.9564 15.8549 16.3227 15.2294 15.5409 15.2212Z"
                                                                    fill="white" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #e8e6e3;"></path>
                                                                <path
                                                                    d="M16.7797 0.00231934C7.51341 0.00231934 0 4.93172 0 10.9762C0 11.1326 0 11.5646 0 11.6181C0 18.0329 6.56703 23.2256 16.7797 23.2256C27.0541 23.2256 33.5594 18.0329 33.5594 11.6181C33.5594 11.3959 33.5594 11.2642 33.5594 10.9762C33.5594 4.92761 26.046 0.00231934 16.7797 0.00231934ZM32.8023 9.75825C29.1567 10.5688 26.4369 11.7456 25.7538 12.0501C24.1615 10.6594 20.4829 7.46226 19.4872 6.71338C18.9193 6.28545 18.5284 6.05915 18.1869 5.95628C18.0347 5.91102 17.8207 5.85752 17.5491 5.85752C17.294 5.85752 17.0225 5.90279 16.7386 5.99331C16.0926 6.19904 15.4507 6.70515 14.8335 7.19891L14.8005 7.2236C14.2245 7.68445 13.6237 8.15764 13.1711 8.2605C12.9736 8.30577 12.772 8.32634 12.5704 8.32634C12.0643 8.32634 11.6075 8.17821 11.4388 7.96013C11.41 7.9231 11.4306 7.86549 11.4964 7.7832L11.5047 7.77086L12.9037 6.26077C14.0023 5.16214 15.0351 4.12935 17.4216 4.07175C17.4627 4.07175 17.4998 4.07175 17.5409 4.07175C19.0263 4.07175 20.5076 4.73833 20.6763 4.81239C22.0671 5.49132 23.5031 5.83695 24.9474 5.83695C26.4492 5.83695 28.0005 5.46663 29.6258 4.71776C31.3293 6.1579 32.4567 7.88195 32.8023 9.75825ZM16.7797 0.648325C21.6968 0.648325 26.0995 2.05966 29.0579 4.27748C27.626 4.89469 26.2641 5.2074 24.9474 5.2074C23.6019 5.2074 22.2564 4.88234 20.9479 4.24457C20.8779 4.21165 19.2403 3.43809 17.5368 3.43809C17.4916 3.43809 17.4463 3.43809 17.401 3.43809C15.3972 3.48335 14.2697 4.19519 13.5126 4.82062C12.7761 4.83708 12.1383 5.01813 11.5705 5.17449C11.0644 5.31439 10.6282 5.43783 10.2044 5.43783C10.0275 5.43783 9.71478 5.42137 9.68597 5.42137C9.19633 5.40491 6.74398 4.80416 4.78539 4.06352C7.73974 1.96914 12.019 0.648325 16.7797 0.648325ZM4.2052 4.50379C6.2502 5.33907 8.73547 5.9892 9.52138 6.03857C9.73946 6.05503 9.97401 6.07972 10.2085 6.07972C10.7311 6.07972 11.2496 5.9357 11.7516 5.79169C12.0478 5.7094 12.377 5.61887 12.7226 5.55304C12.6321 5.64356 12.5375 5.73408 12.4469 5.82872L11.0232 7.36762C10.9122 7.47871 10.6694 7.7832 10.8299 8.15352C10.8916 8.30165 11.0233 8.44567 11.2043 8.56911C11.5417 8.79541 12.1507 8.95177 12.7144 8.95177C12.9283 8.95177 13.13 8.9312 13.3151 8.89005C13.9118 8.75838 14.5372 8.25639 15.1997 7.72971C15.7264 7.31001 16.4793 6.77922 17.0513 6.62286C17.2118 6.5776 17.4092 6.55291 17.5697 6.55291C17.6191 6.55291 17.6602 6.55291 17.7014 6.56114C18.0799 6.61051 18.4462 6.73807 19.1045 7.22772C20.2731 8.10415 25.4411 12.6262 25.4905 12.6715C25.4946 12.6756 25.8238 12.9595 25.7991 13.4327C25.7868 13.696 25.6386 13.9305 25.3876 14.091C25.1654 14.2309 24.9391 14.3009 24.7087 14.3009C24.3631 14.3009 24.1244 14.1404 24.108 14.1281C24.0874 14.1116 22.3181 12.4904 21.668 11.9432C21.5651 11.8567 21.4622 11.7786 21.3635 11.7786C21.31 11.7786 21.2606 11.8033 21.2277 11.8403C21.1248 11.9678 21.24 12.1407 21.3758 12.2559L23.4455 14.3338C23.4496 14.3379 23.7047 14.5766 23.7335 14.8934C23.75 15.239 23.5854 15.5271 23.2439 15.7492C23.0011 15.9097 22.7542 15.992 22.5115 15.992C22.1946 15.992 21.9724 15.848 21.9231 15.811L21.6268 15.5188C21.0837 14.9839 20.5241 14.4325 20.1126 14.091C20.0139 14.0087 19.9069 13.9305 19.804 13.9305C19.7546 13.9305 19.7094 13.947 19.6723 13.9882C19.6271 14.0416 19.5941 14.1322 19.7094 14.2885C19.7546 14.3502 19.8122 14.4037 19.8122 14.4037L21.3223 16.099C21.3347 16.1155 21.635 16.4693 21.3552 16.8232L21.3017 16.889C21.2565 16.9384 21.2071 16.9837 21.1619 17.0248C20.9026 17.2346 20.5611 17.2593 20.4253 17.2593C20.3512 17.2593 20.2813 17.2552 20.2196 17.2429C20.0714 17.2182 19.9686 17.1729 19.9233 17.1194L19.9028 17.0989C19.8205 17.0125 19.0592 16.2389 18.4297 15.7122C18.3474 15.6423 18.2445 15.5559 18.1375 15.5559C18.0841 15.5559 18.0388 15.5764 18.0018 15.6176C17.8783 15.7534 18.0635 15.9591 18.1458 16.0332L19.4337 17.4527C19.4337 17.4651 19.4172 17.4939 19.3843 17.5391C19.339 17.6009 19.1827 17.7572 18.7136 17.8189C18.656 17.8272 18.5984 17.8313 18.5408 17.8313C18.0594 17.8313 17.545 17.5967 17.2817 17.4568C17.401 17.2017 17.4668 16.9219 17.4668 16.6421C17.4668 15.5805 16.6069 14.7206 15.5494 14.7206C15.5288 14.7206 15.5042 14.7206 15.4795 14.7206C15.5124 14.235 15.4466 13.3216 14.5043 12.9183C14.2327 12.8031 13.9653 12.7414 13.7019 12.7414C13.4962 12.7414 13.2987 12.7784 13.1135 12.8484C12.9201 12.4698 12.5951 12.1942 12.1754 12.0501C11.9408 11.9678 11.7104 11.9308 11.4841 11.9308C11.0932 11.9308 10.7311 12.046 10.4102 12.2764C10.1016 11.8938 9.6366 11.6675 9.14695 11.6675C8.71902 11.6675 8.30756 11.8403 7.99896 12.1407C7.59984 11.8362 6.00743 10.824 1.75284 9.85701C1.55122 9.81174 1.09039 9.68007 0.798251 9.59367C1.19326 7.68033 2.40708 5.9357 4.2052 4.50379ZM12.0972 15.5805L12.0519 15.5394H12.0067C11.9696 15.5394 11.9285 15.5559 11.875 15.5929C11.6569 15.7451 11.4512 15.8192 11.2372 15.8192C11.122 15.8192 11.0027 15.7945 10.8833 15.7492C9.89581 15.3666 9.97401 14.4325 10.0234 14.1527C10.0316 14.0951 10.0151 14.0499 9.98222 14.0211L9.91228 13.9635L9.84645 14.0252C9.65306 14.2104 9.40205 14.3132 9.13871 14.3132C8.575 14.3132 8.11416 13.8524 8.11416 13.2887C8.11416 12.7249 8.575 12.2641 9.13871 12.2641C9.64894 12.2641 10.0851 12.6468 10.1468 13.157L10.1839 13.4327L10.3361 13.1981C10.3526 13.1693 10.7681 12.5439 11.5293 12.5439C11.6734 12.5439 11.8215 12.5686 11.9737 12.6138C12.5786 12.799 12.6815 13.3504 12.6979 13.5767C12.7103 13.7084 12.8049 13.7166 12.8214 13.7166C12.8749 13.7166 12.9119 13.6837 12.9407 13.6549C13.0559 13.5355 13.3028 13.338 13.6937 13.338C13.8706 13.338 14.064 13.3792 14.2574 13.4656C15.2202 13.8812 14.7841 15.1032 14.78 15.1156C14.6977 15.3172 14.6935 15.4077 14.7717 15.4612L14.8088 15.4777H14.8376C14.8787 15.4777 14.9363 15.4612 15.0227 15.4283C15.1544 15.383 15.3519 15.3131 15.5371 15.3131C16.2612 15.3213 16.8538 15.9138 16.8538 16.6298C16.8538 17.354 16.2612 17.9424 15.5371 17.9424C14.8293 17.9424 14.2492 17.391 14.2245 16.6874C14.2245 16.6257 14.2162 16.4652 14.0805 16.4652C14.027 16.4652 13.9776 16.4981 13.92 16.5475C13.7636 16.6915 13.5661 16.8396 13.274 16.8396C13.1423 16.8396 12.9983 16.8108 12.8502 16.7491C12.1013 16.4446 12.0931 15.9303 12.1219 15.7246C12.1342 15.6834 12.1383 15.6258 12.0972 15.5805ZM16.7797 21.2917C7.86728 21.2917 0.64601 16.6709 0.64601 10.968C0.64601 10.7375 0.662454 10.5112 0.683028 10.2849C0.752977 10.3014 1.4607 10.4701 1.60883 10.503C5.95395 11.47 7.38998 12.4739 7.63275 12.6632C7.55045 12.8607 7.50931 13.0747 7.50931 13.2887C7.50931 14.1857 8.2376 14.9181 9.13871 14.9181C9.23747 14.9181 9.34035 14.9098 9.4391 14.8893C9.57489 15.5517 10.0069 16.0496 10.6694 16.3088C10.8628 16.3829 11.0562 16.4199 11.2496 16.4199C11.373 16.4199 11.4964 16.4035 11.6199 16.3747C11.7433 16.6833 12.0149 17.0701 12.6321 17.3211C12.846 17.4075 13.0641 17.4527 13.2699 17.4527C13.4386 17.4527 13.6073 17.4239 13.7677 17.3622C14.0599 18.0782 14.7635 18.5555 15.5412 18.5555C16.0596 18.5555 16.5534 18.3456 16.9155 17.9712C17.2241 18.144 17.8783 18.4567 18.5408 18.4567C18.6272 18.4567 18.7054 18.4526 18.7877 18.4403C19.4419 18.358 19.7505 18.0987 19.8904 17.9012C19.9151 17.8683 19.9398 17.8313 19.9603 17.7901C20.1167 17.8354 20.2854 17.8724 20.4829 17.8724C20.8409 17.8724 21.1865 17.749 21.5322 17.498C21.8737 17.2511 22.1165 16.8972 22.1535 16.5969C22.1535 16.5928 22.1535 16.5886 22.1535 16.5845C22.2687 16.6092 22.388 16.6216 22.5032 16.6216C22.8736 16.6216 23.2357 16.5063 23.5813 16.28C24.252 15.8398 24.3672 15.2678 24.3549 14.8934C24.4742 14.9181 24.5935 14.9304 24.7128 14.9304C25.0585 14.9304 25.4 14.8276 25.725 14.6177C26.1406 14.3544 26.3916 13.947 26.4287 13.4738C26.4533 13.1529 26.3752 12.8278 26.2065 12.548C27.3257 12.0666 29.885 11.1326 32.8969 10.4536C32.9093 10.6223 32.9175 10.7952 32.9175 10.968C32.9175 16.6709 25.6921 21.2917 16.7797 21.2917Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #24285f;"></path>
                                                                <path
                                                                    d="M91.0867 3.37496C90.4777 2.60963 89.5519 2.23108 88.3134 2.23108C87.0748 2.23108 86.149 2.61374 85.54 3.37496C84.9311 4.13618 84.6266 5.04141 84.6266 6.08243C84.6266 7.13991 84.9311 8.04514 85.54 8.79813C86.149 9.55111 87.0748 9.92555 88.3134 9.92555C89.556 9.92555 90.4777 9.547 91.0867 8.79813C91.6956 8.04514 92.0001 7.13991 92.0001 6.08243C92.0001 5.04141 91.6956 4.1403 91.0867 3.37496ZM89.5765 7.75711C89.2803 8.14801 88.8565 8.34551 88.3051 8.34551C87.7538 8.34551 87.3299 8.14801 87.0296 7.75711C86.7333 7.36621 86.5852 6.80662 86.5852 6.08243C86.5852 5.35824 86.7333 4.80276 87.0296 4.41187C87.3258 4.02097 87.7538 3.82758 88.3051 3.82758C88.8606 3.82758 89.2803 4.02508 89.5765 4.41187C89.8728 4.80276 90.0209 5.35824 90.0209 6.08243C90.0209 6.80662 89.8687 7.36621 89.5765 7.75711Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                                <path
                                                                    d="M75.5123 2.73714C74.8951 2.42442 74.1874 2.26807 73.3932 2.26807C72.1712 2.26807 71.3071 2.58901 70.8051 3.22267C70.4883 3.63003 70.3113 4.15259 70.2702 4.78626H72.0971C72.1424 4.50646 72.2329 4.28427 72.3646 4.11968C72.5538 3.89748 72.8748 3.78639 73.3274 3.78639C73.7306 3.78639 74.0392 3.84399 74.2491 3.95509C74.4548 4.06619 74.5618 4.27192 74.5618 4.56818C74.5618 4.81095 74.426 4.99199 74.1545 5.1072C74.0022 5.17304 73.7512 5.23064 73.3974 5.27179L72.7472 5.34997C72.0107 5.44461 71.4511 5.59685 71.0726 5.81493C70.3813 6.21406 70.0356 6.85595 70.0356 7.74884C70.0356 8.43599 70.2496 8.96678 70.6817 9.34122C71.1096 9.71566 71.6568 9.87202 72.3152 9.90493C76.4505 10.0901 76.4011 7.72826 76.4381 7.2345V4.51469C76.4381 3.64649 76.1295 3.05397 75.5123 2.73714ZM74.5495 6.86418C74.5371 7.49784 74.3561 7.934 74.0063 8.17265C73.6566 8.4113 73.278 8.53063 72.8624 8.53063C72.5991 8.53063 72.381 8.45656 72.1959 8.31255C72.0148 8.16853 71.9243 7.934 71.9243 7.60894C71.9243 7.24684 72.0724 6.97939 72.3728 6.80657C72.5497 6.7037 72.8377 6.61729 73.2451 6.54734L73.6772 6.46917C73.8911 6.42802 74.0598 6.38276 74.1833 6.3375C74.3067 6.29223 74.4301 6.23051 74.5495 6.15645V6.86418Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                                <path
                                                                    d="M66.0857 3.8852C66.5589 3.8852 66.9045 4.03332 67.1308 4.32135C67.283 4.53532 67.3818 4.7822 67.4188 5.04965H69.4556C69.3445 4.02098 68.9824 3.30091 68.3776 2.89767C67.7686 2.49443 66.9868 2.2887 66.0322 2.2887C64.9089 2.2887 64.0283 2.63433 63.3906 3.32148C62.7528 4.01275 62.4318 4.97559 62.4318 6.21411C62.4318 7.31274 62.7198 8.20562 63.3 8.89278C63.8761 9.57993 64.7813 9.92556 66.0034 9.92556C67.2296 9.92556 68.1554 9.51409 68.7808 8.68704C69.1717 8.17682 69.3898 7.63368 69.4391 7.05762H67.4065C67.3654 7.43618 67.246 7.74889 67.0485 7.98754C66.851 8.2262 66.5218 8.34552 66.0569 8.34552C65.3985 8.34552 64.95 8.04515 64.7114 7.4444C64.5797 7.12346 64.5139 6.69965 64.5139 6.16885C64.5139 5.61337 64.5797 5.16898 64.7114 4.83158C64.9541 4.20203 65.415 3.8852 66.0857 3.8852Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                                <path
                                                                    d="M61.8848 2.29285C57.696 2.29285 57.9429 6.00018 57.9429 6.00018V9.76513H59.8439V6.23061C59.8439 5.65043 59.918 5.22251 60.062 4.94271C60.3212 4.44895 60.8314 4.20206 61.5927 4.20206C61.6503 4.20206 61.7243 4.20618 61.819 4.21029C61.9136 4.21441 62.0165 4.22264 62.1399 4.2391V2.30519C62.0576 2.29696 62 2.29696 61.9753 2.29285C61.9506 2.29285 61.9218 2.29285 61.8848 2.29285Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                                <path
                                                                    d="M56.4204 3.69592C56.0912 3.21039 55.6757 2.85241 55.1737 2.6261C54.6717 2.40391 54.1038 2.2887 53.4784 2.2887C52.4209 2.2887 51.561 2.62199 50.8944 3.28857C50.2319 3.95515 49.8986 4.91387 49.8986 6.16473C49.8986 7.50201 50.2648 8.46485 51.0014 9.05736C51.7338 9.64988 52.5855 9.94614 53.5443 9.94614C54.7087 9.94614 55.6139 9.59639 56.2599 8.89278C56.6097 8.52245 56.8278 8.15625 56.9183 7.79827H54.9021C54.8239 7.91348 54.7375 8.00812 54.6429 8.0863C54.3754 8.30849 54.0092 8.37433 53.5813 8.37433C53.1739 8.37433 52.8571 8.3126 52.5691 8.13156C52.0959 7.83942 51.8284 7.34565 51.7996 6.62147H57.0582C57.0664 5.99603 57.0458 5.51873 56.9965 5.18955C56.9059 4.62173 56.7167 4.12385 56.4204 3.69592ZM51.8449 5.37471C51.9107 4.90564 52.0835 4.5312 52.3469 4.25552C52.6143 3.97983 52.9888 3.84405 53.4743 3.84405C53.9187 3.84405 54.2931 3.9716 54.5935 4.23495C54.8939 4.49417 55.0626 4.87684 55.0955 5.37471H51.8449V5.37471Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                                <path
                                                                    d="M46.1215 2.26807C45.2409 2.26807 44.4756 2.65485 43.9654 3.27617C43.4798 2.65485 42.7269 2.26807 41.8052 2.26807C39.9494 2.26807 38.7521 3.63003 38.7521 5.43638V9.76503H40.4885V5.39523C40.4885 4.59698 41.0275 4.04561 41.8052 4.04561C42.9491 4.04561 43.0684 4.99611 43.0684 5.39523V9.76503H44.8048V5.39523C44.8048 4.59698 45.3561 4.04561 46.1215 4.04561C47.2654 4.04561 47.4011 4.99611 47.4011 5.39523V9.76503H49.1376V5.43638C49.1334 3.57242 48.0183 2.26807 46.1215 2.26807Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                                <path
                                                                    d="M82.1229 1.34232L82.1187 3.37909C81.9089 3.03757 81.6332 2.77011 81.2917 2.58084C80.9543 2.38745 80.5634 2.29281 80.1272 2.29281C79.1767 2.29281 78.4196 2.64667 77.8518 3.35029C77.284 4.05801 77.0001 5.07434 77.0001 6.30875C77.0001 7.37857 77.2881 8.255 77.8641 8.93804C78.4402 9.61696 79.5717 9.91733 80.5757 9.91733C84.0773 9.91733 84.0362 6.91772 84.0362 6.91772V0.00915687C84.0362 0.00915687 82.1229 -0.196577 82.1229 1.34232ZM81.7566 7.77358C81.481 8.1727 81.0695 8.37021 80.5387 8.37021C80.0038 8.37021 79.6047 8.16859 79.3413 7.76947C79.078 7.37034 78.9463 6.78605 78.9463 6.11947C78.9463 5.50227 79.0739 4.98382 79.3331 4.56412C79.5923 4.14854 79.9956 3.93869 80.5469 3.93869C80.909 3.93869 81.2259 4.0539 81.5015 4.28432C81.9459 4.66287 82.1722 5.34591 82.1722 6.23057C82.1763 6.86012 82.0364 7.37445 81.7566 7.77358Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                                <path
                                                                    d="M42.7681 12.6701C41.2046 12.596 40.4104 12.9704 39.8961 13.3613C39.1842 13.9045 38.7522 14.7068 38.7522 15.9906V22.5988H39.6739C39.9208 22.5988 40.1677 22.5124 40.3487 22.3478C40.5503 22.1586 40.6532 21.9322 40.6532 21.6648V19.196C40.8754 19.5828 41.1716 19.8667 41.5461 20.0518C41.8999 20.2164 42.3073 20.2987 42.7764 20.2987C43.6528 20.2987 44.3688 19.949 44.9284 19.2495C45.488 18.5294 45.7677 17.5954 45.7677 16.4391C45.7677 15.3158 45.5044 14.4559 44.8707 13.6864C44.3523 13.061 43.5746 12.7071 42.7681 12.6701ZM43.4141 18.0891C43.1343 18.4759 42.7517 18.6693 42.2703 18.6693C41.7477 18.6693 41.3486 18.4759 41.0688 18.0891C40.789 17.7394 40.6491 17.2168 40.6491 16.5173C40.6491 15.7643 40.7807 15.213 41.04 14.8632C41.3198 14.4764 41.7106 14.283 42.2126 14.283C42.7352 14.283 43.1343 14.4764 43.4141 14.8632C43.6939 15.25 43.8338 15.8014 43.8338 16.5173C43.8338 17.1839 43.6939 17.7065 43.4141 18.0891Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                                <path
                                                                    d="M59.091 13.353C58.4449 12.8634 57.7866 12.6083 56.6509 12.6371C55.4988 12.67 54.6594 12.9909 54.141 13.6987C53.6184 14.4064 53.3592 15.3281 53.3592 16.4637C53.3592 17.4389 53.5567 18.2207 53.9476 18.8215C54.3426 19.4181 54.8117 19.8255 55.363 20.0435C55.9103 20.2657 56.4617 20.311 57.0213 20.1834C57.5809 20.0559 58.0211 19.7349 58.3503 19.2206V19.6856C58.3133 20.274 58.1734 20.7142 57.9265 21.0105C57.6796 21.3026 57.4039 21.4837 57.1036 21.5454C56.8032 21.6071 56.4987 21.5742 56.1983 21.4343C55.8938 21.2985 55.6716 21.101 55.5276 20.8418H53.5238C54.0422 22.4012 54.9722 23.0884 56.6509 23.2118C59.3584 23.4052 60.2184 21.1133 60.2184 19.8789V15.9947C60.2184 14.7109 59.7987 13.888 59.091 13.353ZM58.2968 17.1674C58.2228 17.5994 58.1034 17.9162 57.9388 18.1179C57.5932 18.5952 57.0501 18.7639 56.3218 18.6281C55.5894 18.4882 55.2231 17.7887 55.2231 16.5214C55.2231 15.933 55.3301 15.4104 55.5523 14.9495C55.7704 14.4928 56.1901 14.2624 56.8155 14.2624C57.2722 14.2624 57.622 14.427 57.8565 14.7562C58.0952 15.0853 58.2474 15.4639 58.3092 15.8877C58.375 16.3074 58.3709 16.7353 58.2968 17.1674Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                                <path
                                                                    d="M51.7337 13.1597C51.1165 12.847 50.4088 12.6907 49.6146 12.6907C48.3926 12.6907 47.5285 13.0075 47.0265 13.6453C46.7097 14.0526 46.5327 14.5752 46.4916 15.2048H48.3185C48.3638 14.925 48.4543 14.7028 48.586 14.5423C48.7752 14.3201 49.0962 14.209 49.5488 14.209C49.9562 14.209 50.2606 14.2666 50.4705 14.3777C50.6762 14.4888 50.7832 14.6945 50.7832 14.9908C50.7832 15.2336 50.6474 15.4146 50.3759 15.5298C50.2236 15.5956 49.9726 15.6491 49.6187 15.6944L48.9727 15.7726C48.2362 15.8672 47.6766 16.0236 47.2981 16.2375C46.6068 16.6367 46.2612 17.2786 46.2612 18.1714C46.2612 18.8586 46.4751 19.3894 46.9072 19.7638C47.3351 20.1383 47.8823 20.2946 48.5407 20.3275C52.6718 20.5127 52.6266 18.1509 52.6636 17.6571V14.9373C52.6595 14.0691 52.3509 13.4766 51.7337 13.1597ZM50.7709 17.2868C50.7585 17.9204 50.5775 18.3566 50.2277 18.5953C49.878 18.8339 49.4994 18.9532 49.0838 18.9532C48.8205 18.9532 48.6024 18.8792 48.4173 18.7352C48.2321 18.5911 48.1457 18.3566 48.1457 18.0315C48.1457 17.6695 48.2938 17.402 48.5942 17.2292C48.7711 17.1263 49.0591 17.0399 49.4624 16.97L49.8944 16.8918C50.1084 16.8506 50.2771 16.8095 50.4005 16.7601C50.524 16.7148 50.6474 16.6531 50.7668 16.5791V17.2868H50.7709Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                                <path
                                                                    d="M67.4069 13.7605C66.798 12.9951 65.8722 12.6166 64.6295 12.6166C63.391 12.6166 62.4652 12.9992 61.8562 13.7605C61.2472 14.5217 60.9427 15.4228 60.9427 16.4638C60.9427 17.5213 61.2472 18.4265 61.8562 19.1795C62.4652 19.9284 63.391 20.3069 64.6295 20.3069C65.8722 20.3069 66.7938 19.9284 67.4069 19.1795C68.0159 18.4265 68.3204 17.5213 68.3204 16.4638C68.3163 15.4228 68.0159 14.5217 67.4069 13.7605ZM65.8968 18.1385C65.6006 18.5294 65.1768 18.7269 64.6254 18.7269C64.074 18.7269 63.6461 18.5294 63.3498 18.1385C63.0495 17.7476 62.9013 17.188 62.9013 16.4679C62.9013 15.7437 63.0495 15.1883 63.3498 14.7974C63.6461 14.4065 64.074 14.2131 64.6254 14.2131C65.1768 14.2131 65.6006 14.4065 65.8968 14.7974C66.1931 15.1883 66.3412 15.7437 66.3412 16.4679C66.3371 17.188 66.189 17.7476 65.8968 18.1385Z"
                                                                    fill="#2D3277" data-darkreader-inline-fill=""
                                                                    style="--darkreader-inline-fill: #93add6;"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <div id="wallet_container"></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-fullscreen-lg-down modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Números de cuenta para hacer
                                        sus pagos.</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"
    integrity="sha256-+0Qf8IHMJWuYlZ2lQDBrF1+2aigIRZXEdSvegtELo2I=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ url('js/pagoclientedirecto.js')}}"></script>
<script>
    $(document).ready(function() {
        $("#mainPrinc").hide();
        @if ($totalapagar <= 0)
            datosLugar = @json($datosLugar);
            console.log(datosLugar);
        @else
            @if (!empty($cobros[0]->preference_mp))
                @if (!empty($keyMP))
                    const mp = new MercadoPago('{{ $keyMP }}', {
                        locale: 'es-MX'
                    });

                    mp.bricks().create("wallet", "wallet_container", {
                        initialization: {
                            preferenceId: "{{ $cobros[0]->preference_mp }}",
                        },
                    });
                @endif
            @endif

            Swal.fire({
                title: "Ingresa tu teléfono",
                text: "*******{{substr($cobros[0]->Title[0]->Numero_celular,7)}}",
                input: "text",
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Confirmar",
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                showCancelButton: false,
                allowEscapeKey: false,
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    verificarTelefono(result.value,"{{$cobros[0]->Id_cobro_renta}}", "{{route('pagos.verificarTelefono')}}");
                }
            });

            $('.js-example-basic-single').select2();
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
            })()
        @endif
    });    
</script>

</html>

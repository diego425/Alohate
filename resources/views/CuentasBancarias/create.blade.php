@extends('layouts.menu_layout')
@section('title', 'REGISTRAR CUENTA BANCARIA')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        @media screen and (max-width: 600px) {
            /* .js-example-responsive{
                        width: 50%;
                    } */

            img {
                width: 50%;
                margin-left: 25%;
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
                margin-left: 63%;
                font-size: small;
            }

            .cancelar {
                margin-left: 0%;
                font-size: small;
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
                <br>
                <br>
                <form class="row g-3 needs-validation" action="{{ route('cuentas.store') }}" method="post" novalidate>
                    @csrf
                    <div class="col-md-4">
                        <label for="Id_Banco" class="form-label">Banco</label>
                        <select class="form-select js-example-responsive" id="Id_Banco" name="Id_Banco" required>
                            <option selected disabled value="">Elija una opción...</option>
                            @foreach ($bancos as $item)
                                <option value="{{ $item->Id_Banco }}" title="{{ $item->Nombre_Institucion }}"
                                    {{ old('Id_Banco') == $item->Id_Banco ? 'selected' : '' }}>
                                    {{ $item->Nombre_Abreviado }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid bank.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="tipo_cuenta" class="form-label">Tipo de cuenta</label>
                        <select class="form-select" id="tipo_cuenta" name="tipo_cuenta" required>
                            <option selected disabled value="">Elija una opción...</option>
                            <option value="Transferencia" {{ old('tipo_cuenta') == 'Transferencia' ? 'selected' : '' }}>Para
                                transferencia</option>
                            <option value="PagoOxxo" {{ old('tipo_cuenta') == 'PagoOxxo' ? 'selected' : '' }}>Pago en oxxo
                            </option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid type.
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="Nombre" class="form-label">Nombre del titular</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre" value="{{ old('Nombre') }}"
                            required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="CLABE" class="form-label">CLABE Interbancaria</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">#</span>
                            <input type="number" class="form-control" id="CLABE" name="CLABE"
                                value="{{ old('CLABE') }}" aria-describedby="inputGroupPrepend" required>
                            <div class="invalid-feedback">
                                Requerido.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="cuenta" class="form-label">Número de cuenta</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">#</span>
                            <input type="number" class="form-control" id="cuenta" name="cuenta"
                                value="{{ old('cuenta') }}" aria-describedby="inputGroupPrepend">
                            <div class="invalid-feedback">
                                Requerido.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="tarjeta" class="form-label">Número de tarjeta</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">#</span>
                            <input type="number" class="form-control" id="tarjeta" name="tarjeta"
                                value="{{ old('tarjeta') }}" aria-describedby="inputGroupPrepend">
                            <div class="invalid-feedback">
                                Requerido.
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Registrar</button>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table table-primary">
                        <thead>
                            <tr>
                                <th scope="col">Banco</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">CLABE</th>
                                <th scope="col">Tipo cuenta</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cuentas as $item)
                                <tr class="">
                                    <td>{{ $item->Nombre_Abreviado }}</td>
                                    <td>{{ $item->Nombre }}</td>
                                    <td>{{ $item->CLABE }}</td>
                                    <td>{{ $item->tipo_cuenta }}</td>
                                    <td>
                                        <form action="{{ route('cuentas.destroy', $item->id_cuenta) }}" method="post"
                                            id="eliminarCuenta">
                                            @csrf
                                            <button class="btn btn-danger" title="Eliminar registro">
                                                <i class='bx bxs-trash'></i>
                                            </button>
                                        </form>
                                    </td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js" integrity="sha256-+0Qf8IHMJWuYlZ2lQDBrF1+2aigIRZXEdSvegtELo2I=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            cuentas = @json($cuentas);
            console.log(cuentas);

            $('#Id_Banco').select2();

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

            $("#eliminarCuenta").submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: "¿Esta seguro de desactivar esta cuenta?",
                    text: "¡No podra revertir los cambios!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Si, hazlo!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        e.currentTarget.submit();
                    }
                });
            });
        });
    </script>
@endsection

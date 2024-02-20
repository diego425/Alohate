@extends('layouts.menu_layout')
@section('title', 'CREAR CAJA CHICA')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2 d-md-block">
                    <a class="btn btn-danger" style="position: fixed;" type="button" href="{{ route('caja.index') }}">
                        <i class='bx bx-arrow-back'></i>
                    </a>
                </div>
                <br><br>
                <form class="row g-3 needs-validation" action="{{ route('caja.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-10 col-xl-12 col-sm-6">
                        <label for="Id_locacion" class="form-label">Locacion (opcional)</label>
                        <select class="form-select" id="Id_locacion" name="Id_locacion">
                            <option selected disabled value="">Elija una opción</option>
                            @foreach ($locaciones as $item)
                                <option value="$item->Id_locacion">{{ strtoupper($item->Nombre_locacion) }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Campo obligatorio.
                        </div>
                    </div>
                    <div class="col-md-10 col-xl-12 col-sm-6">
                        <label for="Id_colaborador" class="form-label">Encargado</label>
                        <select class="form-select" id="Id_colaborador" name="Id_colaborador" required>
                            <option selected disabled value="">Elija una opción</option>
                            @foreach ($colaboradores as $item)
                                <option value="{{$item->Id_colaborador}}">
                                    {{ strtoupper($item->Nombre . ' ' . $item->Apellido_pat . ' ' . $item->Apellido_mat) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Campo obligatorio.
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                    </div>
                </form>
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
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
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
    </script>
@endsection

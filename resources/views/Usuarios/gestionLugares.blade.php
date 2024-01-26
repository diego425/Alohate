@extends('layouts.menu_layout')
@section('title', 'Gestion de lugares')
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
    <div class="container small text-uppercase">
        <h6>Gestion de lugares de los cuales esta a cargo {{ $user->Nombre }}</h6>
        <div class="card">
            <div class="input-group">
                <div class="col-sm-1 mt-4 mb-3">
                    <a class="btn btn-danger" type="button" href="{{ route('user.index') }}">
                        <i class='bx bx-arrow-back'></i>
                    </a>
                </div>
                <div class="col-sm-1 mt-4 mb-3">
                    <button type="button" class="btn btn-small btn-primary small btnFiltro" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <i class='bx bx-filter-alt'></i>
                        Filtros
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="overflow-auto">
                    <table class="table table-hover table-striped" id="tablaLugares">
                        <thead>
                            <tr>
                                <th class="numerosTabla" scope="col">#</th>
                                <th scope="col">Locación</th>
                                <th scope="col">Lugar</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Colaborador</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            <!-- {{ $i = 1 }}-->
                            @foreach ($locaciones as $locacion)
                                @if ($locacion->mostrar == 'mostrar')
                                    @if ($locacion->tipoLocacion == 'Entera')
                                        <tr class="{{ $locacion->tipoLocacion }} table-warning">
                                        @else
                                        <tr class="{{ $locacion->tipoLocacion }}">
                                    @endif
                                    <th class="numerosTabla" data-label="#"><strong>{{ $i++ }}</strong></th>
                                    <td data-label="Nombre de locación:">{{ $locacion->nombreLocacion }}</td>
                                    <td data-label="Nombre del lugar:">{{ $locacion->Nombre }}</td>
                                    <td data-label="Tipo de lugar:"><strong>{{ $locacion->tipoLocacion }}</strong></td>
                                    <td data-label="Estado del lugar:">{{ $locacion->Nombre_estado }}</td>
                                    <td data-label="Colaborador:">
                                        {{ $locacion->NombreCola . ' ' . $locacion->Apellido_pat }}</td>
                                    <td data-label="Seleccionar">
                                        @if (!empty($locacion->Id_colaborador))
                                            @if ($locacion->Id_colaborador == $user->Id_colaborador)
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input checkSeleccionar" type="checkbox"
                                                        role="switch" igual="Si"
                                                        colaborador="{{ $locacion->NombreCola . ' ' . $locacion->Apellido_pat }}"
                                                        id="{{ $locacion->tipoLocacion }}{{ $locacion->id }}"
                                                        tipo="{{ $locacion->tipoLocacion }}"
                                                        id-lugar="{{ $locacion->id }}"
                                                        locacion="{{ $locacion->nombreLocacion }}"
                                                        lugar="{{ $locacion->Nombre }}" checked>
                                                </div>
                                            @else
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input checkSeleccionar" type="checkbox"
                                                        role="switch" igual="No"
                                                        colaborador="{{ $locacion->NombreCola . ' ' . $locacion->Apellido_pat }}"
                                                        id="{{ $locacion->tipoLocacion }}{{ $locacion->id }}"
                                                        tipo="{{ $locacion->tipoLocacion }}"
                                                        id-lugar="{{ $locacion->id }}"
                                                        locacion="{{ $locacion->nombreLocacion }}"
                                                        lugar="{{ $locacion->Nombre }}">
                                                </div>
                                            @endif
                                        @elseif ($locacion->Id_colaborador == $user->Id_colaborador)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input checkSeleccionar" type="checkbox"
                                                    role="switch" igual="Si"
                                                    colaborador="{{ $locacion->NombreCola . ' ' . $locacion->Apellido_pat }}"
                                                    id="{{ $locacion->tipoLocacion }}{{ $locacion->id }}"
                                                    tipo="{{ $locacion->tipoLocacion }}" id-lugar="{{ $locacion->id }}"
                                                    locacion="{{ $locacion->nombreLocacion }}"
                                                    lugar="{{ $locacion->Nombre }}" checked>
                                            </div>
                                        @else
                                            <div class="form-check form-switch">
                                                <input class="form-check-input checkSeleccionar" type="checkbox"
                                                    role="switch" igual="Si"
                                                    colaborador="{{ $locacion->NombreCola . ' ' . $locacion->Apellido_pat }}"
                                                    id="{{ $locacion->tipoLocacion }}{{ $locacion->id }}"
                                                    tipo="{{ $locacion->tipoLocacion }}" id-lugar="{{ $locacion->id }}"
                                                    locacion="{{ $locacion->nombreLocacion }}"
                                                    lugar="{{ $locacion->Nombre }}">
                                            </div>
                                        @endif
                                    </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
                            <form class="row col-lg-auto mb-sm-0 me-sm-3" action="#" method="get" role="search">
                                <div class="col-sm-3">
                                    <label for="locacion" class="form-label">Locación</label>
                                    <select class="form-select" id="locacion" name="locacion">
                                        <option selected disabled value="">Elija...</option>
                                        @foreach ($locaciones as $locacion)
                                            @if ($locacion->tipoLocacion == 'Entera')
                                                <option value="{{ $locacion->id }}" {{ $locacion->id == old('locacion') ? 'selected' : '' }}>{{ $locacion->Nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione una locación.
                                    </div>
                                </div>
                                <div class="input-group mb-3 col">
                                    <div class="col-sm-3">
                                        <label for="tipoLugar" class="form-label">Tipo de lugar</label>
                                        <select class="form-select" id="tipoLugar" name="tipoLugar">
                                            <option selected disabled value="">Elija...</option>
                                            @foreach ($tipos as $tipo)
                                                <option {{ $tipo->tipoLocacion == old('tipoLugar') ? 'selected' : '' }} value="{{ $tipo->tipoLocacion }}">
                                                    {{ $tipo->tipoLocacion }}
                                                    ({{ $tipo->total }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Seleccione un lugar.
                                        </div>
                                    </div>

                                    <div class="col-sm-1 mt-4 mb-3" style="margin-left: 1em;">
                                        <button type="submit" class="btn btn-danger">
                                            <i class='bx bx-search-alt'></i>
                                        </button>
                                    </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <a type="button" class="btn btn-primary" href="{{route('user.gestionLugares',$user->Id_colaborador)}}">Reset</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const locaciones = @json($locaciones);
        console.log(locaciones);

        $(document).ready(function() {
            var table = $('#tablaLugares').DataTable();

            function myFunction(x) {
                if (x.matches) { // If media query matches
                    table.destroy();
                    table = $('#tablaLugares').DataTable({
                        "order": [
                            [0, "asc"]
                        ],
                        responsive: true,
                        scrollCollapse: true,
                        scrollY: '37em',
                        paginate: false,
                        "lengthMenu": [5, 10, 15],
                        "language": {
                            "lengthMenu": "Mostrar _MENU_ registros por pagina",
                            "zeroRecords": "Sin datos",
                            "info": "Mostrando pagina _PAGE_ de _PAGES_",
                            "infoEmpty": "No records available",
                            "infoFiltered": "(filtrado de _MAX_ total registros)",
                            'search': 'Buscar',
                            'paginate': {
                                'next': 'Siguiente',
                                'previous': 'Anterior'
                            }
                        }
                    });
                } else {
                    table.destroy();
                    table = $('#tablaLugares').DataTable({
                        "order": [
                            [0, "asc"]
                        ],
                        responsive: true,
                        stateSave: true,
                        scrollCollapse: true,
                        scrollY: '30em',
                        "lengthMenu": [5, 10, 15],
                        "language": {
                            "lengthMenu": "Mostrar _MENU_ registros por pagina",
                            "zeroRecords": "Sin datos",
                            "info": "Mostrando pagina _PAGE_ de _PAGES_",
                            "infoEmpty": "No records available",
                            "infoFiltered": "(filtrado de _MAX_ total registros)",
                            'search': 'Buscar',
                            'paginate': {
                                'next': 'Siguiente',
                                'previous': 'Anterior'
                            }
                        }
                    });
                }
            }

            // Create a MediaQueryList object
            var x = window.matchMedia("(max-width: 600px)")

            // Call listener function at run time
            myFunction(x);

            // Attach listener function on state changes
            x.addEventListener("change", function() {
                myFunction(x);
            });
        });

        $(".checkSeleccionar").click(function(e) {
            id = $(this).attr("id-lugar");
            tipo = $(this).attr("tipo");
            locacion = $(this).attr("locacion");
            lugar = $(this).attr("lugar");
            igual = $(this).attr("igual");
            check = $(this).prop('checked');
            Id_colaborador = "{{ $user->Id_colaborador }}";
            console.log(tipo + " " + id + " " + check);

            if (igual == "No") {
                Swal.fire({
                    title: "El lugar ya esta asignado",
                    text: "Si continua se quita el lugar a " + $(this).attr("colaborador") +
                        " y pasa a {{ $user->Nombre . ' ' . $user->Apellido_pat }}.",
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: "Continuar",
                    cancelButtonText: `Cancelar`
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (check === true) {
                            if (tipo == "Entera") {
                                Swal.fire({
                                    title: "Esta seguro de asignar este lugar?",
                                    text: "Es un lugar completo se asignarán habitaciones, locales o departamentos relacionados.",
                                    showDenyButton: false,
                                    showCancelButton: true,
                                    confirmButtonText: "Continuar",
                                    cancelButtonText: `Cancelar`
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        cargando();
                                        $("#" + tipo + id).prop('checked', true);
                                        mandarDatos(id, tipo, "true", Id_colaborador, locacion,
                                            lugar);
                                    } else {
                                        cargando();
                                        $("#" + tipo + id).removeAttr('checked');
                                        $("#" + tipo + id).prop('checked', false);
                                        alertaInfo("Cambios no guardados");
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Esta seguro de asignar este lugar?",
                                    showDenyButton: false,
                                    showCancelButton: true,
                                    confirmButtonText: "Continuar",
                                    cancelButtonText: `Cancelar`
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $("#" + tipo + id).prop('checked', true);
                                        mandarDatos(id, tipo, "true", Id_colaborador, locacion,
                                            lugar);
                                    } else {
                                        $("#" + tipo + id).removeAttr('checked');
                                        $("#" + tipo + id).prop('checked', false);
                                        alertaInfo("Cambios no guardados");
                                    }
                                });
                            }
                        } else {
                            if (tipo == "Entera") {
                                Swal.fire({
                                    title: "Esta seguro de quitar este lugar?",
                                    text: "Es un lugar completo se quitaran habitaciones, locales o departamentos relacionados.",
                                    showDenyButton: false,
                                    showCancelButton: true,
                                    confirmButtonText: "Continuar",
                                    cancelButtonText: `Cancelar`
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        cargando();
                                        $("#" + tipo + id).prop('checked', true);
                                        mandarDatos(id, tipo, check, Id_colaborador, locacion,
                                            lugar);
                                    } else {
                                        cargando();
                                        $("#" + tipo + id).removeAttr('checked');
                                        $("#" + tipo + id).prop('checked', false);
                                        alertaInfo("Cambios no guardados");
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Esta seguro de quitar este lugar?",
                                    showDenyButton: false,
                                    showCancelButton: true,
                                    confirmButtonText: "Continuar",
                                    cancelButtonText: `Cancelar`
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $("#" + tipo + id).prop('checked', true);
                                        mandarDatos(id, tipo, check, Id_colaborador, locacion,
                                            lugar);
                                    } else {
                                        $("#" + tipo + id).removeAttr('checked');
                                        $("#" + tipo + id).prop('checked', false);
                                        alertaInfo("Cambios no guardados");
                                    }
                                });
                            }
                        }
                    } else {
                        cargando();
                        $("#" + tipo + id).removeAttr('checked');
                        $("#" + tipo + id).prop('checked', false);
                        alertaInfo("Cambios no guardados");
                    }
                });
            } else {
                if (check === true) {
                    if (tipo == "Entera") {
                        Swal.fire({
                            title: "Esta seguro de asignar este lugar?",
                            text: "Es un lugar completo se asignarán habitaciones, locales o departamentos relacionados.",
                            showDenyButton: false,
                            showCancelButton: true,
                            confirmButtonText: "Continuar",
                            cancelButtonText: `Cancelar`
                        }).then((result) => {
                            if (result.isConfirmed) {
                                cargando();
                                $("#" + tipo + id).prop('checked', true);
                                mandarDatos(id, tipo, "true", Id_colaborador, locacion, lugar);
                            } else {
                                cargando();
                                $("#" + tipo + id).removeAttr('checked');
                                $("#" + tipo + id).prop('checked', false);
                                alertaInfo("Cambios no guardados");
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Esta seguro de asignar este lugar?",
                            showDenyButton: false,
                            showCancelButton: true,
                            confirmButtonText: "Continuar",
                            cancelButtonText: `Cancelar`
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#" + tipo + id).prop('checked', true);
                                mandarDatos(id, tipo, "true", Id_colaborador, locacion, lugar);
                            } else {
                                $("#" + tipo + id).removeAttr('checked');
                                $("#" + tipo + id).prop('checked', false);
                                alertaInfo("Cambios no guardados");
                            }
                        });
                    }
                } else {
                    if (tipo == "Entera") {
                        Swal.fire({
                            title: "Esta seguro de quitar este lugar?",
                            text: "Es un lugar completo se quitaran habitaciones, locales o departamentos relacionados.",
                            showDenyButton: false,
                            showCancelButton: true,
                            confirmButtonText: "Continuar",
                            cancelButtonText: `Cancelar`
                        }).then((result) => {
                            if (result.isConfirmed) {
                                cargando();
                                $("#" + tipo + id).prop('checked', true);
                                mandarDatos(id, tipo, check, Id_colaborador, locacion, lugar);
                            } else {
                                cargando();
                                $("#" + tipo + id).removeAttr('checked');
                                $("#" + tipo + id).prop('checked', false);
                                alertaInfo("Cambios no guardados");
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Esta seguro de quitar este lugar?",
                            showDenyButton: false,
                            showCancelButton: true,
                            confirmButtonText: "Continuar",
                            cancelButtonText: `Cancelar`
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#" + tipo + id).prop('checked', true);
                                mandarDatos(id, tipo, check, Id_colaborador, locacion, lugar);
                            } else {
                                $("#" + tipo + id).removeAttr('checked');
                                $("#" + tipo + id).prop('checked', false);
                                alertaInfo("Cambios no guardados");
                            }
                        });
                    }
                }
            }
        });

        async function mandarDatos(id, tipo, check, Id_colaborador, locacion, lugar) {
            await guardarDatos(id, tipo, check, Id_colaborador, locacion, lugar).then((result) => {
                setTimeout(alertaOK("Correcto"), 4000);
                setTimeout(location.reload(), 100000);
            }).catch((err) => {
                $("#" + tipo + id).prop('checked', true);
                alertaError(err);
            });
        }

        function guardarDatos(id, tipo, check, Id_colaborador, locacion, lugar) {
            console.log(id, tipo, check);
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.asignarLugar') }}",
                    data: {
                        "id": id,
                        "tipo": tipo,
                        "check": check,
                        "Id_colaborador": Id_colaborador,
                        "locacion": locacion,
                        "lugar": lugar
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response);
                        if (response.length == 0) {
                            reject(response);
                        } else {
                            resolve(response);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        reject(error);
                    }
                });
            })
        }

        function alertaOK(mensaje) {
            Swal.close();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "" + mensaje,
                showConfirmButton: false,
                timer: 1500
            });
        }

        function alertaError(mensaje) {
            Swal.close();
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "" + mensaje,
                showConfirmButton: false,
                timer: 1500
            });
        }

        function alertaInfo(mensaje) {
            Swal.close();
            Swal.fire({
                position: "top-end",
                icon: "info",
                title: "" + mensaje,
                showConfirmButton: false,
                timer: 1500
            });
        }

        function cargando() {
            Swal.close();
            Swal.fire({
                title: "Cargando...",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {}
            });
        }
    </script>
@endsection

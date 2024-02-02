@extends('layouts.menu_layout')
@section('title', 'AGREGAR UN REPORTE')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        h6 {
            text-align: center;
        }

        .checkSeleccionar {
            cursor: pointer;
        }

        .pointer {
            cursor: pointer;
        }

        .enmedio {
            position: relative;
            margin-left: 40%;
        }

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
        }
    </style>
@endsection
@section('MenuPrincipal')
    <div class="container">
        <div class="card">
            <div class="d-grid gap-2 d-md-block">
                <a class="btn btn-danger" style="position: fixed;" type="button" href="{{ route('limpieza.index') }}">
                    <i class='bx bx-arrow-back'></i>
                </a>
            </div>
            <br>
            <div class="card-title">
                <h6>¿Tu reporte es de limpieza o mantenimiento?</h6>
            </div>
            <div class="card-body">
                <form class="row g-3 needs-validation" action="{{ route('limpieza.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-12">
                        <div class="enmedio group-input">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input pointer" type="radio" id="Limpieza" name="Tipo_reporte"
                                    value="Limpieza" {{old('Tipo_reporte') == "Limpieza" ? 'checked':''}} required>
                                <label class="form-check-label pointer" for="Limpieza">Limpieza</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input pointer" type="radio" id="Mantenimiento"
                                    name="Tipo_reporte" value="Mantenimiento" {{old('Tipo_reporte') == "Mantenimiento" ? 'checked':''}} required>
                                <label class="form-check-label pointer" for="Mantenimiento">Mantenimiento</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input pointer" type="radio" id="Otro" name="Tipo_reporte"
                                    value="Otro" {{old('Tipo_reporte') == "Tipo_reporte" ? 'checked':''}} required>
                                <label class="form-check-label pointer" for="Otro">Otro</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-ms-12 mantenimiento" style="display: none;">
                        <label for="Categoria_mtto" class="form-label">Tipo de mantenimiento</label>
                        <input type="text" class="form-control" id="Categoria_mtto" name="Categoria_mtto" value="{{old('Categoria_mtto')}}">
                    </div>
                    @if (!empty($id) && !empty($tipoLocacion) && !empty($Id_locacion))
                        <h6 id="nombreLugar" name="nombreLugar">{{$nombreLugar}}</h6>
                        <input type="hidden" id="idLugar" name="idLugar" value="{{$id}}">
                        <input type="hidden" id="tipoLocacion" name="tipoLocacion" value="{{$tipoLocacion}}">
                        <input type="hidden" id="Id_locacion" name="Id_locacion" value="{{$Id_locacion}}">
                    @else
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Seleccionar un lugar
                            </button>
                        </div>
                        <h6 id="nombreLugar" name="nombreLugar">{{old('nombreLugar')}}</h6>
                        <input type="hidden" id="idLugar" name="idLugar" value="{{old('idLugar')}}">
                        <input type="hidden" id="tipoLocacion" name="tipoLocacion" value="{{old('tipoLocacion')}}">
                        <input type="hidden" id="Id_locacion" name="Id_locacion" value="{{old('Id_locacion')}}">
                    @endif
                    <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Ingrese la descripción" id="Descripcion_Reporte" name="Descripcion_Reporte"
                                required>{{old('Descripcion_Reporte')}}</textarea>
                            <label for="Descripcion_Reporte">Descripción del reporte</label>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2 mt-3">
                                <button class="btn btn-primary" type="submit">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
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
                                            <th>Accion</th>
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
                                                <th class="numerosTabla" data-label="#">
                                                    <strong>{{ $i++ }}</strong>
                                                </th>
                                                <td data-label="Nombre de locación:">{{ $locacion->nombreLocacion }}</td>
                                                <td data-label="Nombre del lugar:">{{ $locacion->Nombre }}</td>
                                                <td data-label="Tipo de lugar:">
                                                    <strong>{{ $locacion->tipoLocacion }}</strong>
                                                </td>
                                                <td data-label="Estado del lugar:">{{ $locacion->Nombre_estado }}</td>
                                                <td data-label="Colaborador:">
                                                    {{ $locacion->NombreCola . ' ' . $locacion->Apellido_pat }}
                                                </td>
                                                <td>
                                                    <button class="tomarLugar btn btn-outline-danger" type="button"
                                                        colaborador="{{ $locacion->NombreCola . ' ' . $locacion->Apellido_pat }}"
                                                        id="{{ $locacion->tipoLocacion }}{{ $locacion->id }}"
                                                        tipo="{{ $locacion->tipoLocacion }}"
                                                        id-lugar="{{ $locacion->id }}"
                                                        Id_locacion="{{ $locacion->Id_locacion }}"
                                                        locacion="{{ $locacion->nombreLocacion }}"
                                                        lugar="{{ $locacion->Nombre }}" data-bs-dismiss="modal">
                                                        <i class='bx bx-check-circle'></i>
                                                    </button>
                                                </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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

        $(".tomarLugar").click(function(e) {
            id = $(this).attr("id-lugar");
            Id_locacion = $(this).attr("Id_locacion");
            tipo = $(this).attr("tipo");
            locacion = $(this).attr("locacion");
            lugar = $(this).attr("lugar");
            igual = $(this).attr("igual");

            console.log(id,tipo,locacion,lugar);
            $("#idLugar").val(id);
            $("#tipoLocacion").val(tipo);
            $("#Id_locacion").val(Id_locacion);
            if (tipo == "Entera") {
                $("#nombreLugar").text(""+tipo+": "+lugar);
            } else {
                $("#nombreLugar").text(locacion+", "+tipo+": "+lugar);
            }
        });

        $("[name=Tipo_reporte]").change(function (e) {
            const tipo = $(this).val();
            console.log(tipo);
            if (tipo == "Mantenimiento") {
                $(".mantenimiento").show();
            }else{
                $("#Categoria_mtto").val("");
                $(".mantenimiento").hide();
            }
        });
    </script>
@endsection

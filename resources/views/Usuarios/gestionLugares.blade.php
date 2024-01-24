@extends('layouts.menu_layout')
@section('title', 'Gestion de lugares')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css"/>
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
            font-size: .8rem;
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
            margin-left: 5em;
        }
    }

    .checkSeleccionar{
        cursor: pointer;
    }
</style>
@endsection
@section('MenuPrincipal')
<div class="container small">
    <h6>Gestion de lugares de los cuales esta a cargo {{Cookie::get('user')}}</h6>
    <div class="card">
        <div class="d-grid gap-2 d-md-block">
            <a class="btn btn-danger" style="position: absolute;" type="button" href="{{route('user.index')}}">
                <i class='bx bx-arrow-back'></i>
            </a>
        </div>
        <br>
        <div class="card-body">
            <div class="search">
                <form class="row col-lg-auto mb-3 mb-lg-0 me-lg-3" action="#" method="get" role="search">
                    <div class="col-sm-3">
                        <label for="locacion" class="form-label">Locación</label>
                        <select class="form-select" id="locacion" name="locacion">
                            <option selected disabled value="">Elija...</option>
                            @foreach ($locaciones as $locacion)
                                @if ($locacion->tipoLocacion == 'Entera')
                                    <option value="{{$locacion->id}}">{{$locacion->Nombre}}</option>
                                @endif                                
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Seleccione una locación.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="tipoLugar" class="form-label">Tipo de lugar</label>
                        <select class="form-select" id="tipoLugar" name="tipoLugar">
                            <option selected disabled value="">Elija...</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{$tipo->tipoLocacion}}">{{$tipo->tipoLocacion}} ({{$tipo->total}})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Seleccione un lugar.
                        </div>
                    </div>
                    <div class="col-sm-1 mt-4">
                        <button type="submit" class="btn btn-danger">
                            <i class='bx bx-search-alt'></i>
                        </button>
                    </div>
                </form>
            </div>

            <table class="table table-hover table-striped" id="tablaLugares">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Locación</th>
                        <th scope="col">Lugar</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody class="">
                    <!-- {{$i=1;}}-->
                    @foreach($locaciones as $locacion)
                        @if ($locacion->mostrar == 'mostrar')
                        @if ($locacion->tipoLocacion == 'Entera')
                        <tr class="{{$locacion->tipoLocacion}} table-warning">
                        @else
                        <tr class="{{$locacion->tipoLocacion}}">
                        @endif
                            <th class="" data-label="#"><strong>{{$i++;}}</strong></th>
                            <td data-label="Nombre de locación:">{{$locacion->nombreLocacion}}</td>
                            <td data-label="Nombre del lugar:">{{$locacion->Nombre}}</td>
                            <td data-label="Tipo de lugar:"><strong>{{$locacion->tipoLocacion}}</strong></td>
                            <td data-label="Estado del lugar:">{{$locacion->Nombre_estado}}</td>
                            <td data-label="Seleccionar">
                                @if ($locacion->Id_colaborador == $user->Id_colaborador)
                                <div class="form-check form-switch">
                                    <input class="form-check-input checkSeleccionar" type="checkbox" role="switch" id="{{$locacion->tipoLocacion}}{{$locacion->id}}" tipo="{{$locacion->tipoLocacion}}" id-lugar="{{$locacion->id}}" checked="checked">
                                </div>
                                @else                                    
                                <div class="form-check form-switch">
                                    <input class="form-check-input checkSeleccionar" type="checkbox" role="switch" id="{{$locacion->tipoLocacion}}{{$locacion->id}}" tipo="{{$locacion->tipoLocacion}}" id-lugar="{{$locacion->id}}">
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
    
    $(document).ready(function () {
        $('#tablaLugares').DataTable({
            dom: 'Bfrtip',
            "order": [[ 0, "asc" ]],
            responsive: true,
            autoWidth: false,
            "lengthMenu": [5,10,15],
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
    });

    $(".checkSeleccionar").click(function (e) {
        id = $(this).attr("id-lugar");
        tipo = $(this).attr("tipo");
        check = $(this).prop('checked');
        Id_colaborador = "{{$user->Id_colaborador}}";
        console.log(tipo+" "+id+" "+check);

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
                        $("#"+tipo+id).prop('checked', true);
                        mandarDatos(id, tipo, "true", Id_colaborador);
                    } else {
                        cargando();
                        $("#"+tipo+id).removeAttr('checked');
                        $("#"+tipo+id).prop('checked', false);
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
                        $("#"+tipo+id).prop('checked', true);
                        mandarDatos(id, tipo, "true", Id_colaborador);
                    } else {
                        $("#"+tipo+id).removeAttr('checked');
                        $("#"+tipo+id).prop('checked', false);
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
                        $("#"+tipo+id).prop('checked', true);
                        mandarDatos(id, tipo, check, Id_colaborador);
                    } else {
                        cargando();
                        $("#"+tipo+id).removeAttr('checked');
                        $("#"+tipo+id).prop('checked', false);
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
                        $("#"+tipo+id).prop('checked', true);
                        mandarDatos(id, tipo, check, Id_colaborador);
                    } else {
                        $("#"+tipo+id).removeAttr('checked');
                        $("#"+tipo+id).prop('checked', false);
                        alertaInfo("Cambios no guardados");
                    }
                });
            }            
        }
    });

    async function mandarDatos(id, tipo, check, Id_colaborador) {
        await guardarDatos(id, tipo, check, Id_colaborador).then((result) => {
            setTimeout(alertaOK("Lugar asignado"), 4000);
            setTimeout(location.reload(), 100000);
        }).catch((err) => {
            $("#"+tipo+id).prop('checked', true);
            alertaError(err);
        });
    }

    function guardarDatos(id, tipo, check, Id_colaborador) {
        console.log(id, tipo, check);
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: "{{route('user.asignarLugar')}}",
                data: {
                    "id":id,
                    "tipo":tipo,
                    "check":check,
                    "Id_colaborador": Id_colaborador
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "JSON",
                success: function (response) {
                    console.log(response);
                    if (response.length == 0) {
                        reject(response);
                    } else {
                        resolve(response);
                    }
                },error: function (error) {
                    console.log(error);
                    reject(error);
                }
            });
        })
    }

    function alertaOK(mensaje) {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: ""+mensaje,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function alertaError(mensaje) {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: ""+mensaje,
            showConfirmButton: false,
            timer: 1500
        });
    }
    
    function alertaInfo(mensaje) {
        Swal.fire({
            position: "top-end",
            icon: "info",
            title: ""+mensaje,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function cargando() {
        Swal.fire({
            title: "Cargando...",
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            },
            willClose: () => {
            }
        });
    }
</script>
@endsection
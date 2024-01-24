@extends('layouts.menu_layout')
@section('title', 'Usuarios')
@section('MenuPrincipal')
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

            #tablaLugares_filter label{
                text-justify: auto;
                content: "Palabra clave";
            }
        }
    </style>
    <div class="container">
        <div class="card">
            <div class="card-body row">
                <div class="col-sm-3">
                    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="{{route('user.index')}}" method="get" role="search">
                        <input type="search" name="buscar" class="form-control" placeholder="Buscar..." aria-label="Search">
                    </form>
                </div>
                <div class="col-sm-9">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a class="btn btn-primary me-md-2" href="{{route('user.create')}}">
                            <i class='bx bx-user-plus'></i>
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
                        <th>User</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th colspan="3">Accion</th>
                    </tr>
                </thead>
                <caption>
                    Usuarios
                </caption>
                <tbody class="table-group-divider">
                    @foreach ($users as $user)
                        <tr class="">
                            <td data-label="User" scope="row">{{$user->user}}</td>
                            <td data-label="Nombre">{{$user->Nombre." ".$user->Apellido_pat}}</td>
                            <td data-label="Email">{{$user->email}}</td>
                            <td data-label="Rol">{{$user->nameRol}}</td>
                            <td class="flex" data-label="Editar">
                                <form action="{{route('user.edit',$user->Id_colaborador)}}" method="get">
                                    <button class="btn btn-outline-warning" type="submit" title="Editar">
                                        <i class='bx bx-edit-alt'></i>
                                    </button>
                                </form>
                            </td>
                            <td class="flex" data-label="Detalle">
                                <form action="{{route('user.show',$user->Id_colaborador)}}" method="get">
                                    <button class="btn btn-outline-light" type="submit" title="Mostrar detalle">
                                        <i class='bx bxs-show bx-burst' style='color:#1764e6'></i>
                                    </button>
                                </form>
                            </td>
                            <td class="flex" data-label="Lugares">
                                <form action="{{route('user.gestionLugares',$user->Id_colaborador)}}" method="get">
                                    <button class="btn btn-outline-danger" type="submit" title="Mostrar lugares asignados">
                                        <i class='bx bx-building-house'></i>
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
                {{ $users->appends(Request::all())->render() }}
            </div>
        </div>        
    </div>
@endsection
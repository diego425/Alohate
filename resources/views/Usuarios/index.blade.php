@extends('layouts.menu_layout')
@section('title', 'Usuarios')
@section('MenuPrincipal')
    <div class="container">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-primary me-md-2" href="{{route('user.create')}}">
                <i class='bx bx-user-plus'></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-borderless table-primary align-middle">
                <thead class="table-light">
                    <caption>
                        Usuarios
                    </caption>
                    <tr>
                        <th>User</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th colspan="1">Accion</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($users as $user)
                        <tr class="table-primary">
                            <td scope="row">{{$user->user}}</td>
                            <td>{{$user->Nombre}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->nameRol}}</td>
                            <td>
                                <form action="{{route('user.edit',$user->Id_colaborador)}}" method="get">                                    
                                    <button class="btn btn-warning" type="submit">
                                        <i class='bx bx-edit-alt'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>                        
                    @endforeach
                </tbody>
                <tfoot>
                    
                </tfoot>
            </table>
            <div class="d-flex">
                {{ $users->appends(Request::all())->render() }}
            </div>
        </div>        
    </div>
@endsection
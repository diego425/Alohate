@extends('layouts.menu_layout')
@section('title', 'Detalle de colaborador')
@section('MenuPrincipal')
<style>
    .divImage{
        display: flex;
        justify-content: center;
    }

    .center{
        text-align: center;
    }
    
    .center-image{
        display: block;
        margin-left: 1em;
        margin-right: 1em;
        margin: 5em;
    }

    .bold{
        font-weight: bold;
    }

    .user-detail-card span {
        color: #3c559c;
    }
    @media screen and (max-width: 800px) {
        .center{
            text-align: center;
        }
        
        .justify{
            text-align: justify;
        }
    
        .center-image{
            display: block;
            margin: 1em;
            width: 50%;
        }

        .bold{
            font-weight: bold;
        }        
    }
</style>
<div class="container text-uppercase">
    <div class="card mb-3" style="max-width: 100%;">
        <div class="row g-0">
            <div class="d-grid gap-2 d-md-block">
                <a class="btn btn-danger" style="position: fixed;" type="button" href="{{route('user.index')}}">
                    <i class='bx bx-arrow-back'></i>
                </a>
            </div>
            <br>
            <div class="col-md-10">
                <div class="card-body">
                    <h6 class="card-title center bold">Datos del colaborador</h6>
                    <div class="user-detail-card justify" data-mh="card-one">
                        <p><span>Nombre :</span> {{$user->Nombre}}</p>
                        <p><span>Apellido paterno :</span> {{$user->Apellido_pat}}</p>
                        <p><span>Apellido materno :</span> {{$user->Apellido_mat}} </p>
                        <p><span>Teléfono :</span> {{$user->Numero_cel}}</p>
                        <p><span>Calle :</span> {{$user->Calle}}</p>
                        <p><span>Colonia. :</span> {{$user->Colonia}}</p>
                        <p><span>Numero :</span> {{$user->Numero_casa}}</p>
                    </div>
                    <hr>
                    <h6 class="card-title center bold">Credenciales</h6>
                    <div class="user-detail-card justify" data-mh="card-one">
                        <p><span>Usuario :</span> {{$user->user}}</p>
                        <p><span>Puesto :</span> {{$user->nameRol}}</p>
                        <p><span>Estado :</span> {{$user->Estatus_col}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 divImage">
                <img src="https://www.debate.com.mx/__export/1663724202745/sites/debate/img/2022/09/20/margarito_esparzx_foto_internet.webp_2120446623.webp" class="img-fluid card-img-top rounded-start rounded-end center-image" alt="...">
            </div>
        </div>
    </div>
</div>
@endsection
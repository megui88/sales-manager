@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Usuario <strong>{{ $user->code  }}</strong>
                    <a href="/profile/edit/{{ $user->id }}" style="float: right"> Editar </a></div>
                    <div class="panel-body">

                        <h1>{{ $user->name }} {{ $user->last_name }}</h1>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Estado:</strong>
                            </div>

                            <div class="col-md-6">
                                @if($user->enable == 1) Activo @else Inactivo @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Tipo de Rol:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->role}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Tipo de Rol:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->role}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Correo Electronico:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->email}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Credito Maximo:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->credit_max}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Documento:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->document}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Dirección:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->address}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Localidad:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->location}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Telefono:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->phone}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Interno Telefonico:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->internal_phone}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Celular:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->cellphone}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Fecha de Nacimiento:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->birth_date}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Cuil/Cuit:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->cuil_cuit}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Nombre de Fantasia:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->fantasy_name}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Razon social:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->business_name}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>CBU:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->cbu}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Pagina Web:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->web}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Fecha de Alta:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->discharge_date}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Fecha de Baja:</strong>
                            </div>

                            <div class="col-md-6">
                                {{$user->leaving_date}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

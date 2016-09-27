@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row hidden-print text-center">
            <div class="col-xs-4">
                <a onclick="print()" class="btn btn-lg btn-info">Imprimir</a>
            </div>
            <div class="col-xs-4">
            </div>
            <div class="col-xs-offset-4 col-xs-4">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h1> ASOCIACION MUTUAL 7 de MARZO </h1>
            </div>
            <div class="col-xs-12">
                <h3> (Mut 001) ALTA DE ASOCIADO </h3>
            </div>
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-3">Fecha: <strong>{{ $user->created_at->format('Y/m/d')}}</strong></div>
                    <div class="col-xs-3">N° de ASOCIADO: <strong>{{$user->code}}</strong></div>
                    <div class="col-xs-3"></div>
                    <div class="col-xs-3">Fecha de ALTA: <strong>{{ $user->discharge_date }}</strong></div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-3">Apellido/Nombre: </div>
                    <div class="col-xs-6"><strong>{{ $user->last_name . ' ' . $user->name}}</strong></div>
                    <div class="col-xs-3"></div>
                </div>
                <div class="row">
                    <div class="col-xs-3">Dirección: </div>
                    <div class="col-xs-3"><strong>{{ $user->address}}</strong></div>
                    <div class="col-xs-3">Localidad: </div>
                    <div class="col-xs-3"><strong>{{ $user->location}}</strong></div>
                </div>
                <div class="row">
                    <div class="col-xs-3">Telefono: </div>
                    <div class="col-xs-3"><strong>{{ $user->phone}}</strong></div>
                    <div class="col-xs-3">Interno: </div>
                    <div class="col-xs-3"><strong>{{ $user->internal_phone}}</strong></div>
                </div>
                <div class="row">
                    <div class="col-xs-3">Celular: </div>
                    <div class="col-xs-9"><strong>{{ $user->cellphone}}</strong></div>
                </div>
                <div class="row">
                    <div class="col-xs-3">Fecha de Nacimiento: </div>
                    <div class="col-xs-3"><strong>{{ \App\Helpers\BladeHelpers::date($user->birth_date)}}</strong></div>
                    <div class="col-xs-3">DNI</div>
                    <div class="col-xs-3"><strong>{{ $user->document}}</strong></div>
                </div>
                <div class="row">
                    <div class="col-xs-3">Cuil/Cuit: </div>
                    <div class="col-xs-3"><strong>{{ $user->cuil_cuit}}</strong></div>
                    <div class="col-xs-2">Correo Electronico</div>
                    <div class="col-xs-4"><strong>{{ \App\Helpers\BladeHelpers::email($user->email)}}</strong></div>
                </div>
            </div>
        </div>
        <h2>INTEGRANTES DEL GRUPO FAMILIAR</h2>
        <div class="row table-responsive">
            <table class="table table-bordered">
                <thead>
                <td width="20%">PARENTESCO</td>
                <td width="40%">APELLIDO/NOMBRE</td>
                <td>DNI N°</td>
                <td>FECHA NACIMIENTO</td>
                </thead>
                <tbody>
                <tr>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                </tr>
                <tr>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                </tr>
                <tr>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                </tr>
                <tr>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                </tr>
                <tr>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                    <td><h5>&nbsp;</h5></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h3>Si mi empleador tiene convenio con <strong>{{ env('BUSINESS_NAME') }}</strong> CUIT: <strong>{{ env('BUSINESS_CUIT') }}</strong> autorizo el cobro mis consumos por debito automatico </h3>
                <h3> <strong>SI&nbsp;&nbsp;&nbsp;&nbsp;NO</strong>&nbsp;&nbsp;<small>(tache el que no corresponde)</small></h3>
            </div>
        </div>
        <div class="row" style="font-size: 0.85em">
            <div class="col-xs-4">
                <h1>&nbsp;</h1>
                <h1>&nbsp;</h1>
                <p>____________________________<br>Firma del Afiliado</p>
            </div>
            <div class="col-xs-2">
            </div>
            <div class="col-xs-6">
                <h1>&nbsp;</h1>
                <h1>&nbsp;</h1>
                <p>____________________________<br>
                    {{ Auth::user()->name . ' ' . Auth::user()->last_name }}<br>
                    Empleado de {{ env('BUSINESS_NAME') }}</p>
            </div>
        </div>
    </div>
@endsection
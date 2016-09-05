@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-xs-12">
                <h1> ASOCIACION MUTUAL 7 de MARZO </h1>
            </div>
            <div class="col-xs-12">
                <h3> (Mut 002) ALTA DE PROVEEDOR </h3>
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
                    <div class="col-xs-6"><strong>{{ $user->last_name . $user->name}}</strong></div>
                    <div class="col-xs-3"></div>
                </div>
                <div class="row">
                    <div class="col-xs-2">Razon Social: </div>
                    <div class="col-xs-4"><strong>{{ $user->business_name }}</strong></div>
                    <div class="col-xs-2">Nombre de Fantasia: </div>
                    <div class="col-xs-4"><strong>{{ $user->fantasy_name }}</strong></div>
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
                    <div class="col-xs-3"><strong>{{ $user->birth_date}}</strong></div>
                    <div class="col-xs-3">DNI</div>
                    <div class="col-xs-3"><strong>{{ $user->document}}</strong></div>
                </div>
                <div class="row">
                    <div class="col-xs-3">Cuil/Cuit: </div>
                    <div class="col-xs-3"><strong>{{ $user->cuil_cuit}}</strong></div>
                    <div class="col-xs-2">Correo Electronico</div>
                    <div class="col-xs-4"><strong>{{ $user->email}}</strong></div>
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
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                </tr>
                <tr>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                </tr>
                <tr>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                </tr>
                <tr>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                </tr>
                <tr>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                </tr>
                <tr>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                    <td><h4>&nbsp;</h4></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h3>Autorizo a <strong>{{ env('BUSINESS_NAME') }}</strong> CUIT: <strong>{{ env('BUSINESS_CUIT') }}</strong> el cobro de cuotas/consumos/gastos administrativos/Anulaciones de ventas/etc. de mi haber por ventas.</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <h1>&nbsp;</h1>
                <p>____________________________<br>Firma del Proveedor</p>
            </div>
            <div class="col-xs-4">
                </div>
            <div class="col-xs-4">
                <h1>&nbsp;</h1>
                <p>____________________________<br>Autorizado</p>
            </div>
        </div>
    </div>
@endsection
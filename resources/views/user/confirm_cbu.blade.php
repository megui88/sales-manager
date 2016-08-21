@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Actualización de CBU Usuario: <strong>{{ $user->code }}</strong><br>CUIL/CUIT: <strong>{{ $user->cuil_cuit }}</strong></div>
                    <div class="panel-body">
                        <p>Yo <strong>{{ $user->last_name . ' ' . $user->name }}</strong> DNI <strong>{{ $user->document }}</strong>:</p>
                        <p>
                            Por medio de este comprobante afirmo que el <strong>CBU N° {{ $user->cbu }}</strong>
                            corresponde a mi cuenta bancaria declarada.
                            Puede ser usado por <strong>{{ env('BUSINESS_NAME') }}</strong> CUIT: <strong>{{ env('BUSINESS_CUIT') }}</strong>
                            para transferirme dinero y en caso de perder vigencia me comprometo a informarle a la mutual en su debido momento.
                            De haber ocacionado daños o gastos por informar mal mi CBU, me hare responsable.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <h1>&nbsp;</h1>
                        <h1>&nbsp;</h1>
                        <p>____________________________<br>Firma del Usuario</p>
                    </div>
                    <div class="col-xs-4">
                    </div>
                    <div class="col-xs-4">
                        <h1>&nbsp;</h1>
                        <h1>&nbsp;</h1>
                        <p>____________________________<br>Empleado Mutual</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

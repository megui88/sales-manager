@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">BAJA de Usuario: <strong>{{ $user->code }}</strong> <br> CUIL/CUIT: <strong>{{ $user->cuil_cuit }}</strong></div>
                    <div class="panel-body">
                        <p>Yo <strong>{{ $user->last_name . ' ' . $user->name }}</strong> DNI <strong>{{ $user->document }}</strong>:</p>
                        <p>
                            Por medio de este comprobante solicito la baja en el sistema de la Mutual: <strong>{{ env('BUSINESS_NAME') }}</strong><br> CUIT: <strong>{{ env('BUSINESS_CUIT') }}</strong>.
                            Solicitando a la misma el descuento total de mis consumos o la retribución de mis haberes pactado en mi alta.
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

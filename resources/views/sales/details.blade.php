@extends('layouts.app')

@section('content')
    <div class="container" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row hidden-print text-center">
            <div class="col-xs-4">
                <a onclick="print()" class="btn btn-lg btn-info">Imprimir</a>
            </div>
            @if($sale->state == \App\Sale::ANNULLED)
            <div class="col-xs-4">
                <h1 style="color: red">ANULADA</h1>
            </div>@endif
            @if($sale->state != \App\Sale::ANNULLED)
            <div class="col-xs-offset-4 col-xs-4">
                <a href="/sales/{{$sale->id}}/annul" class="btn btn-lg btn-danger">Anular</a>
            </div>
            @endif
        </div>
        <div class="row">
            <h1>Comprobante de Venta</h1>
        </div>
        <div class="row"  style="text-align: center">
            <div class="col-xs-5" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                <h3>{{ env('BUSINESS_NAME') }}</h3>
                <h4>CUIT: {{ env('BUSINESS_CUIT') }}</h4>
                <h5>Dirección: {{ env('BUSINESS_ADDRESS') }}</h5>
                <h5>Tel.: {{ env('BUSINESS_PHONE') }}</h5>
            </div>
            <div class="col-xs-2" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                <h1>X</h1>
                <p>Comprobante No valido como Factura</p>
            </div>
            <div class="col-xs-5" >
                <div class="row" >
                    <div class="col-xs-8" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                        <h4>Comprobante N° <strong>{{ str_pad($sale->id, 10, "0", STR_PAD_LEFT) }}</strong></h4>
                    </div>
                    <div class="col-xs-4" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                        <h4>Fecha <strong>{{ $sale->created_at->format('d/m/Y') }}</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                        <h4>Operador: <strong>{{ $sale->transaction->first()->operator->code }}</strong></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="row" >
                    <h4>Socio:</h4>
                    <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif><strong>Codigo:</strong></div>
                    <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>{{ $sale->payer->code }}</div>
                    <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif><strong>Apellido/Nombre:</strong></div>
                    <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>{{ $sale->payer->fullName() }}</div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="row">
                    <h4>Vendedor:</h4>
                    <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif><strong>Codigo:</strong></div>
                    <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>{{ $sale->collector->code }}</div>
                    <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif><strong>Nombre:</strong></div>
                    <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>{{ $sale->collector->fantasy_name }}</div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <table class="table">
                <thead>
                <tr>
                    <td>Periodo</td>
                    <td>Cuota N°</td>
                    <td>Descripción</td>
                    <td>Importe</td>
                </tr>
                </thead>
                <tbody>
                @foreach($sale->dues as $due)
                    <tr>
                        <td>{{ $due->period }}</td>
                        <td>{{ $due->number_of_quota }}</td>
                        <td>@if(!empty($sale->description)){{ $sale->description}}@else{{ $sale->concept->name }}@endif</td>
                        <td>{{ \App\Services\BusinessCore::printAmount($due->amount_of_quota) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: right"><strong>Total</strong></td>
                    <td><strong>{{ \App\Services\BusinessCore::printAmount($sale->amount) }}</strong></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Comprobante de Venta</h1>
        <div class="row"  style="text-align: center">
            <div class="col-sm-5">
                <h1>{{ env('BUSINESS_NAME') }}</h1>
                <h2>CUIT: {{ env('BUSINESS_CUIT') }}</h2>
                <h3>Dirección: {{ env('BUSINESS_ADDRESS') }}</h3>
                <h3>Tel.: {{ env('BUSINESS_PHONE') }}</h3>
            </div>
            <div class="col-sm-2">
                <h1>X</h1>
                <h3>Comprobante No valido como Factura</h3>
            </div>
            <div class="col-sm-5">
                <div class="row">
                    <div class="col-sm-8">
                        <h3>Comprobante N° <strong>{{ str_pad($sale->id, 10, "0", STR_PAD_LEFT) }}</strong></h3>
                    </div>
                    <div class="col-sm-4">
                        <h3>Fecha <strong>{{ $sale->created_at->format('d/m/Y') }}</strong></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <h1>Socio:</h1>
            <div class="col-sm-3">Codigo:</div>
            <div class="col-sm-3">{{ $sale->payer()->code }}</div>
            <div class="col-sm-3">Apellido/Nombre:</div>
            <div class="col-sm-3">{{ $sale->payer()->fullName() }}</div>
        </div>
        <div class="row">
            <h1>Vendedor:</h1>
            <div class="col-sm-3">Codigo:</div>
            <div class="col-sm-3">{{ $sale->collector()->code }}</div>
            <div class="col-sm-3">Nombre:</div>
            <div class="col-sm-3">{{ $sale->collector()->fantasy_name }}</div>
        </div>
        <div class="row">
            <table class="table">
                <thead>
                <tr>
                    <td>Periodo</td>
                    <td>Cuota N°</td>
                    <td>Descripción</td>
                    <td style="text-align: right">Importe</td>
                </tr>
                </thead>
                <tbody>
                @foreach($sale->dues() as $due)
                    <tr>
                        <td>{{ $due->period }}</td>
                        <td>{{ $due->number_of_quota }}</td>
                        <td>{{ $sale->description }}</td>
                        <td>{{ \App\Services\BusinessCore::printAmount($due->amount_of_quota) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td style="text-align: right"><strong>Total</strong></td>
                    <td><strong>{{ \App\Services\BusinessCore::printAmount($sale->amount) }}</strong></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
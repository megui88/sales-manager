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
        @foreach([false, true] as $n)
            @if($n)<hr> @endif
            <div @if($n)class="visible-print"@endif>
                <h4>Orden de Compra @if($n) Proveedor @else Mutual @endif</h4>
            </div>
            <div @if($n)class="visible-print"@endif>
                <div class="row"  style="text-align: center">
                    <div class="col-xs-5" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                        <p>{{ env('BUSINESS_NAME') }}<br>
                            CUIT: {{ env('BUSINESS_CUIT') }}<br>
                            Dirección: {{ env('BUSINESS_ADDRESS') }}<br>
                            Tel.: {{ env('BUSINESS_PHONE') }}</p>
                    </div>
                    <div class="col-xs-2" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                        <p>Comprobante No valido como Factura</p>
                    </div>
                    <div class="col-xs-5">
                        <div class="row">
                            <div class="col-xs-8" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                                <p>Comprobante N° <strong>{{ str_pad($sale->id, 10, "0", STR_PAD_LEFT) }}</strong></p>
                            </div>
                            <div class="col-xs-4" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                                <p>Fecha <strong>{{ $sale->created_at->format('d/m/Y') }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                                <p>Operador: <strong>{{ $sale->transaction->first()->operator->code }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="row"  @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                            <p>Socio:</p>
                            <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif><strong>Codigo:</strong></div>
                            <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>{{ $sale->payer->code }}</div>
                            <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif><strong>Apellido/Nombre:</strong></div>
                            <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>{{ $sale->payer->fullName() }}</div>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="row" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                            <p>Vendedor:</p>
                            <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif><strong>Codigo:</strong></div>
                            <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>{{ $sale->collector->code }}</div>
                            <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif><strong>Nombre:</strong></div>
                            <div class="col-xs-6" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>{{ $sale->collector->fantasy_name }}</div>
                        </div>
                    </div>
                </div>
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
                            <tr style="font-size: 0.85em">
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
                            <td></td>
                            <td style="text-align: right"><strong>Credito</strong></td>
                            <td><strong>{{ \App\Services\BusinessCore::printAmount($sale->amount) }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="text-align: right"><strong>Total</strong></td>
                            <td style="border: 2px black solid"></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="row" style="font-size: 0.75em">
                    <div class="col-xs-4" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                        <h5></h5>
                        <p>____________________________<br>Firma del Afiliado</p>
                    </div>
                    <div class="col-xs-2">
                    </div>
                    <div class="col-xs-5" @if($sale->state == \App\Sale::ANNULLED) style="text-decoration:line-through;"@endif>
                        <h5></h5>
                        <p>____________________________<br>
                            {{ Auth::user()->name . ' ' . Auth::user()->last_name }}<br>
                            Empleado de {{ env('BUSINESS_NAME') }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
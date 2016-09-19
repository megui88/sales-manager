@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Orden de compra</div>

                    <div class="panel-body">
                        <form method="post" action="/purchase_orders" name="tests">
                            {{ csrf_field() }}
                            <input type="hidden" name="sale_mode" value="{{ \App\Sale::PURCHASE_ORDER }}">

                            <div class="form-group{{ !empty($errors->getBags()) ? ' has-error' : '' }}">

                                <div class="col-md-4">
                                    <label for="payer" class="col-md-4 control-label">Comprador</label>
                                    <input id="payer" type="text" class="form-control" name="payer" data-sale=true required value="{{old('payer')}}">
                                    <input id="payer_id" type="hidden" class="form-control" name="payer_id" value="{{old('payer_id')}}">

                                    @if ($errors->has('payer_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('payer_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="collector" class="col-md-4 control-label">Vendedor</label>
                                    <input id="collector" type="text" class="form-control" name="collector" data-sale=true required value="{{old('collector')}}">
                                    <input id="collector_id" type="hidden" class="form-control" name="collector_id" value="{{old('collector_id')}}">

                                    @if ($errors->has('collector_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('collector_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="period" class="col-md-4 control-label">Periodo</label>
                                    {!! \App\Helpers\BladeHelpers::sellPeriodSelect(5, old('period')) !!}

                                    @if ($errors->has('period'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('period') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label for="concept" class="col-md-4 control-label">Concepto</label>
                                    {!! \App\Helpers\BladeHelpers::sellConceptSelect('+', old('concept_id')) !!}

                                    @if ($errors->has('concept_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('concept_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="description" class="col-md-4 control-label">Descripción</label>
                                    <input id="description" type="text" class="form-control" name="description"  value="{{old('description')}}">

                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label for="installments" class="col-md-4 control-label">Cuotas</label>
                                    <input id="installments" type="number" class="form-control" name="installments" value="{{old('installments')}}">

                                    @if ($errors->has('installments'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('installments') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">

                                    <label for="amount" class="col-md-4 control-label">Importe</label>
                                    <input id="amount" type="number" class="form-control" name="amount" value="{{old('amount')}}">

                                    @if ($errors->has('amount'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-12" style="text-align: right">
                                    <p>
                                        {!! \App\Helpers\BladeHelpers::buttonSubmit('Vender')!!}
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Ultimas ordene de compras</div>

                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>

                            <tr>
                                <td>Comprador</td>
                                <td>Vendedor</td>
                                <td>Periodo</td>
                                <td>C</td>
                                <td>Importe</td>
                                <td></td>
                            </tr>
                            </thead>
                            @foreach($purchase_orders as $purchase_order)
                                <tr @if($purchase_order->state == \App\Sale::ANNULLED) class="danger"@endif>
                                    <td>{{ $purchase_order->payer->code  }}</td>
                                    <td>{{ $purchase_order->collector->code }}</td>
                                    <td>{{ $purchase_order->period }}</td>
                                    <td>{{ $purchase_order->installments }}</td>
                                    <td>{{ $purchase_order->amount }}</td>
                                    <td><a href="/purchase_orders/{{$purchase_order->id}}"><i class="fa fa-print" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var inputFocus = 'payer';
    </script>
@endsection
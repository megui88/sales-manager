@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Orden de compra</div>

                    <div class="panel-body">
                        <form method="post" action="/purchase_orders" name="tests">
                            {{ csrf_field() }}
                            <input type="hidden" name="sale_mode" value="{{ \App\Sale::PURCHASE_ORDER }}">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="payer" class="col-md-6 control-label" id="payer-title">&nbsp;</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="collector" class="col-md-6 control-label" id="collector-title">&nbsp;</label>
                                    </div>
                                </div>
                            </div>
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
                                    {!! \App\Helpers\BladeHelpers::sellPeriodSelect(5, old('period'), 'period', true) !!}

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
                                        {!! \App\Helpers\BladeHelpers::buttonSubmit('Vender', null, 'javascript: saleFormSubmit(this)')!!}
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Confirmar Orden</div>

                    <div class="panel-body">
                        <div class="form-group{{ !empty($errors->getBags()) ? ' has-error' : '' }}">
                            <form method="post" action="/purchase_orders" name="confirm_order">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="PATCH">
                                <div class="col-md-6">
                                    <label for="order_id" class="col-md-4 control-label">Numero de Orden</label>
                                    <input id="id" type="number" class="form-control" name="order_id" data-sale=true required value="{{old('id')}}" required>

                                    @if ($errors->has('order_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('order_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="order_amount" class="col-md-4 control-label">Importe</label>
                                    <input id="order_amount" type="number" class="form-control" name="order_amount" value="{{old('amount')}}" required>

                                    @if ($errors->has('order_amount'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('order_amount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-12" style="text-align: right">
                                    <p>
                                        <input type="submit" value="Confirmar Orden" class="btn btn-lg btn-warning">
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12">
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
@section('bottom')
    <script>
        $(document).ready(function(){
            $('#payer').keydown(function(){
                $('#payer-title').html('&nbsp;');
                $('#payer_id').val('');
            });
            $('#collector').keydown(function(){
                $('#collector_id').val('');
            });
        });
    </script>
    <script type="application/javascript">
        function saleFormSubmit(that) {
            var payer = $('#payer');

            bussiness.inputs.inputMember(payer)
                .then(function (data) {
                    bussiness.inputs.memberOk(payer, data);

                    if (bussiness.inputs.setMember(payer, {
                            'full_name': data.last_name + ', ' + data.name,
                            'code': data.code,
                            'id': data.id,
                            'fantasy_name': data.fantasy_name
                        })) {
                        var collector = $('#collector');
                        bussiness.inputs.inputMember(collector)
                            .then(function (data) {
                                bussiness.inputs.memberOk(collector, data);

                                if (bussiness.inputs.setMember(collector, {
                                        'full_name': data.last_name + ', ' + data.name,
                                        'code': data.code,
                                        'id': data.id,
                                        'fantasy_name': data.fantasy_name
                                    })) {
                                    console.log('mando form');
                                    return that.form.submit();
                                }
                            })
                            .catch(function(msg){if ($(collector).prop('required')){
                                bussiness.alerts.inputIsRequired(collector);
                                console.log(msg);
                            } else {
                                bussiness.inputs.nextInput(collector);
                            }});
                    }

                })
                .catch(function(msg){if ($(payer).prop('required')){
                    bussiness.alerts.inputIsRequired(payer);
                    console.log(msg);
                } else {
                    bussiness.inputs.nextInput(payer);
                }});
        }
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Nuevo Descuento a Proveedor</div>

                    <div class="panel-body">
                        <form method="post" action="/check_book" name="tests">
                            {{ csrf_field() }}
                            <input type="hidden" name="sale_mode" value="{{ \App\Sale::DISCOUNT_SUPPLIER }}">

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
                                    <label for="payer" class="col-md-4 control-label">Proveedor</label>
                                    <input id="payer" type="text" class="form-control" name="payer" data-sale=true required value="{{old('payer')}}">
                                    <input id="payer_id" type="hidden" class="form-control" name="payer_id" value="{{old('payer_id')}}">

                                    @if ($errors->has('payer_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('payer_id') }}</strong>
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
                                    {!! \App\Helpers\BladeHelpers::sellConceptSelect('*', old('concept_id'), 'c418cb0e-7e10-11e6-91cb-04011111c601') !!}

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
                    <div class="panel-heading">Ultimos descuentos</div>

                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>

                            <tr>
                                <td>Proveedor</td>
                                <td>Periodo</td>
                                <td>C</td>
                                <td>Importe</td>
                                <td></td>
                            </tr>
                            </thead>
                            @foreach($check_books as $check_book)
                                <tr @if($check_book->state == \App\Sale::ANNULLED) class="danger"@endif>
                                    <td>{{ $check_book->payer->code  }}</td>
                                    <td>{{ $check_book->period }}</td>
                                    <td>{{ $check_book->installments }}</td>
                                    <td>{{ $check_book->amount }}</td>
                                    <td><a href="/sales/{{$check_book->id}}"><i class="fa fa-print" aria-hidden="true"></i></a></td>
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
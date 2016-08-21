@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Nueva venta</div>

                    <div class="panel-body">
                        <form method="post" action="/sales">
                            {{ csrf_field() }}
                            <input type="hidden" name="sale_mode" value="{{ \App\Sale::CURRENT_ACCOUNT }}">

                            <div class="form-group{{ !empty($errors->getBags()) ? ' has-error' : '' }}">

                                <div class="col-md-4">
                                    <label for="payer" class="col-md-4 control-label">Socio</label>
                                    <input id="payer" type="text" class="form-control" name="payer">
                                    <input id="payer_id" type="hidden" class="form-control" name="payer_id">

                                    @if ($errors->has('payer_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('payer_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="collector" class="col-md-4 control-label">Proveedor</label>
                                    <input id="collector" type="text" class="form-control" name="collector">
                                    <input id="collector_id" type="hidden" class="form-control" name="collector_id">

                                    @if ($errors->has('collector_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('collector_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="period" class="col-md-4 control-label">Periodo</label>
                                    <input id="period" type="text" class="form-control" name="period">
                                    <input id="period_id" type="hidden" class="form-control" name="period_id">

                                    @if ($errors->has('period_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('period_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <label for="description" class="col-md-4 control-label">Descripción</label>
                                    <input id="description" type="text" class="form-control" name="description">

                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label for="installments" class="col-md-4 control-label">Cuotas</label>
                                    <input id="installments" type="number" class="form-control" name="installments">

                                    @if ($errors->has('installments'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('installments') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">

                                    <label for="amount" class="col-md-4 control-label">Importe</label>
                                    <input id="amount" type="number" class="form-control" name="amount">

                                    @if ($errors->has('amount'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-12" style="text-align: right">
                                    <p>
                                        <button type="submit" class="btn btn-primary">Completar</button>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Ultimas ventas</div>

                    <div class="panel-body">
                        <table class="table-responsive">
                            @foreach($sales as $sale)
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->payer()->last_name . ' ... ' }}</td>
                                <td>{{ $sale->collecter()->fantasy_name }}</td>
                                <td>{{ $sale->period }}</td>
                                <td>{{ $sale->installments }}</td>
                                <td>{{ $sale->amount }}</td>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('user.change_layout')
@section('form_title')
    Anulacion de venta: <strong>{{ $sale->id }}</strong>
@endsection
@section('form_content')
    <p>Se realizara la anulacion de la venta <strong>{{ $sale->id}}</strong> del comprador <strong>{{ $sale->payer->code}}</strong><br>
        y el vendedor <strong>{{ $sale->collector->code}}</strong> por el importe de: <strong>{{ $sale->amount}}</strong></p>
    <form action="/sales/{{ $sale->id }}/annul" method="post">

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Confirmar con contraseña:</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}">
                @if ($errors->has('password'))
                    <span class="help-block">
                         <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </form>
@endsection

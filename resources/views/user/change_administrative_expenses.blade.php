@extends('user.change_layout')
@section('form_title')
    Cambiar CBU
@endsection
@section('form_content')
    <div class="alert alert-danger">
        <h1> IMPORTANTE:</h1>
        <h3>Estos cambios tienen efecto a partir de este momento, no cambia los cargos de las ventas ya relizadas.</h3>
    </div>
    <form class="form-horizontal" role="form" method="post" action="{{ url('/users/administrative_expenses') . '/' . $user->id }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('administrative_expenses') ? ' has-error' : '' }}">
            <label for="administrative_expenses" class="col-md-4 control-label">Gastos Administrativos</label>

            <div class="col-md-6">
                <input id="administrative_expenses" type="number" class="form-control" name="administrative_expenses" value="{{ !empty($errors->getBags()) ? old('administrative_expenses') : $user->administrative_expenses }}">
                @if ($errors->has('administrative_expenses'))
                    <span class="help-block">
                         <strong>{{ $errors->first('administrative_expenses') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('administrative_expenses_confirmation') ? ' has-error' : '' }}">
            <label for="administrative_expenses_confirmation" class="col-md-4 control-label">Gastos Administrativos</label>

            <div class="col-md-6">
                <input id="administrative_expenses_confirmation" type="number" class="form-control" name="administrative_expenses_confirmation" value="{{ old('administrative_expenses_confirmation')}}">
                @if ($errors->has('administrative_expenses_confirmation'))
                    <span class="help-block">
                         <strong>{{ $errors->first('administrative_expenses_confirmation') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                {!! \App\Helpers\BladeHelpers::buttonSubmit('Cambiar Gastos Administrativos')!!}
            </div>
        </div>
    </form>
@endsection

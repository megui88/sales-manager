@extends('user.change_layout')
@section('form_title')
    Cambiar código
@endsection
@section('form_content')
    <form class="form-horizontal" role="form" method="post" action="{{ url('/users/code') . '/' . $user->id }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
            <label for="code" class="col-md-4 control-label">Codigo</label>

            <div class="col-md-6">
                <input id="code" type="number" class="form-control" name="code" value="{{ !empty($errors->getBags()) ? old('code') : $user->code }}">
                @if ($errors->has('code'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('code_confirmation') ? ' has-error' : '' }}">
            <label for="code_confirmation" class="col-md-4 control-label">Confirmar Codigo</label>

            <div class="col-md-6">
                <input id="code_confirmation" type="number" class="form-control" name="code_confirmation" value="{{  old('code_confirmation') }}">
                @if ($errors->has('code_confirmation'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('code_confirmation') }}</strong>
                                    </span>
                @endif
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                {!! \App\Helpers\BladeHelpers::buttonSubmit('Cambiar Codigo')!!}
            </div>
        </div>
    </form>
@endsection

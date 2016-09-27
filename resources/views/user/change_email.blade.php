@extends('user.change_layout')

@section('form_title')
    Cambiar E-mail
@endsection
@section('form_content')
    <form class="form-horizontal" role="form" method="post" action="{{ url('/users/email') . '/' . $user->id }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">E-Mail</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ !empty($errors->getBags()) ? old('email') : \App\Helpers\BladeHelpers::email($user->email) }}">
                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('email_confirmation') ? ' has-error' : '' }}">
            <label for="email_confirmation" class="col-md-4 control-label">Confirmar E-Mail</label>

            <div class="col-md-6">
                <input id="email_confirmation" type="email" class="form-control" name="email_confirmation" value="{{  old('email_confirmation') }}">
                @if ($errors->has('email_confirmation'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email_confirmation') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                {!! \App\Helpers\BladeHelpers::buttonSubmit('Cambiar E-Mail')!!}
            </div>
        </div>
    </form>
@endsection

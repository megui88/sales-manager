@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">BAJA de Usuario: <strong>{{ $user->code }}</strong><br>CUIL/CUIT: <strong>{{ $user->cuil_cuit }}</strong></div>
                    <div class="panel-body">
                        <p>Se realizara la baja de <strong>{{ $user->last_name . ' ' . $user->name }}</strong> DNI <strong>{{ $user->document }}</strong>:</p>
                        <form action="/users/disenrolled/{{ $user->id }}" method="post">

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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

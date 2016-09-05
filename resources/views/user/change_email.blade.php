@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Alta</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="post" action="{{ url('/users/email') . '/' . $user->id }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ !empty($errors->getBags()) ? old('email') : $user->email }}">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

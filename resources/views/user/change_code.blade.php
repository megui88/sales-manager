@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Alta</div>
                    <div class="panel-body">
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
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-user"></i> Cambiar Codigo
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

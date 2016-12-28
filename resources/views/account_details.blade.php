@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Detalle de cuenta</div>

                    <div class="panel-body">
                        <p>Por favor indique el periodo de inicio y fin para mostrar un detalle de su cuenta</p>
                        <form method="get" action="/details" name="tests">

                            <div class="form-group{{ !empty($errors->getBags()) ? ' has-error' : '' }}">
                                <div class="col-md-6">
                                    <label for="init" class="col-md-4 control-label">Inicio</label>
                                    {!! \App\Helpers\BladeHelpers::sellPeriodSelect(5, old('period'), 'init') !!}
                                    @if ($errors->has('init'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('init') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                @if(! in_array(Auth::user()->role,[\App\Services\BusinessCore::MEMBER_ROLE, \App\Services\BusinessCore::VENDOR_ROLE]))
                                    <div class="col-md-6  control-label">
                                        <label for="member" class="col-md-4 control-label">Socio</label>
                                        <input id="member" type="text" class="form-control" name="member" required value="{{old('member')}}">
                                        <input id="member_id" type="hidden" class="form-control" name="member_id" value="{{old('member_id')}}">

                                        @if ($errors->has('member_id'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('member_id') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <label for="done" class="col-md-4 control-label">Fin</label>
                                    {!! \App\Helpers\BladeHelpers::sellPeriodSelect(5, old('period'), 'done') !!}
                                    @if ($errors->has('done'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('done') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-12  control-label" style="text-align: right">
                                    <p>
                                        {!! \App\Helpers\BladeHelpers::buttonSubmit('Ir al detalle',null,'')!!}
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

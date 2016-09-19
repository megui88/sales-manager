@extends('user.change_layout')
@section('form_title')
    Cambiar CBU
@endsection
@section('form_content')
    <form class="form-horizontal" role="form" method="post" action="{{ url('/users/cbu') . '/' . $user->id }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
            <label for="document" class="col-md-4 control-label">DNI</label>

            <div class="col-md-6">
                <input id="document" type="number" class="form-control" name="document" value="{{ !empty($errors->getBags()) ? old('document') : $user->document }}">
                @if ($errors->has('document'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('document') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('cuil_cuit') ? ' has-error' : '' }}">
            <label for="cuil_cuit" class="col-md-4 control-label">Cuil/Cuit</label>

            <div class="col-md-6">
                <input id="cuil_cuit" type="number" class="form-control" name="cuil_cuit" value="{{ !empty($errors->getBags()) ? old('cuil_cuit') : $user->cuil_cuit }}">
                @if ($errors->has('cuil_cuit'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('cuil_cuit') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('cbu') ? ' has-error' : '' }}">
            <label for="cbu" class="col-md-4 control-label">CBU</label>

            <div class="col-md-6">
                <input id="cbu" type="number" class="form-control" name="cbu" value="{{ !empty($errors->getBags()) ? old('cbu') : $user->cbu }}">
                @if ($errors->has('cbu'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('cbu') }}</strong>
                                    </span>
                @endif
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                {!! \App\Helpers\BladeHelpers::buttonSubmit('Dar de alta CBU')!!}
            </div>
        </div>
    </form>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Alta</div>
                    <form class="form-horizontal" role="form" method="post" action="{{ url('/register/provider') }}">
                        {{ csrf_field() }}

                        <div class="panel-body table-bordered">


                            <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }} hidden">
                                <label for="role" class="col-md-4 control-label">Tipo de Rol</label>

                                <div class="col-md-6">
                                    <select id="role" name="role" class="form-control">
                                        <option value="{{\App\Services\BusinessCore::VENDOR_ROLE}}"
                                                @if(old('role') == \App\Services\BusinessCore::VENDOR_ROLE) selected="selected" @endif
                                        >{{  \App\Services\BusinessCore::VENDOR_ROLE }}</option>
                                    </select>
                                    @if ($errors->has('role'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Nombre</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-md-4 control-label">Apellido</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                <label for="code" class="col-md-4 control-label">Codigo de proveedor</label>

                                <div class="col-md-6">
                                    <input id="code" type="text" class="form-control" name="code" value="{{ old('code') }}">

                                    @if ($errors->has('code'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('code_confirmation') ? ' has-error' : '' }}">
                                <label for="code-confirm" class="col-md-4 control-label">Codigo de proveedor</label>

                                <div class="col-md-6">
                                    <input id="code-confirm" type="text" class="form-control" name="code_confirmation" value="{{ old('code_confirmation') }}">

                                    @if ($errors->has('code_confirmation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('code_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">Correo Electronico</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email_confirmation') ? ' has-error' : '' }}">
                                <label for="email-confirm" class="col-md-4 control-label">Confirmar Correo Electronico</label>

                                <div class="col-md-6">
                                    <input id="email-confirm" type="email" class="form-control" name="email_confirmation" value="{{ old('email_confirmation') }}">

                                    @if ($errors->has('email_confirmation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Contraseña</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password-confirm" class="col-md-4 control-label">Confirmar Contraseña</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="panel-body">

                            <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                <label for="document" class="col-md-4 control-label">Documento</label>

                                <div class="col-md-6">
                                    <input id="document" type="text" class="form-control" name="document" value="{{ old('document') }}">

                                    @if ($errors->has('document'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('document') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                <label for="address" class="col-md-4 control-label">Dirección</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}">

                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                                <label for="location" class="col-md-4 control-label">Localidad</label>

                                <div class="col-md-6">
                                    <input id="location" type="text" class="form-control" name="location" value="{{ old('location') }}">

                                    @if ($errors->has('location'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label for="phone" class="col-md-4 control-label">Telefono</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}">

                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('internal_phone') ? ' has-error' : '' }}">
                                <label for="internal_phone" class="col-md-4 control-label">Interno Telefonico</label>

                                <div class="col-md-6">
                                    <input id="internal_phone" type="text" class="form-control" name="internal_phone" value="{{ old('internal_phone') }}">

                                    @if ($errors->has('internal_phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('internal_phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('cellphone') ? ' has-error' : '' }}">
                                <label for="cellphone" class="col-md-4 control-label">Celular</label>

                                <div class="col-md-6">
                                    <input id="cellphone" type="text" class="form-control" name="cellphone" value="{{ old('cellphone') }}">

                                    @if ($errors->has('cellphone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('cellphone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('birth_date') ? ' has-error' : '' }}">
                                <label for="birth_date" class="col-md-4 control-label">Fecha de Nacimiento</label>

                                <div class="col-md-6">
                                    <input id="birth_date" type="date" class="form-control" name="birth_date" value="{{ old('birth_date') }}">

                                    @if ($errors->has('birth_date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('birth_date') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('cuil_cuit') ? ' has-error' : '' }}">
                                <label for="cuil_cuit" class="col-md-4 control-label">Cuil/Cuit</label>

                                <div class="col-md-6">
                                    <input id="cuil_cuit" type="text" class="form-control" name="cuil_cuit" value="{{ old('cuil_cuit') }}">

                                    @if ($errors->has('cuil_cuit'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('cuil_cuit') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('fantasy_name') ? ' has-error' : '' }}">
                                <label for="fantasy_name" class="col-md-4 control-label">Nombre de Fantasia</label>

                                <div class="col-md-6">
                                    <input id="fantasy_name" type="text" class="form-control" name="fantasy_name" value="{{ old('fantasy_name') }}">

                                    @if ($errors->has('fantasy_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('fantasy_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('business_name') ? ' has-error' : '' }}">
                                <label for="business_name" class="col-md-4 control-label">Razon social</label>

                                <div class="col-md-6">
                                    <input id="business_name" type="text" class="form-control" name="business_name" value="{{ old('business_name') }}">

                                    @if ($errors->has('business_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('business_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('web') ? ' has-error' : '' }}">
                                <label for="web" class="col-md-4 control-label">Pagina Web</label>

                                <div class="col-md-6">
                                    <input id="web" type="text" class="form-control" name="web" value="{{ old('web') }}">

                                    @if ($errors->has('web'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('web') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    {!! \App\Helpers\BladeHelpers::buttonSubmit('Registrarme', 'register')!!}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

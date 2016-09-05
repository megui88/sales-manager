@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Usuario</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12" style="text-align: right">
                                <p style="@if(! \App\Helpers\BladeHelpers::isMemberDisenrolled($user)) display:none @endif">
                                    <a href="/users/disenrolled/{{  $user->id }}/print">Imprimir baja</a>
                                </p>
                                <p style="@if(\App\Helpers\BladeHelpers::isMemberDisenrolled($user)) display:none @endif">
                                    <a href="{{$user->role === \App\Services\BusinessCore::MEMBER_ROLE ?  '/members/income/' : '/providers/income/'}}{{  $user->id }}">Imprimir Alta</a> |
                                    <a href="/users/cbu/{{  $user->id }}">Gestionar CBU</a> |
                                    <a href="/users/email/{{  $user->id }}">Cambiar E-mail</a> |
                                    <a href="/users/code/{{  $user->id }}">Cambiar Codigo</a>
                                </p>
                            </div>
                        </div>
                        <form class="form-horizontal" role="form" method="post" action="{{ url('/profile/' . $user->id) }}">
                            <input type="hidden" name="_method" value="put" />
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('enable') ? ' has-error' : '' }}">
                                <label for="role" class="col-md-4 control-label">Activo</label>
                                <div class="col-md-6">
                                    <select id="enable" name="enable" class="form-control" {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>
                                        <option value='1'
                                                @if($user->enable == 1) selected="selected" @endif
                                        >Activo</option>
                                        <option value='0'
                                                @if($user->enable == 0) selected="selected" @endif
                                        >Inactivo</option>
                                    </select>
                                    @if ($errors->has('enable'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('enable') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                                <label for="role" class="col-md-4 control-label">Tipo de Rol</label>

                                <div class="col-md-6">
                                    <select id="role" name="role" class="form-control" {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>
                                        <option value="{{\App\Services\BusinessCore::MEMBER_ROLE}}"
                                                @if(old('role') == \App\Services\BusinessCore::MEMBER_ROLE or ( !$errors->has('role') and $user->role == \App\Services\BusinessCore::MEMBER_ROLE)) selected="selected" @endif
                                        >{{  \App\Services\BusinessCore::MEMBER_ROLE }}</option>
                                        <option value="{{\App\Services\BusinessCore::VENDOR_ROLE}}"
                                                @if(old('role') == \App\Services\BusinessCore::VENDOR_ROLE or ( !$errors->has('role') and  $user->role == \App\Services\BusinessCore::VENDOR_ROLE)) selected="selected" @endif
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
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

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
                                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ $user->last_name }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                <label for="code" class="col-md-4 control-label">Codigo</label>

                                <div class="col-md-6">
                                    <input id="code" type="text" class="form-control" name="code" value="{{ $user->code  }}" disabled >

                                    @if ($errors->has('code'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">Correo Electronico</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" disabled>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('credit_max') ? ' has-error' : '' }}">
                                <label for="credit_max" class="col-md-4 control-label">Credito Maximo</label>

                                <div class="col-md-6">
                                    <input id="credit_max" type="number" class="form-control" name="credit_max" value="{{ $user->credit_max }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

                                    @if ($errors->has('credit_max'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('credit_max') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                <label for="document" class="col-md-4 control-label">Documento</label>

                                <div class="col-md-6">
                                    <input id="document" type="text" class="form-control" name="document" value="{{ $user->document }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

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
                                    <input id="address" type="text" class="form-control" name="address" value="{{ $user->address }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

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
                                    <input id="location" type="text" class="form-control" name="location" value="{{ $user->location }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

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
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ $user->phone }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

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
                                    <input id="internal_phone" type="text" class="form-control" name="internal_phone" value="{{ $user->internal_phone }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

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
                                    <input id="cellphone" type="text" class="form-control" name="cellphone" value="{{ $user->cellphone }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

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
                                    <input id="birth_date" type="date" class="form-control" name="birth_date" value="{{ $user->birth_date }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

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
                                    <input id="cuil_cuit" type="text" class="form-control" name="cuil_cuit" value="{{ $user->cuil_cuit }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

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
                                    <input id="fantasy_name" type="text" class="form-control" name="fantasy_name" value="{{ $user->fantasy_name }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

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
                                    <input id="business_name" type="text" class="form-control" name="business_name" value="{{ $user->business_name }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

                                    @if ($errors->has('business_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('business_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('cbu') ? ' has-error' : '' }}">
                                <label for="cbu" class="col-md-4 control-label">CBU</label>

                                <div class="col-md-6">
                                    <input id="cbu" type="text" class="form-control" name="cbu" value="{{ $user->cbu }}" readonly  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

                                    @if ($errors->has('cbu'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('cbu') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('web') ? ' has-error' : '' }}">
                                <label for="web" class="col-md-4 control-label">Pagina Web</label>

                                <div class="col-md-6">
                                    <input id="web" type="text" class="form-control" name="web" value="{{ $user->web }}"  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

                                    @if ($errors->has('web'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('web') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('discharge_date') ? ' has-error' : '' }}">
                                <label for="discharge_date" class="col-md-4 control-label">Fecha de Alta</label>

                                <div class="col-md-6">
                                    <input id="discharge_date" type="date" class="form-control" name="discharge_date" value="{{ $user->discharge_date }}" readonly  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

                                    @if ($errors->has('discharge_date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('discharge_date') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('leaving_date') ? ' has-error' : '' }}">
                                <label for="leaving_date" class="col-md-4 control-label">Fecha de Baja</label>

                                <div class="col-md-6">
                                    <input id="leaving_date" type="date" class="form-control" name="leaving_date" value="{{ $user->leaving_date }}" readonly  {!! \App\Helpers\BladeHelpers::inputMemberDisenrolled($user)  !!}>

                                    @if ($errors->has('leaving_date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('leaving_date') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group" style="@if(\App\Helpers\BladeHelpers::isMemberDisenrolled($user)) display:none @endif">
                                <div class="col-md-6 col-md-offset-4">
                                    {!! \App\Helpers\BladeHelpers::buttonSubmit('Actualizar')!!}
                                    <a href="/users/disenrolled/{{  $user->id }}" class="btn btn-danger">
                                        <i class="fa fa-btn fa-user"></i> BAJA
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

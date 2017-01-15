@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Listado</div>

                    <div class="panel-body">
                        <form method="get">
                            <input type="hidden" name="filters[orderBy]" value="name">
                            @foreach($filters as $filter => $value)
                                @if(! in_array($filter,['role','enable' ,'q']))
                                    <input type="hidden" name="filters[{{ $filter }}]" value="{{ $value }}">
                                @endif
                            @endforeach
                            <div class="row">
                                <div class="col-xs-4">
                                    <h5>Rol: </h5>
                                    <label>Todos <input type="radio" name="filters[role]" id="role_0" value="" onchange="this.form.submit()" @if(isset($filters['role']) and empty($filters['role']))checked="checked" @endif</label>
                                    <label>Proveedores <input type="radio" name="filters[role]" id="role_1" value="{{\App\Services\BusinessCore::VENDOR_ROLE}}" onchange="this.form.submit()"@if(isset($filters['role']) and \App\Services\BusinessCore::VENDOR_ROLE == $filters['role'])checked="checked" @endif</label>
                                    <label>Socios <input type="radio" name="filters[role]" id="role_2" value="{{\App\Services\BusinessCore::MEMBER_ROLE}}" onchange="this.form.submit()"@if(isset($filters['role']) and \App\Services\BusinessCore::MEMBER_ROLE == $filters['role'])checked="checked" @endif</label>
                                </div>
                                <div class="col-xs-4">
                                    <h5>Estado </h5>
                                    <label>Todos <input type="radio" name="filters[enable]" id="enable_0" value="" onchange="this.form.submit()"@if(isset($filters['enable']) and empty($filters['enable']))checked="checked"@endif</label>
                                    <label>Inactivos <input type="radio" name="filters[enable]" id="enable_1" value="false" onchange="this.form.submit()"@if(isset($filters['enable']) and 'false' == $filters['enable'])checked="checked"@endif</label>
                                    <label>Activos <input type="radio" name="filters[enable]" id="enable_2" value="true" onchange="this.form.submit()"@if(isset($filters['enable']) and 'true' == $filters['enable'])checked="checked"@endif</label>
                                </div>
                                <div class="col-xs-4">
                                    <a href="/users-new" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a>
                                </div>
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <input type="text" id="q" name="filters[q]" class="form-control" placeholder="Ingrese nombre..." value="{{ !empty($filters['q']) ? $filters['q'] : '' }}">
                                        <span class="input-group-btn">
                                            {!! \App\Helpers\BladeHelpers::buttonSubmit('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', 'users_search')!!}
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </form>
                        <hr>
                        <table class="table">
                            <thead>
                            <td> Codigo </td>
                            <td> Nombre </td>
                            <td> Apellido </td>
                            <td> Email </td>
                            <td> Nombre de fantasia </td>
                            <td> Role </td>
                            <td> Empresa </td>
                            <td> Sede </td>
                            <td> </td>
                            </thead>
                            @foreach($users as $user)
                                <tr @if(\App\Helpers\BladeHelpers::isMemberDisenrolled($user)) class="danger" @endif>
                                    <td> {{ $user->code }} </td>
                                    <td> {{ $user->name }} </td>
                                    <td> {{ $user->last_name }} </td>
                                    <td> {{ \App\Helpers\BladeHelpers::email($user->email)}} </td>
                                    <td> {{ $user->fantasy_name }} </td>
                                    <td> {{ $user->role }} </td>
                                    <td> {{ \App\Company::where('id','=',$user->company_id)->first()->name}} </td>
                                    <td> {{ \App\Headquarters::where('id','=',$user->headquarters_id)->first()->name}} </td>
                                    <td> <a href="/profile/{{ $user->id }}" id="profile_{{ $user->code }}" title="Editar usuario">
                                            @if(! $user->enable)
                                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                            @else
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="pagination"> {{ $users->links() }} </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var inputFocus = 'q';
    </script>
@endsection

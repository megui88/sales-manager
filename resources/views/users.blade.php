@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Listado</div>

                    <div class="panel-body">
                        <form method="get">
                            <input type="hidden" name="filters[orderBy]" value="name">
                            @foreach($filters as $filter => $value)
                                <input type="hidden" name="filters[{{ $filter }}]" value="{{ $value }}">
                            @endforeach
                            <p>
                                <label>Todos <input type="radio" name="filters[role]" id="role_0" value="" onchange="this.form.submit()"
                                    @if(isset($filters['role']) and empty($filters['role']))
                                        checked="checked"
                                    @endif</label>
                                <label>Proveedores <input type="radio" name="filters[role]" id="role_1" value="1" onchange="this.form.submit()"
                                    @if(isset($filters['role']) and 1 == $filters['role'])
                                        checked="checked"
                                    @endif</label>
                                <label>Socios <input type="radio" name="filters[role]" id="role_2" value="2" onchange="this.form.submit()"
                                    @if(isset($filters['role']) and 2 == $filters['role'])
                                        checked="checked"
                                    @endif</label>
                            </p>
                        <div class="input-group">
                            <input type="text" name="filters[q]" class="form-control" placeholder="Ingrese nombre..."
                            value="{{ !empty($filters['q']) ? $filters['q'] : '' }}">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                </button>
                            </span>
                        </div>
                        </form>
                        <table class="table">
                            <thead>
                            <td> Codigo </td>
                            <td> Nombre </td>
                            <td> Apellido </td>
                            <td> Email </td>
                            <td> Role </td>
                            </thead>
                            @foreach($users as $user)
                                <tr>
                                    <td> {{ $user->code }} </td>
                                    <td> {{ $user->name }} </td>
                                    <td> {{ $user->last_name }} </td>
                                    <td> {{ $user->email }} </td>
                                    <td> {{ $roles[$user->role] }} </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="pagination"> {{ $users->links() }} </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

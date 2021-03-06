@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Axoft (Tango gestion)</div>

                    <div class="panel-body">
                        <form method="post" action="/axoft_import/file" name="tests"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="sale_mode" value="{{ \App\Sale::CURRENT_ACCOUNT }}">
                            <div class="form-group{{ !empty($errors->getBags()) ? ' has-error' : '' }}">

                                <div class="col-md-12">
                                    <label for="description" class="col-md-4 control-label">Descripción</label>
                                    <input id="description" type="text" class="form-control" name="description" data-sale=true required value="{{old('description')}}">

                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-12" >
                                    <label for="file" class="col-md-4 control-label">Archivo</label>
                                    <input id="file" type="file" name="axoft-import-file" class="form-control" >
                                    @if ($errors->has('axoft-import-file'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('axoft-import-file') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-12" style="text-align: right; top:5px">
                                    <p>
                                        {!! \App\Helpers\BladeHelpers::buttonSubmit('Importar')!!}
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Ultimas migraciones</div>

                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>

                            <tr>
                                <td>Nombre</td>
                                <td>Descripcion</td>
                                <td>fecha</td>
                                <td></td>
                            </tr>
                            </thead>
                            @foreach($migrations as $migrate)
                                <tr @if($migrate->status == \App\Contract\States::STOPPED) class="danger"@endif>
                                    <td>{{ $migrate->name }}</td>
                                    <td>{{ $migrate->description }}</td>
                                    <td>{{ \App\Helpers\BladeHelpers::date($migrate->created_at) }}</td>
                                    <td><a href="/migrate/file/{{ $migrate->id }}/errors">Errores</a></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var inputFocus = 'payer';

    </script>
@endsection
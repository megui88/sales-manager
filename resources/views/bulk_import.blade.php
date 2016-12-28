@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Importacion masiva</div>

                    <div class="panel-body">
                        <form method="post" action="/bulk_import/file" name="tests"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="sale_mode" value="{{ \App\Sale::CURRENT_ACCOUNT }}">
                            <div class="form-group{{ !empty($errors->getBags()) ? ' has-error' : '' }}">

                                <div class="col-md-12">
                                    <label for="description" class="col-md-4 control-label">Descripcion</label>
                                    <input id="description" type="text" class="form-control" name="description" data-sale=true required value="{{old('description')}}">

                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-12" >
                                    <label for="file" class="col-md-4 control-label">Archivo</label>
                                    <input id="file" type="file" name="bulk-import-file" class="form-control" >
                                    @if ($errors->has('bulk-import-file'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('bulk-import-file') }}</strong>
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
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <a href="{{ url('/assets/img/descargar_csv.png') }}" data-toggle="lightbox" data-title="Como descargar la plantilla terminada" data-footer="Archivo -> Descargar Como -> Valores separados por comas (.csv, hoja actual)">
                        <img src="{{ url('/assets/img/descargar_csv.png') }}" alt="captura de pantalla" class="img-fluid">
                    </a>
                    <div class="caption">
                        <h3>Importación masiva</h3>
                        <p>La importación masiva consta de un archivo que solo posee datos necesarios para incorporar al sistema ventas en cuentas corrientes.<br>
                            En la primer columna se ingresa el <strong>codigo de usuario</strong>, en la segunda el <strong>codigo del proveedor</strong>, en la tercera la cantidad de <strong>cuotas</strong> y en la cuarta y ultima el <strong>importe</strong>.
                        </p>
                        <p>
                            Es importante entender que el <strong>importe</strong> es el total que sera dividido en <strong>cuotas</strong>.
                        </p>
                        <p>
                            <a class="btn btn-link" href="https://docs.google.com/a/mutualmp.com.ar/spreadsheets/d/14yP0f54yYENUone6aLbl9lqBtTGhGBU9CTtlUQMadIk/edit?usp=sharing" target="_blank">
                                Archivo de Ejemplo
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var inputFocus = 'payer';

    </script>
@endsection
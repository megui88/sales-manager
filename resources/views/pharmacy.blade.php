@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Farmacia</div>

                    <div class="panel-body">
                        <form method="post" action="/pharmacy/file" name="tests"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="sale_mode" value="{{ \App\Sale::PHARMACY_SELLING }}">
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
                                    <input id="file" type="file" name="pharmacy-file" class="form-control" >
                                    @if ($errors->has('pharmacy-file'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('pharmacy-file') }}</strong>
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
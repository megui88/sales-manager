@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Bienvenido</div>

                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>N° de Socio</td>
                            <td>Numero de ventas</td>
                            <td>Consumos del mes en curso</td>
                            <td>Acreditaciones del periodo actual</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr style="text-align: center">
                            <td>{{ Auth::user()->code }}</td>
                            <td>{{ $totalSales }}</td>
                            <td>{{ \App\Helpers\BladeHelpers::import($dueImport) }}</td>
                            <td>{{ \App\Helpers\BladeHelpers::import($accreditImport) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

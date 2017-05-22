@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Usuarios al limite para el periodo {{ $period->uid }}</div>

                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>

                            <tr>
                                <td>Nombre</td>
                                <td>{{ $period->uid }}</td>
                                <td>{{ \App\Services\BusinessCore::nextPeriod($period->uid) }}</td>
                                <td>Accion</td>
                                <td></td>
                            </tr>
                            </thead>
                            @foreach($data as $row)

                                <tr @if($row['exceeded'])
                                    class="danger"
                                        @endif>
                                    <td>{{ $row['payer']->fullName() }}</td>
                                    <td>{{ $row['amount'] }}</td>
                                    <td>{{ $row['next_period'] }}</td>
                                    <td><a href="/details/{{$row['payer']->id}}/{{ $period->uid }}/{{ $period->uid }}">ver detalle</a></td>
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

@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="get">
            <div class="row">
                <label for="period" class="col-md-4 control-label">Periodo: <strong>{{$period}}</strong></label>
                <div class="col-md-5">
                    {!! \App\Helpers\BladeHelpers::sellPeriodSelect(15, $period) !!}
                </div>
                <div class="col-md-1">

                    {!! \App\Helpers\BladeHelpers::buttonSubmit('Ir')!!}
                </div>
            </div>
            <div class="row">
                <hr>
            </div>
        </form>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td>Nombre de Fantasia</td>
                    <td>A cobrar</td>
                    <td>A pagar</td>
                    <td>Gastos Administrativos</td>
                </tr>
                </thead>
                <tbody>
                <?php
                $due = 0;
                $accredit = 0;
                $income = 0;
                ?>
                @foreach($rows as $row)
                    @if($row['due'] == 0 and $row['accredit'] == 0 and $row['income'] == 0)
                        <?php continue; ?>
                    @endif
                    <tr style="text-align: right">
                        <td style="text-align: left">{{ $row['name']}}</td>
                        <td>{{ \App\Helpers\BladeHelpers::import($row['due']) }}</td>
                        <td>{{ \App\Helpers\BladeHelpers::import($row['accredit'])}}</td>
                        <td>{{ \App\Helpers\BladeHelpers::import($row['income'])}}</td>
                    </tr>
                    <?php
                    $due += $row['due'];
                    $accredit += $row['accredit'];
                    $income += $row['income'];
                    ?>
                @endforeach
                </tbody>
                <tfoot style="text-align: right ;">
                <tr>
                    <td></td>
                    <td style="border: 2px solid black">A cobrar</td>
                    <td style="border: 2px solid black">A pagar</td>
                    <td style="border: 2px solid black">Gastos Administrativos</td>
                </tr>
                <tr style="text-align: right ;border: 2px solid black">
                    <td style="border: 2px solid black"><strong>Totales:</strong></td>
                    <td style="border: 2px solid black">{{ \App\Helpers\BladeHelpers::import($due) }}</td>
                    <td style="border: 2px solid black">{{ \App\Helpers\BladeHelpers::import($accredit) }}</td>
                    <td style="border: 2px solid black">{{ \App\Helpers\BladeHelpers::import($income) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
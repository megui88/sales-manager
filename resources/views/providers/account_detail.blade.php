@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row hidden-print text-center">
            <div class="col-xs-4">
                <a onclick="detailsPrint()" class="btn btn-lg btn-info">Imprimir</a>
            </div>
            <div class="col-xs-4">
            </div>
            <div class="col-xs-offset-4 col-xs-4">
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Detalle de cuenta del socio <strong>{{ $user->code }} | {{ $user->last_name . ', ' . $user->name }}</strong><br>
                        periodos: <strong>@if($periodInit === $periodDone) {{$periodInit}} @else {{$periodInit . ' - ' . $periodDone}} @endif</strong></div>

                    <div class="panel-body">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                            @foreach($periods as $period => $items)
                                <?php $total = 0; ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading{{$period}}">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$period}}" aria-expanded="false" aria-controls="collapse{{$period}}">
                                                Periodo <strong>{{$period}}</strong>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse{{$period}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$period}}" style="font-size: 10px">
                                        <div class="panel-body">
                                            <table class="table table-bordered">
                                                <thead style="text-align: center; font-weight: bolder">
                                                <!--td>Tipo</td-->
                                                <td>Comprobante</td>
                                                <td>Socio</td>
                                                <!--td>Descripción</td-->
                                                <td>Numero de Cuota</td>
                                                <td>Importe de Cuota</td>
                                                </thead>
                                                <tbody>
                                                @foreach($items['dues'] as $due)
                                                    <tr style="text-align: right">
                                                        <td><a href="/sales/{{ $due->sale->id }}" class="btn btn-link"  style="font-size: 10px">{{ $due->sale->id }}</a></td>
                                                        <td>{{ \App\Helpers\BladeHelpers::UserCode($due->sale->collector_id)  }} {{ $due->sale->concept->name }}</td>
                                                        <td>{{ $due->number_of_quota }} / {{ $due->sale->installments }}</td>
                                                        <td>{{ \App\Helpers\BladeHelpers::import($due->amount_of_quota) }}</td>
                                                    </tr>
                                                    <?php $total -= $due->amount_of_quota; ?>
                                                @endforeach
                                                </tbody>
                                                <tbody>
                                                @foreach($items['accredits'] as $accredit)
                                                    <tr style="text-align: right;">
                                                    <!--td style="text-align: center">{{ $accredit->sale->concept->name }}</td-->
                                                        <td><a href="/sales/{{ $accredit->sale->id }}" class="btn btn-link"  style="font-size: 10px">{{ $accredit->sale->id }}</a></td>
                                                        <td>{{  $accredit->sale->payer->code . ' - ' .$accredit->sale->payer->fullName() }}</td>
                                                        <!--td style="text-align: center">{{ $accredit->sale->description }}</-td-->
                                                        <td>{{ $accredit->number_of_quota }} / {{ $accredit->sale->installments }}</td>
                                                        <td>{{ \App\Helpers\BladeHelpers::import($accredit->amount_of_quota) }}</td>
                                                    </tr>
                                                    <?php $total += $accredit->amount_of_quota; ?>
                                                @endforeach
                                                </tbody>
                                                <tfoot  style="text-align: right; font-weight: bold">
                                                <td>Periodo: {{$period}}</td>
                                                <td></td>
                                                <td></td>
                                                <td>Total:</td>
                                                <td>{{ \App\Helpers\BladeHelpers::import($total)}}</td>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

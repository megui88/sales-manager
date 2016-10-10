@extends('layouts.app')

@section('content')
    <div class="container">
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
                                    <div id="collapse{{$period}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$period}}">
                                        <div class="panel-body">
                                            <table class="table table-bordered">
                                                <thead style="text-align: center; font-weight: bolder">
                                                <td>Tipo</td>
                                                <td>Comprobante</td>
                                                <td>Descripción</td>
                                                <td>Numero de Cuota</td>
                                                <td>Importe de Cuota</td>
                                                </thead>
                                                <tbody>
                                                @foreach($items['dues'] as $due)
                                                    <tr style="text-align: right">
                                                        <td style="text-align: center">{{ $due->sale->concept->name }}</td>
                                                        <td>{{ $due->sale->id }}</td>
                                                        <td style="text-align: center">{{ $due->sale->description }}</td>
                                                        <td>{{ $due->number_of_quota }}</td>
                                                        <td>{{ \App\Helpers\BladeHelpers::import($due->amount_of_quota) }}</td>
                                                    </tr>
                                                    <?php $total += $due->amount_of_quota; ?>
                                                @endforeach
                                                </tbody>
                                                <tbody>
                                                @foreach($items['accredits'] as $accredit)
                                                    <tr style="text-align: right">
                                                        <td style="text-align: center">{{ $accredit->sale->concept->name }}</td>
                                                        <td>{{ $accredit->sale->id }}</td>
                                                        <td style="text-align: center">{{ $accredit->sale->description }}</td>
                                                        <td>{{ $accredit->number_of_quota }}</td>
                                                        <td>{{ \App\Helpers\BladeHelpers::import($accredit->amount_of_quota) }}</td>
                                                    </tr>
                                                    <?php $total -= $accredit->amount_of_quota; ?>
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

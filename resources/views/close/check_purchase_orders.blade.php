@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Cierre de periodo</div>

                    <form class="form" action="/close/close" method="post">
                        <div class="panel-body">
                            <h1> Se </h1>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Ordenes para anular</div>
                                        <div class="panel-body">
                                            <table class="table table-bordered table-responsive">
                                                <thead>
                                                <tr>
                                                    <td>Comprador</td>
                                                    <td>Vendedor</td>
                                                    <td>Periodo</td>
                                                    <td>C</td>
                                                    <td>Importe</td>
                                                    <td></td>
                                                </tr>
                                                </thead>
                                                @foreach($purchase_orders as $purchase_order)
                                                    <tr class="danger">
                                                        <td>{{ $purchase_order->payer->code  }}</td>
                                                        <td>{{ $purchase_order->collector->code }}</td>
                                                        <td>{{ $purchase_order->period }}</td>
                                                        <td>{{ $purchase_order->installments }}</td>
                                                        <td>{{ $purchase_order->amount }}</td>
                                                        <td><a target="_blank" href="/purchase_orders/{{$purchase_order->id}}"><i class="fa fa-open" aria-hidden="true"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <button class="btn btn-lg btn-danger">Continuar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
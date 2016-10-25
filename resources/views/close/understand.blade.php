@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Cierre de periodo</div>

                    <form class="form" action="/close/check_steps" method="post">
                        <div class="panel-body">
                            <h1> A continuación debera marcar si realiazo todas las tareas</h1>
                            <div class="row">

                                <div class="col-xs-12">
                                    <label for="import-pharmacy">
                                        <input id="import-pharmacy" name="import-pharmacy" type="checkbox" class="form-group">
                                        SI
                                    </label>
                                    Importo las deudas del sistema Farmatronic del corriente periodo.
                                </div>

                                <div class="col-xs-12">
                                    <label for="import-sport-club">
                                        <input id="import-sport-club" name="import-sport-club" type="checkbox" class="form-group">
                                        SI
                                    </label>
                                    Realizo la importación masiva de Sport Club.
                                </div>

                                <div class="col-xs-12">
                                    <label for="import-seguro">
                                        <input id="import-seguro" name="import-seguro" type="checkbox" class="form-group">
                                        SI
                                    </label>
                                    Realizo la importación masiva del Seguro.
                                </div>

                                <div class="col-xs-12">
                                    <label for="import-osetra">
                                        <input id="import-osetra" name="import-osetra" type="checkbox" class="form-group">
                                        SI
                                    </label>
                                    Realizo la importación masiva de Osetra.
                                </div>

                                <div class="col-xs-12">
                                    <label for="confirm-purchase_orders">
                                        <input id="confirm-purchase_orders" name="confirm-purchase_orders" type="checkbox" class="form-group">
                                        SI
                                    </label>
                                    Confirmo las Ordenes de compras entregadas por el Proveedor (pasadas dos periodos seran anuladas).
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
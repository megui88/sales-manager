@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Cierre de periodo</div>

                    <div class="panel-body">
                        <h1>Usuario <strong>{{ Auth::user()->name . ' ' . Auth::user()->last_name }}</strong>:</h1>
                        <h2 > Entiende que esta cerrando el periodo <strong>{{ $current }}</strong> para iniciar
                            el periodo <strong>{{ $nextPeriod }}</strong></h2>
                        <h4>Una vez cerrado el periodo no se puede re-abrir</h4>
                        <p>
                        <form class="form" action="/close/understand" method="post">
                            <button class="btn btn-lg btn-danger">Entiendo</button>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
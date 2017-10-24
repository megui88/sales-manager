@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="post" action="/satellite">
            <div class="row">
                <label for="period" class="col-md-4 control-label">Periodo: <strong>{{$period}}</strong></label>
                <div class="col-md-5">
                    {!! \App\Helpers\BladeHelpers::sellPeriodSelect(26, $period) !!}
                </div>
                <div class="col-md-1">

                    {!! \App\Helpers\BladeHelpers::buttonSubmit('Descargar')!!}
                </div>
            </div>
            <div class="row">
                <hr>
            </div>
        </form>
    </div>
@endsection
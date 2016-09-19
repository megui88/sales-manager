@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">@yield('confirm_title')</div>
                    <div class="panel-body">@yield('confirm_body')</div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <h1>&nbsp;</h1>
                        <h1>&nbsp;</h1>
                        <p>____________________________<br>Firma del Afiliado</p>
                    </div>
                    <div class="col-xs-2">
                    </div>
                    <div class="col-xs-6">
                        <h1>&nbsp;</h1>
                        <h1>&nbsp;</h1>
                        <p>____________________________<br>
                            {{ Auth::user()->name . ' ' . Auth::user()->last_name }}<br>
                            Empleado de {{ env('BUSINESS_NAME') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

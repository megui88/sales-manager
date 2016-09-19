@extends('layouts.app')

@section('content')
    <div class="container">
        {!! \App\Helpers\BladeHelpers::goBack()  !!}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">@yield('form_title')</div>
                    <div class="panel-body">
                        @yield('form_content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

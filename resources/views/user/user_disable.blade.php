@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Usuario Inactivo</div>

                <div class="panel-body">
                    Estimado <strong>{{ $user->last_name }} {{ $user->name }}</strong> su acceso debe ser autorizado por personal administrativo.<br>
                    Si lo desea puede contactarlos al correo <strong>{{ env('EMAIL_CONTACT') }}</strong>.

                    <p>
                        Atte. {{ env('ATTE_SIGNATURE') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

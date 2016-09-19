@extends('user.confirm_layout')
@section('confirm_title')
    BAJA de Usuario: <strong>{{ $user->code }}</strong> <br> CUIL/CUIT: <strong>{{ $user->cuil_cuit }}</strong>
@endsection
@section('confirm_body')
    <p>Yo <strong>{{ $user->last_name . ' ' . $user->name }}</strong> DNI <strong>{{ $user->document }}</strong>:</p>
    <p>
        Por medio de este comprobante solicito la baja en el sistema de la Mutual: <strong>{{ env('BUSINESS_NAME') }}</strong><br> CUIT: <strong>{{ env('BUSINESS_CUIT') }}</strong>.
        A partir de la fecha {{ $user->leaving_date->format('d-m-Y') }}
    </p>
@endsection

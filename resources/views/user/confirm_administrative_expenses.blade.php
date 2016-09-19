@extends('user.confirm_layout')
@section('confirm_title')
    Actualización del concepto "Gastos Adminsitrativos" para el Usuario: <strong>{{ $user->code }}</strong><br>CUIL/CUIT: <strong>{{ $user->cuil_cuit }}</strong>
@endsection
@section('confirm_body')
    <p>Yo <strong>{{ $user->last_name . ' ' . $user->name }}</strong> DNI <strong>{{ $user->document }}</strong>:</p>
    <p>
        Por medio de este comprobante afirmo que estoy conciente del cargo por el concepto "Gastos Adminsitrativos"
        que sera descontando de cada venta en el que yo cumpla el rol de vendedor.
        El recaudador <strong>{{ env('BUSINESS_NAME') }}</strong> CUIT: <strong>{{ env('BUSINESS_CUIT') }}</strong>
        lo descontara de forma automatica.
    </p>
@endsection

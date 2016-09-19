@extends('user.confirm_layout')
@section('confirm_title')
    Actualización de CBU Usuario: <strong>{{ $user->code }}</strong><br>CUIL/CUIT: <strong>{{ $user->cuil_cuit }}
@endsection
@section('confirm_body')
    <p>Yo <strong>{{ $user->last_name . ' ' . $user->name }}</strong> DNI <strong>{{ $user->document }}</strong>:</p>
    <p>
        Por medio de este comprobante afirmo que el <strong>CBU N° {{ $user->cbu }}</strong>
        corresponde a mi cuenta bancaria declarada.
        Puede ser usado por <strong>{{ env('BUSINESS_NAME') }}</strong> CUIT: <strong>{{ env('BUSINESS_CUIT') }}</strong>
        para transferirme dinero y en caso de perder vigencia me comprometo a informarle a la mutual en su debido momento.
        De haber ocacionado daños o gastos por informar mal mi CBU, me hare responsable.
    </p>
@endsection

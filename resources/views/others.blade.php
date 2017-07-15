@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="post" action="/others">
            <div class="row">
                <label for="period" class="col-md-4 control-label">Periodo: </label>
                <div class="col-md-5">
                    {!! \App\Helpers\BladeHelpers::sellPeriodSelect(20, $period) !!}
                </div>
            </div>
            <div class="row">
                <label for="company_id" class="col-md-4 control-label">Empresa: </label>
                <div class="col-md-5">
                    <select id="company_id" name="company_id" class="form-control" >
                        @foreach(\App\Company::all() as $company)
                            <option value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">

                    {!! \App\Helpers\BladeHelpers::buttonSubmit('Descargar')!!}
                </div>
            </div>
        </form>
    </div>
@endsection
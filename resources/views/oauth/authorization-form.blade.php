@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel-heading">Desea authorizar a la aplicación <strong>{{$client->getName()}}</strong></div>

            <div class="panel-body">
                <div class="form-group">
                    <form method="post" action="{{route('oauth.authorize.post', $params)}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="client_id" value="{{$params['client_id']}}">
                        <input type="hidden" name="redirect_uri" value="{{$params['redirect_uri']}}">
                        <input type="hidden" name="response_type" value="{{$params['response_type']}}">
                        <input type="hidden" name="state" value="{{$params['state']}}">
                        <input type="hidden" name="scope" value="{{$params['scope']}}">

                        <button class="btn btn-primary btn-submit" type="submit" name="approve" value="1">Aprobar</button>
                        <button class="btn btn-danger btn-submit" type="submit" name="deny" value="1">Denegar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
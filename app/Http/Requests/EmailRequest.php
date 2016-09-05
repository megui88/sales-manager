<?php

namespace App\Http\Requests;


use App\User;

class EmailRequest extends Request
{

    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        $user = User::find(request()->segments()[2]);
        return [
          'email' => 'email|required|unique:users,email,' . $user->id . '|confirmed'
        ];
    }
}
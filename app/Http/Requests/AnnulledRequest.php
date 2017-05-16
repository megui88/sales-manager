<?php

namespace App\Http\Requests;


use App\User;

class CodeRequest extends Request
{

    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        $user = User::find(request()->segments()[2]);
        return [
            'code' => 'numeric|required|unique:users,code,' . $user->id . '|confirmed'
        ];
    }
}

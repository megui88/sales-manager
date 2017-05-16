<?php

namespace App\Http\Requests;


use App\User;

class CbuRequest extends Request
{

    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        $user = User::find(request()->segments()[2]);
        return [
            'document' => 'required|unique:users,document,' . $user->id,
            'cuil_cuit' => 'required|unique:users,cuil_cuit,' . $user->id,
            'cbu' => 'required|unique:users,cbu,' . $user->id . '|digits:22',
        ];
    }
}

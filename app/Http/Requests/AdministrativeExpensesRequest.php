<?php

namespace App\Http\Requests;


use App\User;

class AdministrativeExpensesRequest extends Request
{

    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        return [
          'administrative_expenses' => 'required|numeric|max:20|min:1|confirmed',
        ];
    }
}

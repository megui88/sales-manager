<?php

namespace App\Http\Requests;


class ConfirmOrderRequest extends Request
{

    public function authorize()
    {
        return \Auth::check();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_amount' => 'numeric|required',
            'order_id' => 'numeric|required|exists:sales,id',
        ];
    }
}

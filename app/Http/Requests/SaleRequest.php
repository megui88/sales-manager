<?php
/**
 * Created by PhpStorm.
 * User: megui
 * Date: 21/08/16
 * Time: 16:52
 */

namespace App\Http\Requests;


class SaleRequest extends Request
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
            'amount' => 'numeric|required',
            'charge' => 'numeric|required',
            'payer_id' => 'numeric|required',
            'collector_id' => 'numeric|required',
            'installments' => 'numeric|required',
            'sale_mode' => 'string|required',
            'description' => 'string|required',
            'state' => 'string|required',
            'first_due_date' => 'date',
            'period' => 'string|required',
        ];
    }
}
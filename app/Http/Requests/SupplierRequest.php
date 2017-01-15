<?php
/**
 * Created by PhpStorm.
 * User: megui
 * Date: 21/08/16
 * Time: 16:52
 */

namespace App\Http\Requests;


class SupplierRequest extends Request
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
            'charge' => 'numeric',
            'payer_id' => 'string|required',
            'installments' => 'numeric|required',
            'sale_mode' => 'string|required',
            'description' => 'string',
            'state' => 'string',
            'first_due_date' => 'date',
            'period' => 'string|required',
            'concept_id' => 'string|required',
        ];
    }
}
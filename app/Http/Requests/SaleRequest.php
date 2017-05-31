<?php
/**
 * Created by PhpStorm.
 * User: megui
 * Date: 21/08/16
 * Time: 16:52
 */

namespace App\Http\Requests;


use App\Sale;
use App\Services\BusinessCore;

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
            'charge' => 'numeric',
            'payer_id' => 'string|required',
            'collector_id' => 'string|required',
            'installments' => 'numeric|required',
            'sale_mode' => 'string|required',
            'description' => 'string',
            'state' => 'string',
            'first_due_date' => 'date',
            'period' => 'string|required',
            'concept_id' => 'string|required',
        ];
    }


    public function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->after(function () use ($validator) {

            $input = $validator->getData();
            $payer_id = $input['payer_id'];
            $period = $input['period'];

            $b = new BusinessCore();
            $current = $b->getUserDuesByPeriod($payer_id, $period);
            $amount = $b->calculateTheValueOfTheAmountOfEachInstallment($input['amount'], $input['installments'], 1);

            if (($current + $amount) > BusinessCore::CURRENT_MAX and $_SERVER['REQUEST_URI'] != '/credit_notes') {
                $validator->errors()->add('payer_id',
                    'El socio pose un gasto superior a ' . BusinessCore::CURRENT_MAX . ' en el periodo ' . $period);
            }
        });

        return $validator;
    }
}

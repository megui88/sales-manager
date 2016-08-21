<?php

namespace App\Http\Requests;

use App\Services\BusinessCore;

class UserProfileRequest extends Request
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
            'name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'document' => 'numeric',
            'address' => 'string|max:255|required_if:role,' . BusinessCore::VENDOR_ROLE,
            'location' => 'string|max:255|required_if:role,' . BusinessCore::VENDOR_ROLE,
            'phone' => 'numeric|required_if:role,' . BusinessCore::VENDOR_ROLE,
            'cellphone' => 'numeric|required_if:role,' . BusinessCore::VENDOR_ROLE,
            'internal_phone',
            'credit_max' => 'numeric|required_if:role,' . BusinessCore::MEMBER_ROLE,
            'birth_date' => 'date',
            'group_id' => 'numeric',
            'debit_automatic' => 'boolean',
            'cuil_cuit' => 'numeric|max:255|required_if:role,' . BusinessCore::VENDOR_ROLE,
            'fantasy_name' => 'string|max:255|required_if:role,' . BusinessCore::VENDOR_ROLE,
            'business_name' => 'string|max:255|required_if:role,' . BusinessCore::VENDOR_ROLE,
            'category_id' => 'numeric',
            'web' => 'string|max:255',
            'stand' => 'numeric',
            'discharge_date' => 'date',
            'leaving_date' => 'date',
            'cbu' => 'numeric',
            'role' => 'string',
            'enable' => 'boolean',
        ];
    }
}

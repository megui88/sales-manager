<?php
namespace App\Http\Requests;



class PharmacyFileRequest extends Request
{

    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        return [
            'description' => 'string|required',
            'pharmacy-file' => 'file|required|unique:migrates,checksum',
        ];
    }

    public function getValidatorInstance() {
        $validator = parent::getValidatorInstance();

        $validator->after(function() use ($validator) {
            $fileName = $_FILES['pharmacy-file']['name'];
            $aux = explode('.',$fileName);
            $ext = strtolower(array_pop($aux));
            if('lst' !== $ext){
                $validator->errors ()->add('pharmacy-file', 'Solo se acepta archivos LST');
            }
        });

        return $validator;
    }

}
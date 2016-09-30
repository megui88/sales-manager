<?php
namespace App\Http\Requests;



use App\Migrate;

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
            'pharmacy-file' => 'file|required',
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
            $file = Migrate::where('checksum','=',md5_file($_FILES['pharmacy-file']['tmp_name']))->first();
            if($file){
                $validator->errors ()->add('pharmacy-file', 'el archivo que intenta cargar ya fue cargado');
            }
        });

        return $validator;
    }

}
<?php
namespace App\Http\Requests;



use App\Migrate;

class AxoftImportFileRequest extends Request
{

    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        return [
            'description' => 'string|required',
            'axoft-import-file' => 'file|required',
        ];
    }

    public function getValidatorInstance() {
        $validator = parent::getValidatorInstance();

        $validator->after(function() use ($validator) {
            $fileName = $_FILES['axoft-import-file']['name'];
            $aux = explode('.',$fileName);
            $ext = strtolower(array_pop($aux));
            if('csv' !== $ext){
                $validator->errors ()->add('axoft-import-file', 'Solo se acepta archivos csv separados por punto y coma');
            }
            $file = Migrate::where('checksum','=',md5_file($_FILES['axoft-import-file']['tmp_name']))->first();
            if($file){
                $validator->errors ()->add('axoft-import-file', 'El archivo que intenta cargar ya fue cargado');
            }
        });

        return $validator;
    }

}
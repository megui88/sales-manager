<?php

namespace App\Http\Requests;


use App\Migrate;

class BulkImportFileRequest extends Request
{

    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        return [
            'description' => 'string|required',
            'bulk-import-file' => 'file|required',
        ];
    }

    public function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->after(function () use ($validator) {
            $fileName = $_FILES['bulk-import-file']['name'];
            $aux = explode('.', $fileName);
            $ext = strtolower(array_pop($aux));
            if ('csv' !== $ext) {
                $validator->errors()->add('bulk-import-file', 'Solo se acepta archivos csv separados por coma');
            }
            $file = Migrate::where('checksum', '=', md5_file($_FILES['bulk-import-file']['tmp_name']))
                ->whereNotIn('status', [Migrate::ANNUL, Migrate::DELETE])
                ->first();
            if ($file) {
                $validator->errors()->add('bulk-import-file', 'El archivo que intenta cargar ya fue cargado');
            }
        });

        return $validator;
    }

}

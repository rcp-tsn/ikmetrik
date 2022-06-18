<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee' => 'required',
            'file_type_id' => 'required',
            'file' => 'required|file|mimes:pdf|max:204800',
            'notification' => 'required',
        ];
    }

    public function messages()
    {
        $messages['employee.required'] = 'Personel Seçimi Yapılmadı.';
        $messages['file_type_id.required'] = 'Evrak Türü Seçilmelidir';
        $messages['file.required'] = 'Dosya Seçilmesi Zorunludur';
        $messages['file.mimes'] = 'Dosya Formatı Pdf Olmalıdır';
        $messages['file.max'] = 'Dosya Boyutu En Fazla 20 MB Olmalıdır';
        $messages['notification.unique'] = 'Açıklama Yazılması Zorunludur';


        return $messages;
    }
}

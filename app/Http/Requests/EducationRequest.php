<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationRequest extends FormRequest
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
            'name' => 'required',
            'notification' => 'required',
            'file' => 'required|file|mimes:mp4|max:20000',

        ];
    }

    public function messages()
    {
        $messages['name.required'] = 'Eğitim Adı Girilmedi.';
        $messages['notification.required'] = 'Eğitim Açıklaması Yapılmadı';
        $messages['file.required'] = 'Dosya Seçilmesi Zorunludur';
        $messages['file.mimes'] = 'Dosya Formatı MP4 Olmalıdır';
        $messages['file.max'] = 'Dosya Boyutu En Fazla 50 MB Olmalıdır';



        return $messages;
    }
}

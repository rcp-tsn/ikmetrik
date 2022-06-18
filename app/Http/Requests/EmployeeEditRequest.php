<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeEditRequest extends FormRequest
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
        $rules=[
            'first_name' => 'required',
            'last_name' => 'required',
            'work_title' => 'required',
            'job_start_date' => 'required',
            'birth_date' => 'required|date_format:"d/m/Y"',
            'identity_number' => 'required',

        ];

        return $rules;
    }

    /**
     * @return array|mixed
     */
    public function messages()
    {
        $messages['first_name.required'] = 'Ad alanı boş geçilmemelidir.';
        $messages['last_name.required'] = 'Soyad boş geçilmemelidir.';
        $messages['work_title.required'] = 'Ünvan alanı boş geçilmemelidir.';
        $messages['job_start_date.required'] = 'İşe Başlama Tarihi alanı boş geçilmemelidir.';



        $messages['birth_date.required'] = 'Kişisel bilgiler bölümündeki doğum tarihi alanı boş geçilmemelidir.';
        $messages['birth_date.date_format'] = 'Kişisel bilgiler bölümündeki doğum tarihi alanı hatalı girildi. GG/AA/YYYY şeklinde olmalıdır.';
        $messages['identity_number.required'] = 'Kimlik Numarası alanı boş geçilmemelidir';



        return $messages;
    }
}

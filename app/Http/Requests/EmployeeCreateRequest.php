<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeCreateRequest extends FormRequest
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
            'email'=>'required|email',
            'last_name' => 'required',
            'work_title' => 'required',
            'job_start_date' => 'required',
            'identity_number'=>'required'



        ];

        return $rules;
    }

    /**
     * @return array|mixed
     */
    public function messages()
    {
        $messages['first_name.required'] = 'Ad alanı boş geçilmemelidir.';
        $messages['identity_number.required'] = 'Tc Kimlik  alanı boş geçilmemelidir.';
        $messages['email.required'] = 'Email alanı boş geçilmemelidir.';
        $messages['email.email'] = 'Email Formatı Hatalı.';
        $messages['last_name.required'] = 'Soyad boş geçilmemelidir.';
        $messages['work_title.required'] = 'Ünvan alanı boş geçilmemelidir.';
        $messages['job_start_date.required'] = 'İşe Başlama Tarihi alanı boş geçilmemelidir.';
        $messages['job_start_date.date_format'] = 'İşe Başlama Tarihi alanı hatalı girildi. GG/AA/YYYY şeklinde olmalıdır.';



        return $messages;
    }
}

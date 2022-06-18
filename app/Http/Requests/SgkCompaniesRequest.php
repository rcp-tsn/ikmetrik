<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SgkCompaniesRequest extends FormRequest
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
            'company_id' => 'required',
            'sector_id' => 'required',
            'name' => 'required|unique:sgk_companies',
        ];
    }

    public function messages()
    {
        $messages['name.required'] = 'Firma adı alanı gereklidir.';
        $messages['sector_id.required'] = 'Sektör seçimi yapılmalıdır.';
        $messages['company_id.required'] = 'Ana Firma belirtilmelidir.';
        $messages['name.unique'] = 'Firma adı sistemde mevcutdur.';


        return $messages;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrmSupportsRequest extends FormRequest
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
        $ruleArray = [
            'name' => 'required|string|unique:egns|max:255',
        ];

        return $ruleArray;
    }
}

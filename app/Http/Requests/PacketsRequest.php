<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PacketsRequest extends FormRequest
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
            'title' => 'required|string|max:255|unique:packets,id,' . $this->id,
            'price' => 'required',
            'max_user_number' => 'required|integer',
        ];
    }
}
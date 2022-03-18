<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cuit' => ['required', 'string','min:4', 'max:20'],
            'nombrefantasia' => ['required', 'string', 'min:5','max:100'],
            'razonsocial' => ['required', 'string'],
            'email' => ['required', 'email'],
        ];
    
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gateway' => 'required',
            'method' => 'required'
        ];
    }

    public function messages()
    {

        return [
            'gateway.required' => 'O parâmetro gateway é obrigatório',
            'method.required' => 'O parâmetro method é obrigatório',
        ];
    }
}

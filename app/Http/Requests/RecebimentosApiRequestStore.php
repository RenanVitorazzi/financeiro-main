<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecebimentosApiRequestStore extends FormRequest
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
        $forma_pagamento = ['Pix', 'Depósito', 'Dinheiro', 'TED', 'DOC', 'Cheque'];

        return [
            'data' => ['date', 'required'],
            'valor' => ['required', 'numeric', 'min:0'],
            'conta_id' => ['nullable', 'numeric'],
            'parcela_id' => ['nullable', 'numeric'],
            'confirmado' => ['boolean'],
            'forma_pagamento' => ['required', Rule::in($forma_pagamento)],
            'observacao' => ['nullable'],
        ];
    }
}

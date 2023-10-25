<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NovaDespesaRequest extends FormRequest
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
        $formasPagamento = ['Boleto', 'Dinheiro', 'Cheque', 'Cartão de Crédito', 'Cartão de Débito', 'Depósito', 'TED', 'DOC', 'Pix'];

        return [
            'nome' => ['required', 'string', 'max:255'],
            'data_vencimento' => ['required', 'date', 'date_format:Y-m-d'],
            'valor' => ['required', 'numeric'],
            'local_id' => ['required', 'numeric'],
            'fixas_id' => ['nullable', 'numeric'],
            'data_referencia' => ['nullable', 'date', 'date_format:Y-m-d'],
            'observacao' => ['nullable', 'string', 'max:255'],
            'conta_id' => ['nullable', 'numeric'],
            'comprovante_id' => ['nullable'],
            'data_pagamento' => ['required', 'date', 'date_format:Y-m-d'],
            'forma_pagamento' => ['required', Rule::in($formasPagamento)],
        ];
    }
}

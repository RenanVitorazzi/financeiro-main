<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RequestContaStore extends FormRequest
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
        $tipoConta = ['Conta Corrente', 'Poupança'];

        return [
            'nome' => ['nullable'],
            'pix' => ['nullable'],
            'numero_banco'=> ['nullable', 'integer'],
            'agencia' => ['nullable', 'integer'],
            'conta' => ['nullable'],
            'conta_corrente' => ['nullable', Rule::in($tipoConta)],
            'inativo' => ['nullable'],
        ];
    }
}

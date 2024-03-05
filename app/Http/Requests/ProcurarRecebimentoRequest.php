<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcurarRecebimentoRequest extends FormRequest
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
            'valor' => ['nullable', 'integer'],
            'date' => ['nullable', 'date'],
            'representante_id' => ['nullable', 'exists:representantes,id'],
            'conta_id' => ['nullable', 'exists:contas,id'],
            'confirmado' => ['nullable'],
        ];
    }
}

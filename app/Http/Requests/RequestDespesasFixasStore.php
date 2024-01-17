<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestDespesasFixasStore extends FormRequest
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
            'nome' => ['required'],
            'data_quitacao' => ['nullable', 'date_format:Y-m-d'],
            'dia_vencimento' => ['required', 'integer', 'max:31'],
            'valor' => ['required', 'numeric'],
            'observacao' => ['nullable'],
            'local_id' => ['nullable', 'exists:locais,id'],
        ];
    }
}

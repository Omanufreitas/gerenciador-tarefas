<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTarefaRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'titulo'           => 'required|string|max:255',
            'descricao'        => 'nullable|string',
            'data_vencimento'  => 'nullable|date',
            'status'           => 'nullable|in:pendente,em_andamento,concluida'
        ];
    }
}

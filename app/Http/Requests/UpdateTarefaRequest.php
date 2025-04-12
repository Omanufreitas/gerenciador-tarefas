<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTarefaRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'titulo'           => 'sometimes|required|string|max:255',
            'descricao'        => 'sometimes|nullable|string',
            'data_vencimento'  => 'sometimes|nullable|date',
            'status'           => 'sometimes|in:pendente,em_andamento,concluida'
        ];
    }
}

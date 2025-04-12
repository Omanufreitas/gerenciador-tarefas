<?php

namespace App\Services;

use App\Models\Tarefa;

class TarefaService
{
    public function criarTarefa(array $data, int $userId): Tarefa
    {
        $data['user_id'] = $userId;
        return Tarefa::create($data);
    }

    public function atualizarTarefa(Tarefa $tarefa, array $data): Tarefa
    {
        $tarefa->update($data);
        return $tarefa;
    }

    public function deletarTarefa(Tarefa $tarefa): bool
    {
        return $tarefa->delete();
    }

    public function listarTarefasPorUsuario(int $userId)
    {
        return Tarefa::where('user_id', $userId)->get();
    }
}

<?php

namespace App\Policies;

use App\Models\Tarefa;
use App\Models\User;

class TarefaPolicy
{
    public function view(User $user, Tarefa $tarefa)
    {
        return $tarefa->user_id === $user->id;
    }

    public function update(User $user, Tarefa $tarefa)
    {
        return $tarefa->user_id === $user->id;
    }

    public function delete(User $user, Tarefa $tarefa)
    {
        return $tarefa->user_id === $user->id;
    }
}

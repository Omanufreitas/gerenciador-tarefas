<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Http\Requests\StoreTarefaRequest;
use App\Http\Requests\UpdateTarefaRequest;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tarefa::where('user_id', $request->user()->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data_vencimento')) {
            $query->whereDate('data_vencimento', $request->data_vencimento);
        }

        $tarefas = $query->paginate(5);

        return response()->json($tarefas);
    }

    public function store(StoreTarefaRequest $request)
    {
        $dados = $request->validated();
        $dados['user_id'] = $request->user()->id;

        $tarefa = Tarefa::create($dados);
        return response()->json([
            'data'    => $tarefa,
            'message' => 'Tarefa criada com sucesso!'
        ], 201);
    }

    public function show(Request $request, Tarefa $tarefa)
    {
        $this->authorize('view', $tarefa);
        return response()->json($tarefa);
    }

    public function update(UpdateTarefaRequest $request, Tarefa $tarefa)
    {
        $this->authorize('update', $tarefa);
        $tarefa->update($request->validated());
        return response()->json([
            'data'    => $tarefa,
            'message' => 'Tarefa atualizada com sucesso!'
        ]);
    }

    public function destroy(Request $request, Tarefa $tarefa)
    {
        $this->authorize('delete', $tarefa);
        $tarefa->delete();
        return response()->json([
            'data'    => $tarefa,
            'message' => 'Tarefa deletada com sucesso!'
        ]);
    }
}

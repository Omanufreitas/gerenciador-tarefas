<?php

namespace Database\Factories;

use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TarefaFactory extends Factory
{
    protected $model = Tarefa::class;

    public function definition(): array
    {
        $status = ['pendente', 'em_andamento', 'concluida'];
        return [
            'user_id'        => User::factory(), 
            'titulo'         => $this->faker->sentence(4),
            'descricao'      => $this->faker->paragraph,
            'data_vencimento'=> $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'status'         => $status[array_rand($status)],
        ];
    }
}

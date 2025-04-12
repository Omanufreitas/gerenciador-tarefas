<?php

namespace Database\Seeders;

use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Database\Seeder;

class TarefaSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(5)->create()->each(function ($user) {
            Tarefa::factory()->count(3)->create(['user_id' => $user->id]);
        });
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tarefa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TarefaFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_pode_visualizar_sua_tarefa()
    {
        $user = User::factory()->create();
        $tarefa = Tarefa::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/tarefas/{$tarefa->id}");
        $response->assertStatus(200)->assertJsonFragment(['id' => $tarefa->id]);
    }

    public function test_usuario_autenticado_pode_atualizar_sua_tarefa()
    {
        $user = User::factory()->create();
        $tarefa = Tarefa::factory()->create(['user_id' => $user->id]);

        $novosDados = [
            'titulo'  => 'Título atualizado',
            'status'  => 'em_andamento'
        ];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/tarefas/{$tarefa->id}", $novosDados);
        $response->assertStatus(200)->assertJsonFragment(['titulo' => 'Título atualizado']);
    }

    public function test_usuario_autenticado_nao_pode_atualizar_tarefa_de_outro_usuario()
    {
        $user = User::factory()->create();
        $outroUser = User::factory()->create();
        $tarefa = Tarefa::factory()->create(['user_id' => $outroUser->id]);

        $novosDados = ['titulo'  => 'Título malicioso'];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/tarefas/{$tarefa->id}", $novosDados);
        $response->assertStatus(403);
    }

    public function test_usuario_autenticado_pode_deletar_sua_tarefa()
    {
        $user = User::factory()->create();
        $tarefa = Tarefa::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/tarefas/{$tarefa->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tarefas', ['id' => $tarefa->id]);
    }

    // Testar funcionalidades de autenticação (login e logout)
    public function test_login_e_logout_funcionam_corretamente()
    {
        $user = User::factory()->create([
            'password' => bcrypt('senha123'),
        ]);

        // Login
        $loginResponse = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => 'senha123'
        ]);
        $loginResponse->assertStatus(200)
                      ->assertJsonStructure(['token', 'data']);

        // Logout
        $token = $loginResponse->json('token');
        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $logoutResponse->assertStatus(200);
    }

    // Testes de validação: envio de dados inválidos

    public function test_nao_cria_tarefa_com_status_invalido()
    {
        $user = User::factory()->create();

        $dados = [
            'titulo' => 'Tarefa com status inválido',
            'descricao' => 'Descrição qualquer',
            'data_vencimento' => '2025-12-31',
            'status' => 'status_invalido', // Status não permitido
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/tarefas', $dados);
        $response->assertStatus(422);
    }

    public function test_nao_cria_tarefa_com_data_vencimento_invalida()
    {
        $user = User::factory()->create();

        $dados = [
            'titulo' => 'Tarefa com data inválida',
            'descricao' => 'Descrição qualquer',
            'data_vencimento' => 'data_invalida', // Formato incorreto
            'status' => 'pendente',
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/tarefas', $dados);
        $response->assertStatus(422);
    }
}

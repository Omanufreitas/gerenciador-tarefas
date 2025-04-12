# Gerenciador de Tarefas

Uma aplicação fullstack para gerenciamento de tarefas desenvolvida com **Laravel** no backend e **Vue.js** no frontend.

O projeto permite que um usuário se registre, faça login e gerencie suas próprias tarefas (criar, visualizar, atualizar e excluir). Cada tarefa possui título, descrição, data de vencimento e status (pendente, em andamento, concluída). A autenticação da API é feita via Laravel Sanctum.

[Link para o repositório no GitHub](https://github.com/Omanufreitas/gerenciador-tarefas)

## Sumário

- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Execução](#execução)
- [Endpoints da API](#endpoints-da-api)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Testes](#testes)
- [CI/CD e Deploy](#cidcd-e-deploy)
- [Documentação Adicional](#documentação-adicional)
- [Considerações Finais](#considerações-finais)

## Requisitos

- **PHP 8.x** e **Composer**
- **Laravel 9.x** (ou superior)
- **Node.js** e **NPM/Yarn** (para o frontend)
- **Banco de Dados Relacional** (MySQL, PostgreSQL, etc.)

## Instalação

### Backend

1. **Clone o repositório:**

   git clone https://github.com/Omanufreitas/gerenciador-tarefas
   cd gerenciador-tarefas

2.**Instale as dependências do Laravel:**
composer install

3.**Configure o arquivo de ambiente:**

Copie o arquivo .env.example para .env:
cp .env.example .env
Abra o arquivo .env e ajuste as variáveis necessárias, como:
DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
Outras variáveis críticas (ex.: APP_URL, APP_ENV)

4.**Gere a chave da aplicação:**
php artisan key:generate

5.**Execute as migrations e seeders para criar e povoar o banco:**
php artisan migrate --seed

### Frontend
1.Navegue até a pasta do frontend:
cd frontend

2.**Instale as dependências do frontend:**
npm install

Instale o vue-router (versão 4):
npm install vue-router@4

Instale o axios:
npm install axios

Configuração
Autenticação:
O projeto utiliza Laravel Sanctum para autenticação. As rotas protegidas usam o middleware auth:sanctum.

Variáveis de Ambiente:
No arquivo .env do backend, configure adequadamente as informações do banco de dados e outras variáveis necessárias.

Frontend:
O frontend utiliza Vue.js e Tailwind CSS para a interface. Os comandos de build e start devem ser executados dentro da pasta frontend.

### Execução
Backend
Para iniciar o servidor de desenvolvimento do Laravel, na raiz do projeto:
php artisan serve
O backend ficará disponível, por exemplo, em http://127.0.0.1:8000.

Frontend
Com o terminal aberto na pasta frontend, inicie o servidor de desenvolvimento do Vue.js:
npm run dev
O frontend será servido conforme a configuração (ex.: http://localhost:3000).

Endpoints da API
Autenticação
POST /api/register
Registra um novo usuário.
Exemplo de payload:
{
  "name": "Nome do Usuário",
  "email": "email@example.com",
  "password": "senha123",
  "password_confirmation": "senha123"
}

POST /api/login
Realiza o login e retorna um token de autenticação.
Exemplo de payload:
{
  "email": "email@example.com",
  "password": "senha123"
}

POST /api/logout
Realiza o logout do usuário autenticado, revogando o token.
Requer o header:
Authorization: Bearer {token}

Gerenciamento de Tarefas
Todos os endpoints abaixo requerem autenticação via Sanctum:

GET /api/tarefas
Lista as tarefas do usuário autenticado.
Suporta filtros via query string:

status — (pendente, em_andamento, concluida)

data_vencimento — (formato YYYY-MM-DD)

Paginação pelo parâmetro page.

POST /api/tarefas
Cria uma nova tarefa.
Exemplo de payload:
{
  "titulo": "Nova Tarefa",
  "descricao": "Descrição da tarefa",
  "data_vencimento": "2025-12-31",
  "status": "pendente"
}

GET /api/tarefas/{id}
Exibe os detalhes de uma tarefa específica.
(Somente o proprietário da tarefa pode visualizar.)

PUT /api/tarefas/{id}
Atualiza os dados de uma tarefa.
Exemplo de payload (parcial ou completo):
{
  "titulo": "Tarefa atualizada",
  "status": "em_andamento"
}
DELETE /api/tarefas/{id}
Exclui uma tarefa.

Estrutura do Projeto
Controllers:

AuthController: Gerencia autenticação (registro, login, logout).

TarefaController: Gerencia operações (listagem, criação, atualização, deleção) de tarefas.

Services:

TarefaService: Centraliza a lógica de negócio para operações com tarefas.

Models:

User e Tarefa: Representam as entidades e definem relacionamentos (ex.: um usuário possui muitas tarefas).

Policies:

TarefaPolicy: Garante que um usuário somente possa acessar suas próprias tarefas.
(Registrada em AuthServiceProvider.)

FormRequests:

StoreTarefaRequest e UpdateTarefaRequest: Validam os dados de entrada para criação e atualização de tarefas.

Migrations, Seeders e Factories:

Migrations: Criam as tabelas do banco (users, tarefas, etc).

Seeders: Populam o banco com dados iniciais (um usuário de teste e tarefas).

Factories: Geram dados aleatórios para usuários e tarefas, facilitando testes e desenvolvimento.

Testes Automatizados:

Testes de feature para os fluxos principais: visualização, atualização, deleção de tarefas e autenticação.

Testes
Para rodar os testes automatizados do Laravel, na raiz do projeto, execute:
php artisan test

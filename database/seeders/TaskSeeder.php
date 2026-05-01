<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $tasks = [
            [
                'title' => 'Implementar autenticação JWT',
                'description' => 'Adicionar sistema de autenticação usando JWT tokens para a API',
                'status' => TaskStatus::DONE,
                'work_date' => Carbon::now()->subDays(10),
            ],
            [
                'title' => 'Criar testes unitários',
                'description' => 'Escrever testes para os controllers de usuário e autenticação',
                'status' => TaskStatus::DONE,
                'work_date' => Carbon::now()->subDays(8),
            ],
            [
                'title' => 'Refatorar módulo de relatórios',
                'description' => 'Melhorar performance das queries de relatório mensal',
                'status' => TaskStatus::DOING,
                'work_date' => Carbon::now()->subDays(3),
            ],
            [
                'title' => 'Corrigir bug no upload de arquivos',
                'description' => 'Arquivos maiores que 10MB estão falhando no upload',
                'status' => TaskStatus::DOING,
                'work_date' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Implementar cache de consultas',
                'description' => 'Adicionar Redis cache para consultas frequentes',
                'status' => TaskStatus::TODO,
                'work_date' => Carbon::now(),
            ],
            [
                'title' => 'Criar documentação da API',
                'description' => 'Documentar todos os endpoints usando Swagger/OpenAPI',
                'status' => TaskStatus::TODO,
                'work_date' => Carbon::now(),
            ],
            [
                'title' => 'Migrar banco para PostgreSQL',
                'description' => 'Migrar o banco de dados de MySQL para PostgreSQL',
                'status' => TaskStatus::TODO,
                'work_date' => Carbon::now()->addDays(5),
            ],
            [
                'title' => 'Feature de exportação PDF',
                'description' => 'Permitir exportar relatórios em formato PDF',
                'status' => TaskStatus::CANCELLED,
                'work_date' => Carbon::now()->subDays(15),
            ],
            [
                'title' => 'Integração com Slack',
                'description' => 'Enviar notificações automáticas para canal do Slack',
                'status' => TaskStatus::TODO,
                'work_date' => Carbon::now()->addDays(7),
            ],
            [
                'title' => 'Otimizar queries N+1',
                'description' => 'Identificar e corrigir problemas de N+1 queries',
                'status' => TaskStatus::DONE,
                'work_date' => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::create([
                'user_id' => $user->id,
                ...$taskData,
            ]);
        }
    }
}

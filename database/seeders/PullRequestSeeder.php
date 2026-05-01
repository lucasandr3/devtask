<?php

namespace Database\Seeders;

use App\Models\PullRequest;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PullRequestSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $tasks = Task::where('user_id', $user->id)->get();

        $pullRequests = [
            [
                'repo' => 'empresa/api-backend',
                'pr_number' => 142,
                'title' => 'feat: implementa autenticação JWT',
                'url' => 'https://github.com/empresa/api-backend/pull/142',
                'status' => 'merged',
                'work_date' => Carbon::now()->subDays(10),
            ],
            [
                'repo' => 'empresa/api-backend',
                'pr_number' => 145,
                'title' => 'test: adiciona testes unitários para auth',
                'url' => 'https://github.com/empresa/api-backend/pull/145',
                'status' => 'merged',
                'work_date' => Carbon::now()->subDays(8),
            ],
            [
                'repo' => 'empresa/frontend-app',
                'pr_number' => 89,
                'title' => 'fix: corrige loading spinner no login',
                'url' => 'https://github.com/empresa/frontend-app/pull/89',
                'status' => 'merged',
                'work_date' => Carbon::now()->subDays(7),
            ],
            [
                'repo' => 'empresa/api-backend',
                'pr_number' => 150,
                'title' => 'refactor: otimiza queries de relatório',
                'url' => 'https://github.com/empresa/api-backend/pull/150',
                'status' => 'open',
                'work_date' => Carbon::now()->subDays(3),
            ],
            [
                'repo' => 'empresa/api-backend',
                'pr_number' => 152,
                'title' => 'fix: corrige upload de arquivos grandes',
                'url' => 'https://github.com/empresa/api-backend/pull/152',
                'status' => 'open',
                'work_date' => Carbon::now()->subDays(2),
            ],
            [
                'repo' => 'empresa/infrastructure',
                'pr_number' => 34,
                'title' => 'chore: atualiza configuração do Docker',
                'url' => 'https://github.com/empresa/infrastructure/pull/34',
                'status' => 'merged',
                'work_date' => Carbon::now()->subDays(5),
            ],
            [
                'repo' => 'empresa/frontend-app',
                'pr_number' => 95,
                'title' => 'feat: adiciona dark mode',
                'url' => 'https://github.com/empresa/frontend-app/pull/95',
                'status' => 'draft',
                'work_date' => Carbon::now()->subDays(1),
            ],
            [
                'repo' => 'empresa/api-backend',
                'pr_number' => 148,
                'title' => 'perf: resolve N+1 queries',
                'url' => 'https://github.com/empresa/api-backend/pull/148',
                'status' => 'merged',
                'work_date' => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($pullRequests as $index => $prData) {
            // Associa alguns PRs a tasks existentes
            $taskId = null;
            if ($tasks->isNotEmpty() && $index < $tasks->count()) {
                $taskId = $tasks[$index]->id;
            }

            PullRequest::create([
                'user_id' => $user->id,
                'task_id' => $taskId,
                ...$prData,
            ]);
        }
    }
}

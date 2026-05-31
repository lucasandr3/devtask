<?php

namespace Database\Seeders;

use App\Enums\CompanyRole;
use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    private const PORTFOLIO = 'Portfólio 0 — IA para Empresas';

    private const PORTFOLIO_OBJECTIVE = 'Gerar receita recorrente através da venda de agentes de IA para atendimento, vendas, suporte e automação comercial.';

    public function run(): void
    {
        $company = Company::create([
            'name' => 'GestorPro Projetos',
            'slug' => 'gestorpro-projetos',
            'cnpj' => '31.226.405/0001-76',
        ]);

        $lucas = User::updateOrCreate(
            ['email' => 'lucas@gmail.com'],
            [
                'name' => 'Lucas Vieira de Andrade',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'company_name' => 'GestorPro Projetos',
                'cnpj' => '31.226.405/0001-76',
                'current_company_id' => $company->id,
            ]
        );

        $lorraine = User::updateOrCreate(
            ['email' => 'lorrainemacedo@gmail.com'],
            [
                'name' => 'Lorraine Macedo',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'company_name' => 'GestorPro Projetos',
                'current_company_id' => $company->id,
            ]
        );

        $company->users()->syncWithoutDetaching([
            $lucas->id => ['role' => CompanyRole::ADMIN->value],
            $lorraine->id => ['role' => CompanyRole::ADMIN->value],
        ]);

        $this->seedPortfolioProjects($company, $lucas, $lorraine);
    }

    private function seedPortfolioProjects(Company $company, User $lucas, User $lorraine): void
    {
        $users = [$lucas, $lorraine];

        foreach ($this->portfolioProjects() as $index => $definition) {
            $creator = $users[$index % 2];

            $project = Project::create([
                'company_id' => $company->id,
                'created_by' => $creator->id,
                'name' => $definition['name'],
                'description' => $this->buildProjectDescription($definition),
                'status' => ProjectStatus::ACTIVE,
                'starts_at' => now()->startOfMonth(),
            ]);

            foreach ($definition['tasks'] as $taskIndex => $taskTitle) {
                $assignee = $users[($index + $taskIndex) % 2];

                Task::create([
                    'project_id' => $project->id,
                    'user_id' => $assignee->id,
                    'created_by' => $creator->id,
                    'assigned_to' => $assignee->id,
                    'title' => $taskTitle,
                    'description' => null,
                    'internal_notes' => self::PORTFOLIO,
                    'status' => TaskStatus::TODO,
                    'work_date' => now()->toDateString(),
                ]);
            }
        }
    }

    private function buildProjectDescription(array $definition): string
    {
        $phases = implode(' → ', $definition['phases']);

        return implode("\n", [
            self::PORTFOLIO,
            self::PORTFOLIO_OBJECTIVE,
            'Prioridade: '.$definition['priority'],
            'Fases: '.$phases,
        ]);
    }

    /**
     * @return list<array{name: string, priority: string, phases: list<string>, tasks: list<string>}>
     */
    private function portfolioProjects(): array
    {
        return [
            [
                'name' => 'Plataforma de Agentes IA',
                'priority' => 'Crítica',
                'phases' => ['Planejamento', 'Desenvolvimento', 'Infraestrutura', 'Comercialização'],
                'tasks' => [
                    'Definir arquitetura multiempresa',
                    'Criar gestão de clientes',
                    'Criar gestão de agentes',
                    'Criar gestão de conversas',
                    'Criar dashboard operacional',
                    'Criar dashboard para clientes',
                    'Criar sistema de billing',
                    'Criar planos de assinatura',
                    'Criar sistema de onboarding',
                    'Criar gestão de permissões',
                    'Criar gestão de integrações',
                    'Criar documentação técnica',
                ],
            ],
            [
                'name' => 'Agente SDR',
                'priority' => 'Crítica',
                'phases' => ['Produto', 'Treinamento', 'Integrações', 'Comercial'],
                'tasks' => [
                    'Definir ICP',
                    'Criar fluxo de qualificação',
                    'Criar fluxo de descoberta',
                    'Criar perguntas de diagnóstico',
                    'Criar classificação de leads',
                    'Criar lead scoring',
                    'Criar integração WhatsApp',
                    'Criar integração CRM',
                    'Criar dashboard de métricas',
                    'Criar relatórios',
                    'Criar playbook comercial',
                    'Criar apresentação comercial',
                ],
            ],
            [
                'name' => 'Agente de Atendimento',
                'priority' => 'Crítica',
                'phases' => ['Produto', 'Base de Conhecimento', 'Integrações', 'Comercial'],
                'tasks' => [
                    'Criar treinamento inicial',
                    'Criar FAQs',
                    'Criar base de conhecimento',
                    'Criar fluxo de atendimento',
                    'Criar transferência para humano',
                    'Criar avaliação de atendimento',
                    'Criar métricas de satisfação',
                    'Criar relatórios operacionais',
                ],
            ],
            [
                'name' => 'Agente de Vendas',
                'priority' => 'Crítica',
                'phases' => ['Produto', 'Automação', 'Comercial'],
                'tasks' => [
                    'Criar catálogo de produtos',
                    'Criar motor de recomendações',
                    'Criar fluxo de negociação',
                    'Criar recuperação de carrinho',
                    'Criar follow-up automático',
                    'Criar envio de propostas',
                    'Criar geração de contratos',
                    'Criar dashboard de conversão',
                ],
            ],
            [
                'name' => 'Agente de Pós-Venda',
                'priority' => 'Alta',
                'phases' => ['Produto', 'Automação', 'Comercial'],
                'tasks' => [
                    'Criar fluxo de onboarding',
                    'Criar acompanhamento de clientes',
                    'Criar pesquisas NPS',
                    'Criar automação de renovação',
                    'Criar automação de upsell',
                    'Criar automação de cross-sell',
                    'Criar relatórios de retenção',
                ],
            ],
            [
                'name' => 'Agente de Cobrança',
                'priority' => 'Alta',
                'phases' => ['Produto', 'Integrações', 'Comercial'],
                'tasks' => [
                    'Criar lembretes automáticos',
                    'Criar integração bancária',
                    'Criar integração PIX',
                    'Criar régua de cobrança',
                    'Criar relatórios financeiros',
                    'Criar acompanhamento de inadimplência',
                ],
            ],
            [
                'name' => 'Agente para Imobiliárias',
                'priority' => 'Alta',
                'phases' => ['Produto', 'Comercial'],
                'tasks' => [
                    'Atendimento de leads',
                    'Qualificação automática',
                    'Agendamento de visitas',
                    'Follow-up de interessados',
                    'Dashboard para imobiliária',
                ],
            ],
            [
                'name' => 'Agente para Clínicas',
                'priority' => 'Alta',
                'phases' => ['Produto', 'Comercial'],
                'tasks' => [
                    'Atendimento de pacientes',
                    'Agendamento automático',
                    'Confirmação de consultas',
                    'Lembretes automáticos',
                    'Pesquisa de satisfação',
                ],
            ],
            [
                'name' => 'Máquina Comercial',
                'priority' => 'Crítica',
                'phases' => ['Marketing', 'Vendas', 'Escala'],
                'tasks' => [
                    'Criar landing page principal',
                    'Criar página do Agente SDR',
                    'Criar página do Agente de Atendimento',
                    'Criar página do Agente de Vendas',
                    'Criar funil de aquisição',
                    'Criar automação de marketing',
                    'Criar CRM comercial',
                    'Criar pipeline de vendas',
                    'Criar playbook comercial',
                    'Criar programa de indicação',
                    'Criar campanhas de tráfego pago',
                ],
            ],
        ];
    }
}

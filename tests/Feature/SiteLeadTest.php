<?php

namespace Tests\Feature;

use App\Enums\CompanyRole;
use App\Enums\SiteLeadStatus;
use App\Models\Company;
use App\Models\SiteLead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteLeadTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;

    private User $admin;

    private User $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::create([
            'name' => 'Empresa Teste',
            'slug' => 'empresa-teste',
        ]);

        $this->admin = User::factory()->create([
            'current_company_id' => $this->company->id,
        ]);

        $this->member = User::factory()->create([
            'current_company_id' => $this->company->id,
        ]);

        $this->company->users()->attach($this->admin->id, ['role' => CompanyRole::ADMIN->value]);
        $this->company->users()->attach($this->member->id, ['role' => CompanyRole::MEMBER->value]);

        config([
            'site-lead.api_token' => 'test-token-secret',
            'site-lead.company_id' => $this->company->id,
        ]);
    }

    public function test_api_stores_site_lead_with_valid_token(): void
    {
        $response = $this->postJson('/api/site-leads', [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'company' => 'Acme Ltda',
            'phone' => '11999999999',
            'segment' => 'Tecnologia',
            'message' => 'Gostaria de saber mais sobre os serviços.',
        ], [
            'Authorization' => 'Bearer test-token-secret',
        ]);

        $response->assertCreated()
            ->assertJsonPath('id', 1);

        $this->assertDatabaseHas('site_leads', [
            'company_id' => $this->company->id,
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'company_name' => 'Acme Ltda',
            'status' => SiteLeadStatus::NEW->value,
        ]);
    }

    public function test_api_rejects_invalid_token(): void
    {
        $response = $this->postJson('/api/site-leads', [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'message' => 'Teste',
        ], [
            'Authorization' => 'Bearer wrong-token',
        ]);

        $response->assertUnauthorized();
        $this->assertDatabaseCount('site_leads', 0);
    }

    public function test_api_rejects_honeypot(): void
    {
        $response = $this->postJson('/api/site-leads', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Spam',
            'website' => 'http://spam.test',
        ], [
            'Authorization' => 'Bearer test-token-secret',
        ]);

        $response->assertUnprocessable();
    }

    public function test_admin_can_view_site_leads_index(): void
    {
        SiteLead::create([
            'company_id' => $this->company->id,
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'message' => 'Olá',
            'status' => SiteLeadStatus::NEW,
        ]);

        $response = $this->actingAs($this->admin)->get(route('contatos-site.index'));

        $response->assertOk();
        $response->assertSee('Maria');
        $response->assertSee('maria@example.com');
    }

    public function test_member_cannot_view_site_leads(): void
    {
        $response = $this->actingAs($this->member)->get(route('contatos-site.index'));

        $response->assertForbidden();
    }

    public function test_show_marks_lead_as_read(): void
    {
        $lead = SiteLead::create([
            'company_id' => $this->company->id,
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'message' => 'Olá',
            'status' => SiteLeadStatus::NEW,
        ]);

        $this->actingAs($this->admin)->get(route('contatos-site.show', $lead));

        $lead->refresh();
        $this->assertSame(SiteLeadStatus::READ, $lead->status);
        $this->assertNotNull($lead->read_at);
    }
}

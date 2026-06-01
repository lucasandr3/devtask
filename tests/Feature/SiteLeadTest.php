<?php

namespace Tests\Feature;

use App\Enums\CompanyRole;
use App\Enums\SiteLeadStatus;
use App\Models\Client;
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
            'site-legal.accepted_privacy_versions' => ['2026-06-01', '2026-06-02'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validLeadPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'João Silva',
            'company' => 'Acme Ltda',
            'email' => 'joao@example.com',
            'phone' => '11999999999',
            'segment' => 'healthcare',
            'message' => 'Gostaria de saber mais sobre os serviços.',
            'source' => 'zion_tech_site',
            'privacyConsent' => true,
            'privacyPolicyVersion' => '2026-06-01',
            'privacyConsentedAt' => '2026-06-01T14:30:00.000Z',
        ], $overrides);
    }

    public function test_api_accepts_production_like_payload(): void
    {
        $response = $this->postJson('/api/site-leads', [
            'name' => 'Lucas Vieira',
            'company' => 'Zion Flow',
            'email' => 'lucasvieiraandrade58@gmail.com',
            'phone' => '(38) 9217-8166',
            'segment' => 'other',
            'message' => 'etste',
            'source' => 'zion_tech_site',
            'privacyConsent' => true,
            'privacyPolicyVersion' => '2026-06-02',
            'privacyConsentedAt' => '2026-06-01T17:45:10.892Z',
        ], [
            'Authorization' => 'Bearer test-token-secret',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('site_leads', [
            'email' => 'lucasvieiraandrade58@gmail.com',
            'privacy_policy_version' => '2026-06-02',
        ]);
    }

    public function test_api_stores_site_lead_with_valid_token(): void
    {
        $response = $this->postJson('/api/site-leads', $this->validLeadPayload(), [
            'Authorization' => 'Bearer test-token-secret',
            'User-Agent' => 'ZionTechSite/1.0',
        ]);

        $response->assertCreated()
            ->assertJsonPath('id', 1);

        $this->assertDatabaseHas('site_leads', [
            'company_id' => $this->company->id,
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'company_name' => 'Acme Ltda',
            'source' => 'zion_tech_site',
            'privacy_consent' => true,
            'privacy_policy_version' => '2026-06-01',
            'status' => SiteLeadStatus::NEW->value,
        ]);

        $lead = SiteLead::first();
        $this->assertNotNull($lead->privacy_consented_at);
        $this->assertNotNull($lead->ip_address);
        $this->assertSame('ZionTechSite/1.0', $lead->user_agent);
    }

    public function test_api_rejects_without_privacy_consent(): void
    {
        $response = $this->postJson('/api/site-leads', $this->validLeadPayload([
            'privacyConsent' => false,
        ]), [
            'Authorization' => 'Bearer test-token-secret',
        ]);

        $response->assertUnprocessable()
            ->assertJson(['message' => 'Não foi possível processar o envio.']);
        $this->assertDatabaseCount('site_leads', 0);
    }

    public function test_api_rejects_outdated_privacy_policy_version(): void
    {
        $response = $this->postJson('/api/site-leads', $this->validLeadPayload([
            'privacyPolicyVersion' => '2025-01-01',
        ]), [
            'Authorization' => 'Bearer test-token-secret',
        ]);

        $response->assertUnprocessable()
            ->assertJson(['message' => 'Não foi possível processar o envio.']);
        $this->assertDatabaseCount('site_leads', 0);
    }

    public function test_api_rejects_missing_privacy_fields(): void
    {
        $payload = $this->validLeadPayload();
        unset($payload['privacyPolicyVersion'], $payload['privacyConsentedAt']);

        $response = $this->postJson('/api/site-leads', $payload, [
            'Authorization' => 'Bearer test-token-secret',
        ]);

        $response->assertUnprocessable()
            ->assertJson(['message' => 'Não foi possível processar o envio.']);
        $this->assertDatabaseCount('site_leads', 0);
    }

    public function test_api_rejects_invalid_token(): void
    {
        $response = $this->postJson('/api/site-leads', $this->validLeadPayload(), [
            'Authorization' => 'Bearer wrong-token',
        ]);

        $response->assertUnauthorized();
        $this->assertDatabaseCount('site_leads', 0);
    }

    public function test_api_rejects_honeypot(): void
    {
        $response = $this->postJson('/api/site-leads', $this->validLeadPayload([
            'website' => 'http://spam.test',
        ]), [
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

    public function test_admin_can_convert_lead_to_client(): void
    {
        $lead = SiteLead::create([
            'company_id' => $this->company->id,
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'company_name' => 'Maria Corp',
            'phone' => '11988887777',
            'segment' => 'Saúde',
            'message' => 'Quero um orçamento',
            'status' => SiteLeadStatus::READ,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('contatos-site.convert-client', $lead));

        $response->assertRedirect();

        $lead->refresh();
        $this->assertNotNull($lead->client_id);
        $this->assertSame(SiteLeadStatus::ARCHIVED, $lead->status);

        $this->assertDatabaseHas('clients', [
            'id' => $lead->client_id,
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'phone' => '11988887777',
        ]);
    }
}

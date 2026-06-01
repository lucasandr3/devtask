# LGPD — backend (`POST /api/site-leads`)

Implementação no GestorPro (devtask) alinhada ao site Angular Zion Tech.

## Endpoint

```
POST https://tarefas.zionai.com.br/api/site-leads
Authorization: Bearer <SITE_LEAD_API_TOKEN>
Content-Type: application/json
Accept: application/json
```

## Corpo JSON (obrigatório)

| Campo | Tipo | Descrição |
|--------|------|-----------|
| `name` | string | Nome |
| `company` | string | Empresa |
| `email` | string | E-mail |
| `phone` | string | Telefone |
| `segment` | string | Segmento |
| `message` | string | Mensagem |
| `source` | string | Ex.: `zion_tech_site` |
| `privacyConsent` | boolean | Deve ser `true` |
| `privacyPolicyVersion` | string | Ex.: `2026-06-01` |
| `privacyConsentedAt` | string ISO 8601 | Data/hora do consentimento no cliente |

Campos **não** aceitos do cliente (preenchidos no servidor): `ip_address`, `user_agent`.

Honeypot: campo `website` — se preenchido, rejeita com 422.

## Respostas

- **201** — `{ "message": "Contato recebido com sucesso.", "id": 1 }`
- **401** — token inválido
- **422** — validação (mensagem genérica: `Não foi possível processar o envio.`)
- **503** — API não configurada (`SITE_LEAD_API_TOKEN` / `SITE_LEAD_COMPANY_ID`)

## Variáveis `.env`

```env
SITE_LEAD_API_TOKEN=
SITE_LEAD_COMPANY_ID=1
SITE_LEAD_CORS_ORIGINS=https://ziontech.com.br,https://www.ziontech.com.br
SITE_PRIVACY_POLICY_VERSION=2026-06-01
SITE_PRIVACY_EMAIL=privacidade@ziontech.com.br
```

## Sincronia com o site Angular

Constantes espelhadas em `config/site-legal.php` (origem: `src/app/shared/constants/site-legal.ts`):

| Angular | Backend |
|---------|---------|
| `PRIVACY_POLICY_VERSION` | `SITE_PRIVACY_POLICY_VERSION` / `config('site-legal.privacy_policy_version')` |
| `SITE_LEGAL.*` | `config('site-legal.legal.*')` |

Ao publicar nova política no site, incremente a versão nos **dois** projetos. A API só aceita versões listadas em `SITE_ACCEPTED_PRIVACY_VERSIONS` (padrão: só a versão atual).

## Banco (`site_leads`)

- `privacy_consent`, `privacy_policy_version`, `privacy_consented_at`
- `source`
- `ip_address`, `user_agent`

## Segurança

- Rate limit: 10 req/min por IP (`site-leads`)
- CORS restrito a `SITE_LEAD_CORS_ORIGINS` em produção
- Preferir proxy no servidor do site (token fora do bundle Angular)

## Painel admin

`/contatos-site` — exibe dados de consentimento e auditoria (IP, user-agent).

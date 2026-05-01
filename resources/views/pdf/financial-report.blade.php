<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Relatório Financeiro - {{ $date->format('m/Y') }}</title>
<style>
  /* ═══ RESET & BASE ═══ */
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 11px;
    color: #1e293b;
    background: #ffffff;
    margin: 0;
    padding: 28px 32px;
  }

  /* ═══ TITLE ═══ */
  .title-block {
    text-align: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
  }
  .title-block h1 {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 4px;
  }
  .title-block p {
    font-size: 9px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1.5px;
  }

  /* ═══ HEADER INFO TABLE ═══ */
  .header-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-top: 3px solid #0d2833;
  }
  .header-table td {
    padding: 12px 14px;
    border-right: 1px solid #e2e8f0;
    vertical-align: top;
  }
  .header-table td:last-child {
    border-right: none;
  }
  .field-label {
    font-size: 7px;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: #64748b;
    margin-bottom: 4px;
    display: block;
  }
  .field-value {
    font-size: 11px;
    font-weight: 600;
    color: #1e293b;
  }

  /* ═══ SUMMARY CARDS ═══ */
  .summary-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-top: 3px solid #0d2833;
  }
  .summary-table td {
    width: 25%;
    padding: 14px;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: top;
    text-align: center;
  }
  .summary-table td:last-child {
    border-right: none;
  }
  .summary-table tr:last-child td {
    border-bottom: none;
  }
  .card-label {
    font-size: 7px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #64748b;
    margin-bottom: 6px;
    display: block;
  }
  .card-value {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
  }
  .c-green { color: #16a34a; }
  .c-blue { color: #2563eb; }
  .c-amber { color: #d97706; }
  .c-purple { color: #7c3aed; }
  .c-red { color: #dc2626; }

  /* ═══ SECTION TITLE ═══ */
  .section-title {
    font-size: 12px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
    padding-bottom: 6px;
    border-bottom: 2px solid #e2e8f0;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  /* ═══ MAIN TABLE ═══ */
  .main-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
  }

  .main-table thead th {
    background: #1e293b;
    color: #ffffff;
    font-size: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 8px 10px;
    text-align: left;
  }
  .main-table thead th.text-right {
    text-align: right;
  }

  .main-table tbody tr {
    border-bottom: 1px solid #e2e8f0;
  }
  .main-table tbody tr.even {
    background: #f8fafc;
  }
  .main-table tbody tr.odd {
    background: #ffffff;
  }

  .main-table tbody td {
    padding: 8px 10px;
    font-size: 9px;
    color: #475569;
    border: 1px solid #e2e8f0;
  }
  .main-table tbody td.text-right {
    text-align: right;
  }
  .main-table tbody td.font-bold {
    font-weight: 700;
    color: #1e293b;
  }

  /* ═══ TOTAL ROW ═══ */
  .total-row {
    background: #f1f5f9 !important;
  }
  .total-row td {
    font-weight: 700;
    color: #1e293b !important;
  }

  /* ═══ STATUS BADGES ═══ */
  .status-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 7px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .status-paid {
    background: #dcfce7;
    color: #166534;
  }
  .status-pending {
    background: #fef3c7;
    color: #92400e;
  }
  .status-overdue {
    background: #fee2e2;
    color: #991b1b;
  }

  /* ═══ EMPTY MESSAGE ═══ */
  .empty-message {
    text-align: center;
    padding: 20px;
    color: #94a3b8;
    font-style: italic;
  }

  /* ═══ FOOTER TABLE ═══ */
  .footer-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 25px;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
  }
  .footer-table td {
    font-size: 7px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding-top: 8px;
  }
  .footer-table td:last-child {
    text-align: right;
  }
</style>
</head>
<body>

<!-- TITLE -->
<div class="title-block">
  <h1>Relatório Financeiro</h1>
  <p>Demonstrativo Mensal de Receitas e Despesas</p>
</div>

<!-- HEADER INFO -->
<table class="header-table">
  <tr>
    <td style="width: 40%;">
      <span class="field-label">Prestador de Serviço</span>
      <span class="field-value">{{ $user->name }}</span>
    </td>
    <td style="width: 30%;">
      <span class="field-label">Período</span>
      <span class="field-value">{{ $date->translatedFormat('F/Y') }}</span>
    </td>
    <td style="width: 30%;">
      <span class="field-label">Data de Emissão</span>
      <span class="field-value">{{ now()->format('d/m/Y H:i') }}</span>
    </td>
  </tr>
  @if($user->company_name || $user->cnpj)
  <tr>
    <td colspan="2">
      <span class="field-label">Razão Social / Nome Fantasia</span>
      <span class="field-value">{{ $user->company_name ?? '—' }}</span>
    </td>
    <td>
      <span class="field-label">CNPJ</span>
      <span class="field-value">{{ $user->cnpj ?? '—' }}</span>
    </td>
  </tr>
  @endif
</table>

<!-- SUMMARY CARDS -->
<table class="summary-table">
  <tr>
    <td>
      <span class="card-label">Receita Total</span>
      <span class="card-value c-green">{{ $financialData['formatted_total_revenue'] }}</span>
    </td>
    <td>
      <span class="card-label">DAS Pago</span>
      <span class="card-value c-blue">{{ $financialData['formatted_total_das_paid'] }}</span>
    </td>
    <td>
      <span class="card-label">DAS Pendente</span>
      <span class="card-value c-amber">{{ $financialData['formatted_total_das_pending'] }}</span>
    </td>
    <td>
      <span class="card-label">Saldo</span>
      <span class="card-value {{ $financialData['balance'] >= 0 ? 'c-purple' : 'c-red' }}">{{ $financialData['formatted_balance'] }}</span>
    </td>
  </tr>
</table>

<!-- NOTAS FISCAIS -->
<h3 class="section-title">Notas Fiscais do Período</h3>
<table class="main-table">
  <thead>
    <tr>
      <th style="width: 15%;">Número</th>
      <th style="width: 15%;">Data Emissão</th>
      <th style="width: 50%;">Descrição</th>
      <th style="width: 20%;" class="text-right">Valor</th>
    </tr>
  </thead>
  <tbody>
    @php $rowIndex = 0; @endphp
    @forelse($financialData['invoices'] as $invoice)
      <tr class="{{ $rowIndex % 2 == 0 ? 'even' : 'odd' }}">
        <td class="font-bold">{{ $invoice->numero }}</td>
        <td>{{ $invoice->data_emissao->format('d/m/Y') }}</td>
        <td>{{ Str::limit($invoice->descricao, 60) }}</td>
        <td class="text-right font-bold" style="color: #16a34a;">R$ {{ number_format($invoice->valor, 2, ',', '.') }}</td>
      </tr>
      @php $rowIndex++; @endphp
    @empty
      <tr>
        <td colspan="4" class="empty-message">Nenhuma nota fiscal encontrada para este período</td>
      </tr>
    @endforelse
    @if($financialData['invoices']->count() > 0)
      <tr class="total-row">
        <td colspan="3" style="text-align: right; font-size: 10px;">Total de Receitas:</td>
        <td class="text-right" style="font-size: 11px; color: #16a34a !important;">{{ $financialData['formatted_total_revenue'] }}</td>
      </tr>
    @endif
  </tbody>
</table>

<!-- PAGAMENTOS DAS -->
<h3 class="section-title">Pagamentos DAS</h3>
<table class="main-table">
  <thead>
    <tr>
      <th style="width: 20%;">Referência</th>
      <th style="width: 20%;">Vencimento</th>
      <th style="width: 25%;">Data Pagamento</th>
      <th style="width: 15%;">Status</th>
      <th style="width: 20%;" class="text-right">Valor</th>
    </tr>
  </thead>
  <tbody>
    @php $rowIndex = 0; @endphp
    @forelse($financialData['das_payments'] as $das)
      <tr class="{{ $rowIndex % 2 == 0 ? 'even' : 'odd' }}">
        <td class="font-bold">{{ $das->reference_month->format('m/Y') }}</td>
        <td>{{ $das->due_date->format('d/m/Y') }}</td>
        <td>{{ $das->payment_date ? $das->payment_date->format('d/m/Y') : '—' }}</td>
        <td>
          @switch($das->status->value)
            @case('paid')
              <span class="status-badge status-paid">Pago</span>
              @break
            @case('pending')
              <span class="status-badge status-pending">Pendente</span>
              @break
            @case('overdue')
              <span class="status-badge status-overdue">Vencido</span>
              @break
            @default
              <span class="status-badge">{{ $das->status->label() }}</span>
          @endswitch
        </td>
        <td class="text-right font-bold">R$ {{ number_format($das->amount, 2, ',', '.') }}</td>
      </tr>
      @php $rowIndex++; @endphp
    @empty
      <tr>
        <td colspan="5" class="empty-message">Nenhum DAS encontrado para este período</td>
      </tr>
    @endforelse
    @if($financialData['das_payments']->count() > 0)
      <tr class="total-row">
        <td colspan="4" style="text-align: right; font-size: 10px;">Total DAS (Pago + Pendente):</td>
        <td class="text-right" style="font-size: 11px;">R$ {{ number_format($financialData['total_das_paid'] + $financialData['total_das_pending'] + $financialData['total_das_overdue'], 2, ',', '.') }}</td>
      </tr>
    @endif
  </tbody>
</table>

<!-- FOOTER -->
<table class="footer-table">
  <tr>
    <td>Relatório Financeiro — Documento confidencial</td>
    <td>{{ $date->translatedFormat('F/Y') }}</td>
  </tr>
</table>

</body>
</html>

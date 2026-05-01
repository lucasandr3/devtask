<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Espelho de Horas - {{ $report->reference_month->format('m/Y') }}</title>
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
    /* border-bottom: 3px solid #3b82f6; */
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
    border-top: 1px solid #0d2833;
  }
  .header-table td {
    width: 33.33%;
    padding: 10px 14px;
    border-right: 1px solid #e2e8f0;
    vertical-align: top;
  }
  .header-table td:last-child {
    border-right: none;
  }
  .header-table tr + tr td {
    border-top: 1px solid #e2e8f0;
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

  /* ═══ MAIN TABLE ═══ */
  .main-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
  }

  /* Header */
  .main-table thead th {
    background: #1e293b;
    color: #ffffff;
    font-size: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 8px 5px;
    text-align: center;
    
  }
  .main-table thead th:first-child {
    text-align: left;
    padding-left: 10px;
  }

  /* Body rows */
  .main-table tbody tr {
    border-bottom: 1px solid #e2e8f0;
  }
  .main-table tbody tr.even {
    background: #f8fafc;
  }
  .main-table tbody tr.odd {
    background: #ffffff;
  }
  .main-table tbody tr.weekend {
    background: #fef3c7;
  }

  .main-table tbody td {
    padding: 5px 5px;
    text-align: center;
    font-size: 9px;
    color: #475569;
    border: 1px solid #e2e8f0;
  }

  /* Date column */
  .col-date {
    text-align: left !important;
    padding-left: 10px !important;
    font-weight: 600;
    color: #1e293b !important;
  }

  /* Day tag */
  .day-tag {
    font-size: 7px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #64748b;
    background: #f1f5f9;
    padding: 2px 5px;
    border-radius: 3px;
  }

  /* Extra hours highlight */
  .extra-val {
    color: #d97706;
    font-weight: 600;
    font-size: 8px;
  }

  /* Total column */
  .total-val {
    color: #1e293b;
    font-weight: 700;
    font-size: 9px;
  }

  /* Dash (no data) */
  .dash {
    color: #94a3b8;
  }

  /* ═══ TOTAL ROW ═══ */
  .total-row {
    background: #1e293b !important;
  }
  .total-row td {
    padding: 8px 5px;
  }
  .total-label {
    text-align: right !important;
    padding-right: 10px !important;
    font-size: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #ffffff !important;
  }
  .total-grand {
    font-size: 11px;
    font-weight: 700;
    color: #ffffff !important;
    text-align: center;
  }

  /* ═══ SUMMARY TABLE ═══ */
  .summary-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-top: 1px solid #0d2833;
  }
  .summary-table td {
    width: 33.33%;
    padding: 12px 14px;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: top;
  }
  .summary-table td:nth-child(3n) {
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
    margin-bottom: 4px;
    display: block;
  }
  .card-value {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
  }
  .c-accent { color: #2563eb; }
  .c-amber { color: #d97706; }
  .c-green { color: #16a34a; }
  .c-red { color: #dc2626; }
  .c-muted { color: #64748b; }

  /* ═══ DECLARATION ═══ */
  .declaration {
    font-size: 8px;
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 15px;
    font-style: italic;
  }

  /* ═══ SIGNATURES TABLE ═══ */
  .signatures-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }
  .signatures-table td {
    width: 50%;
    padding: 0 30px;
    text-align: center;
    vertical-align: top;
  }
  .sig-line {
    border-bottom: 1px solid #94a3b8;
    height: 35px;
    margin-bottom: 6px;
  }
  .sig-name {
    font-size: 9px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2px;
  }
  .sig-role {
    font-size: 7px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.8px;
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
  <h1>Espelho de Horas</h1>
  <p>Relatório de Horas Trabalhadas — Prestador de Serviço</p>
</div>

<!-- HEADER INFO -->
<table class="summary-table">
  <tr>
    <td>
      <span class="field-label">Prestador de Serviço</span>
      <span class="field-value">{{ $report->user->name }}</span>
    </td>
    <td>
      <span class="field-label">Período</span>
      <span class="field-value">{{ $report->reference_month->translatedFormat('F/Y') }}</span>
    </td>
    <td>
      <span class="field-label">Data de Emissão</span>
      <span class="field-value">{{ now()->format('d/m/Y H:i') }}</span>
    </td>
  </tr>
  @if($report->user->company_name || $report->user->cnpj)
  <tr>
    <td colspan="2">
      <span class="field-label">Razão Social / Nome Fantasia</span>
      <span class="field-value">{{ $report->user->company_name ?? '—' }}</span>
    </td>
    <td>
      <span class="field-label">CNPJ</span>
      <span class="field-value">{{ $report->user->cnpj ?? '—' }}</span>
    </td>
  </tr>
  @endif
</table>

<!-- MAIN TABLE -->
<table class="main-table">
<thead>
  <tr>
    <th style="width:14%;">Data</th>
    <th style="width:7%;">Dia</th>
    <th style="width:10%;">Entrada</th>
    <th style="width:12%;">Intervalo</th>
    <th style="width:12%;">Volta</th>
    <th style="width:12%;">Saída</th>
    <th style="width:14%;">Hora Extra</th>
    <th style="width:10%;">Total</th>
  </tr>
</thead>
<tbody>
@php
    $totalMinutes = 0;
    $totalNormalMinutes = 0;
    $totalExtraMinutes = 0;
    $rowIndex = 0;
@endphp

@forelse($dailyPoints as $point)
@php
    $totalMinutes += $point->total_minutes;
    $totalNormalMinutes += $point->normal_minutes;
    $totalExtraMinutes += $point->extra_minutes;
    $isWeekend = $point->work_date->isWeekend();
    $hasExtra = $point->extra_start_time && $point->extra_end_time;
    $rowClass = $isWeekend ? 'weekend' : ($rowIndex % 2 == 0 ? 'even' : 'odd');
    $rowIndex++;
@endphp
<tr class="{{ $rowClass }}">
  <td class="col-date">{{ $point->work_date->format('d/m/Y') }}</td>
  <td><span class="day-tag">{{ $point->work_date->translatedFormat('D') }}</span></td>
  <td>{!! $point->entry_time ? $point->entry_time->format('H:i') : '<span class="dash">—</span>' !!}</td>
  <td>{!! $point->lunch_out_time ? $point->lunch_out_time->format('H:i') : '<span class="dash">—</span>' !!}</td>
  <td>{!! $point->lunch_return_time ? $point->lunch_return_time->format('H:i') : '<span class="dash">—</span>' !!}</td>
  <td>{!! $point->exit_time ? $point->exit_time->format('H:i') : '<span class="dash">—</span>' !!}</td>
  <td>
    @if($hasExtra)
      <span class="extra-val">{{ $point->extra_start_time->format('H:i') }} – {{ $point->extra_end_time->format('H:i') }}</span>
    @else
      <span class="dash">—</span>
    @endif
  </td>
  <td><span class="total-val">{{ $point->total_hours_formatted }}</span></td>
</tr>
@empty
<tr><td colspan="8" style="text-align:center; padding:20px; color:#94a3b8;">Nenhuma hora registrada neste período</td></tr>
@endforelse

@if($dailyPoints->count() > 0)
<tr class="total-row">
  <td colspan="7" class="total-label">Total de Horas</td>
  <td class="total-grand">{{ minutesToHours($totalMinutes) }}</td>
</tr>
@endif
</tbody>
</table>

<!-- SUMMARY CARDS -->
<table class="summary-table">
  <tr>
    <td>
      <span class="card-label">Dias trabalhados</span>
      <span class="card-value">{{ $dailyPoints->count() }}</span>
    </td>
    <td>
      <span class="card-label">Horas normais</span>
      <span class="card-value">{{ minutesToHours($totalNormalMinutes) }}</span>
    </td>
    <td>
      <span class="card-label">Horas extras</span>
      <span class="card-value">{{ minutesToHours($totalExtraMinutes) }}</span>
    </td>
  </tr>
  <tr>
    <td>
      <span class="card-label">Total de horas</span>
      <span class="card-value">{{ minutesToHours($totalMinutes) }}</span>
    </td>
    <td>
      <span class="card-label">Carga horária contratual</span>
      <span class="card-value">{{ $report->contract_hours_formatted }}</span>
    </td>
    <td>
      <span class="card-label">Saldo</span>
      <span class="card-value">{{ str_replace('-', '', $report->balance_hours_formatted) }}</span>
      <!-- {{ $report->balance_minutes >= 0 ? '+' : '' }} -->
    </td>
  </tr>
</table>

<!-- DECLARATION -->
<p class="declaration">
  Declaro que as informações contidas neste espelho de horas são verdadeiras e correspondem aos horários efetivamente trabalhados no período indicado.
</p>

<!-- SIGNATURES -->
<!-- <table class="signatures-table">
  <tr>
    <td>
      <div class="sig-line"></div>
      <div class="sig-name">{{ $report->user->name }}</div>
      <div class="sig-role">Assinatura do Colaborador</div>
    </td>
    <td>
      <div class="sig-line"></div>
      <div class="sig-name">Responsável / Empresa</div>
      <div class="sig-role">Assinatura do Responsável</div>
    </td>
  </tr>
</table> -->

<!-- FOOTER -->
<table class="footer-table">
  <tr>
    <td>Espelho de Horas — Documento confidencial</td>
    <td>{{ $report->reference_month->translatedFormat('F/Y') }}</td>
  </tr>
</table>

</body>
</html>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Relatório Mensal - {{ $report->reference_month->format('m/Y') }}</title>
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
    margin-top: 25px;
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
    padding: 8px 5px;
    text-align: center;
  }
  .main-table thead th:first-child {
    text-align: left;
    padding-left: 10px;
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

  .col-date {
    text-align: left !important;
    padding-left: 10px !important;
    font-weight: 600;
    color: #1e293b !important;
  }

  .day-tag {
    font-size: 7px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #64748b;
    background: #f1f5f9;
    padding: 2px 5px;
    border-radius: 3px;
  }

  .extra-val {
    color: #d97706;
    font-weight: 600;
    font-size: 8px;
  }

  .total-val {
    color: #1e293b;
    font-weight: 700;
    font-size: 9px;
  }

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

  /* ═══ STATUS BADGES ═══ */
  .status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .status-draft { background: #e5e7eb; color: #374151; }
  .status-sent { background: #dbeafe; color: #1d4ed8; }
  .status-approved { background: #dcfce7; color: #166534; }
  .status-rejected { background: #fee2e2; color: #991b1b; }

  /* ═══ PRODUCTION LIST ═══ */
  .production-date {
    font-size: 10px;
    font-weight: 600;
    color: #1e293b;
    margin-top: 12px;
    margin-bottom: 6px;
    padding: 4px 8px;
    background: #f1f5f9;
    border-left: 3px solid #3b82f6;
  }
  .production-list {
    margin: 0 0 10px 0;
    padding-left: 20px;
  }
  .production-list li {
    margin: 4px 0;
    font-size: 9px;
    color: #475569;
  }
  .task-icon { color: #3b82f6; }
  .pr-icon { color: #8b5cf6; }

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

  /* ═══ PAGE BREAK ═══ */
  .page-break {
    page-break-before: always;
  }

  /* ═══ EMPTY MESSAGE ═══ */
  .empty-message {
    text-align: center;
    padding: 20px;
    color: #94a3b8;
    font-style: italic;
  }
</style>
</head>
<body>

<!-- TITLE -->
<div class="title-block">
  <h1>Relatório Mensal de Atividades</h1>
  <p>Demonstrativo de Horas Trabalhadas e Produção</p>
</div>

<!-- HEADER INFO -->
<table class="header-table">
  <tr>
    <td style="width: 40%;">
      <span class="field-label">Prestador de Serviço</span>
      <span class="field-value">{{ $report->user->name }}</span>
    </td>
    <td style="width: 30%;">
      <span class="field-label">Período</span>
      <span class="field-value">{{ $report->reference_month->translatedFormat('F/Y') }}</span>
    </td>
    <td style="width: 30%;">
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
  <tr>
    <td colspan="2">
      <span class="field-label">Status do Relatório</span>
      @switch($report->status->value)
        @case('draft')
          <span class="status-badge status-draft">Rascunho</span>
          @break
        @case('sent')
          <span class="status-badge status-sent">Enviado</span>
          @break
        @case('approved')
          <span class="status-badge status-approved">Aprovado</span>
          @break
        @case('rejected')
          <span class="status-badge status-rejected">Rejeitado</span>
          @break
      @endswitch
    </td>
    <td>
      @if($report->approver_name)
        <span class="field-label">Aprovado Por</span>
        <span class="field-value">{{ $report->approver_name }}</span>
      @endif
    </td>
  </tr>
</table>

<!-- SUMMARY CARDS -->
<table class="summary-table">
  <tr>
    <td>
      <span class="card-label">Carga Contratual</span>
      <span class="card-value c-blue">{{ $report->contract_hours_formatted }}</span>
    </td>
    <td>
      <span class="card-label">Total Trabalhado</span>
      <span class="card-value c-green">{{ $report->total_hours_formatted }}</span>
    </td>
    <td>
      <span class="card-label">Horas Extras</span>
      <span class="card-value c-amber">{{ $report->extra_hours_formatted }}</span>
    </td>
    <td>
      <span class="card-label">Saldo</span>
      <span class="card-value {{ $report->balance_minutes >= 0 ? 'c-purple' : 'c-red' }}">{{ $report->balance_hours_formatted }}</span>
    </td>
  </tr>
</table>

<!-- ESPELHO DE HORAS -->
<h3 class="section-title">Espelho de Horas</h3>
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
    $rowIndex = 0;
@endphp

@forelse($dailyPoints as $point)
@php
    $totalMinutes += $point->total_minutes;
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
<tr><td colspan="8" class="empty-message">Nenhuma hora registrada neste período</td></tr>
@endforelse

@if($dailyPoints->count() > 0)
<tr class="total-row">
  <td colspan="7" class="total-label">Total de Horas</td>
  <td class="total-grand">{{ minutesToHours($totalMinutes) }}</td>
</tr>
@endif
</tbody>
</table>

<!-- PRODUÇÃO -->
<h3 class="section-title">Produção do Período</h3>
@php
    $tasksByDate = $tasks->groupBy(fn($task) => $task->work_date->format('Y-m-d'));
@endphp

@if($tasks->count() > 0)
    @foreach($tasksByDate as $dateKey => $dayTasks)
        @php
            $date = \Carbon\Carbon::parse($dateKey);
        @endphp
        <div class="production-date">{{ $date->format('d/m/Y') }} — {{ $date->translatedFormat('l') }}</div>
        <ul class="production-list">
            @foreach($dayTasks as $task)
                <li>
                    <span class="task-icon">●</span> 
                    <strong>{{ $task->title }}</strong>
                    @if($task->description) — {{ Str::limit($task->description, 60) }}@endif
                    <span style="font-size: 7px; color: #64748b; margin-left: 4px;">[{{ $task->status->label() }}]</span>
                </li>
                @if($task->pullRequests->count() > 0)
                    @foreach($task->pullRequests as $pr)
                        <li style="margin-left: 15px;"><span class="pr-icon">◆</span> PR #{{ $pr->pr_number }}: {{ $pr->title }}</li>
                    @endforeach
                @endif
            @endforeach
        </ul>
    @endforeach
@else
    <p class="empty-message">Nenhuma tarefa registrada neste período.</p>
@endif

<!-- FOOTER -->
<table class="footer-table">
  <tr>
    <td>Relatório Mensal de Atividades — Documento confidencial</td>
    <td>{{ $report->reference_month->translatedFormat('F/Y') }}</td>
  </tr>
</table>

</body>
</html>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Relatório de Tarefas - {{ $date->format('m/Y') }}</title>
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
    width: 20%;
    padding: 14px;
    border-right: 1px solid #e2e8f0;
    vertical-align: top;
    text-align: center;
  }
  .summary-table td:last-child {
    border-right: none;
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
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
  }
  .c-green { color: #16a34a; }
  .c-blue { color: #2563eb; }
  .c-yellow { color: #ca8a04; }
  .c-gray { color: #6b7280; }
  .c-purple { color: #7c3aed; }

  /* ═══ SECTION TITLE ═══ */
  .section-title {
    font-size: 12px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
    margin-top: 20px;
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
    vertical-align: top;
  }
  .main-table tbody td.font-bold {
    font-weight: 700;
    color: #1e293b;
  }

  /* ═══ STATUS BADGES ═══ */
  .status-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 10px;
    font-size: 7px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .status-todo { background: #e5e7eb; color: #374151; }
  .status-doing { background: #fef3c7; color: #92400e; }
  .status-done { background: #dcfce7; color: #166534; }
  .status-cancelled { background: #fee2e2; color: #991b1b; }

  /* ═══ PR LINK ═══ */
  .pr-link {
    color: #7c3aed;
    font-size: 8px;
    display: block;
    margin-top: 2px;
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
  <h1>Relatório de Tarefas</h1>
  <p>Demonstrativo de Tarefas e Pull Requests</p>
</div>

<!-- HEADER INFO -->
<table class="header-table">
  <tr>
    <td style="width: 40%;">
      <span class="field-label">Desenvolvedor</span>
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
</table>

<!-- SUMMARY CARDS -->
<table class="summary-table">
  <tr>
    <td>
      <span class="card-label">Total de Tarefas</span>
      <span class="card-value c-blue">{{ $totalTasks }}</span>
    </td>
    <td>
      <span class="card-label">Concluídas</span>
      <span class="card-value c-green">{{ $doneTasks }}</span>
    </td>
    <td>
      <span class="card-label">Em Andamento</span>
      <span class="card-value c-yellow">{{ $doingTasks }}</span>
    </td>
    <td>
      <span class="card-label">A Fazer</span>
      <span class="card-value c-gray">{{ $todoTasks }}</span>
    </td>
    <td>
      <span class="card-label">Pull Requests</span>
      <span class="card-value c-purple">{{ $totalPRs }}</span>
    </td>
  </tr>
</table>

<!-- TASKS TABLE -->
<h3 class="section-title">Tarefas do Período</h3>
<table class="main-table">
  <thead>
    <tr>
      <th style="width: 12%;">Data</th>
      <th style="width: 40%;">Tarefa</th>
      <th style="width: 12%;">Status</th>
      <th style="width: 36%;">Pull Requests</th>
    </tr>
  </thead>
  <tbody>
    @php $rowIndex = 0; @endphp
    @forelse($tasks as $task)
      <tr class="{{ $rowIndex % 2 == 0 ? 'even' : 'odd' }}">
        <td class="font-bold">{{ $task->work_date->format('d/m/Y') }}</td>
        <td>
          <strong>{{ $task->title }}</strong>
          @if($task->description)
            <br><span style="color: #64748b; font-size: 8px;">{{ Str::limit($task->description, 80) }}</span>
          @endif
        </td>
        <td>
          @switch($task->status->value)
            @case('todo')
              <span class="status-badge status-todo">A Fazer</span>
              @break
            @case('doing')
              <span class="status-badge status-doing">Em Andamento</span>
              @break
            @case('done')
              <span class="status-badge status-done">Concluída</span>
              @break
            @case('cancelled')
              <span class="status-badge status-cancelled">Cancelada</span>
              @break
          @endswitch
        </td>
        <td>
          @if($task->pullRequests->count() > 0)
            @foreach($task->pullRequests as $pr)
              <span class="pr-link">◆ #{{ $pr->pr_number }}: {{ Str::limit($pr->title, 35) }}</span>
            @endforeach
          @else
            <span style="color: #94a3b8;">—</span>
          @endif
        </td>
      </tr>
      @php $rowIndex++; @endphp
    @empty
      <tr>
        <td colspan="4" class="empty-message">Nenhuma tarefa registrada neste período</td>
      </tr>
    @endforelse
  </tbody>
</table>

<!-- FOOTER -->
<table class="footer-table">
  <tr>
    <td>Relatório de Tarefas — Documento confidencial</td>
    <td>{{ $date->translatedFormat('F/Y') }}</td>
  </tr>
</table>

</body>
</html>

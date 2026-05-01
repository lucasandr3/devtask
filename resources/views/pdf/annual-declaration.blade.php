<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Declaração Anual do MEI</title>
<style>
body {
  font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
  font-size: 12px;
  color: #1f2933;
  margin: 40px;
}

h1, h2, h3 {
  margin-bottom: 6px;
  color: #0f172a;
}

h1 {
  font-size: 18px;
  margin-bottom: 15px;
}

h2 {
  font-size: 14px;
  margin-top: 20px;
  margin-bottom: 10px;
}

h3 {
  font-size: 13px;
  margin-top: 15px;
  margin-bottom: 8px;
}

.section {
  margin-top: 25px;
}

.summary-box {
  background: #f8fafc;
  border: 1px solid #e5e7eb;
  padding: 15px;
  border-radius: 8px;
  margin-top: 15px;
}

.summary-box p {
  margin: 8px 0;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

th, td {
  border: 1px solid #e5e7eb;
  padding: 6px;
  text-align: left;
  font-size: 11px;
}

th {
  background: #f3f4f6;
  font-weight: bold;
  color: #0f172a;
  text-align: center;
}

td.text-right {
  text-align: right;
}

.signature {
  margin-top: 50px;
  page-break-inside: avoid;
}

.signature p {
  margin: 15px 0;
  line-height: 1.8;
}
</style>
</head>

<body>

<h1>DECLARAÇÃO ANUAL DO MICROEMPREENDEDOR INDIVIDUAL (MEI)</h1>
<p><strong>Microempreendedor:</strong> {{ $declaration->user->name }}</p>
<p><strong>Ano de Referência:</strong> {{ $declaration->reference_year }}</p>
<p><strong>Data de geração:</strong> {{ $declaration->generated_at ? $declaration->generated_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</p>

<div class="section summary-box">
<h2>1. Resumo Financeiro</h2>
<p><strong>Receita Total do Ano:</strong> R$ {{ number_format($declaration->total_revenue, 2, ',', '.') }}</p>
<p><strong>Total de DAS Pago no Ano:</strong> R$ {{ number_format($declaration->total_das_paid, 2, ',', '.') }}</p>
<p><strong>Receita Líquida:</strong> R$ {{ number_format($declaration->net_revenue, 2, ',', '.') }}</p>
<p><strong>Total de Notas Fiscais Emitidas:</strong> {{ $declaration->total_invoices }}</p>
</div>

<div class="section">
<h2>2. Notas Fiscais Emitidas no Ano</h2>
@if($invoices->count() > 0)
<table>
<thead>
<tr>
<th>Número</th>
<th>Série</th>
<th>Data de Emissão</th>
<th>Valor (R$)</th>
<th>Tipo</th>
<th>Código Serviço</th>
</tr>
</thead>
<tbody>
@foreach($invoices as $invoice)
<tr>
<td>{{ $invoice->numero }}</td>
<td>{{ $invoice->serie }}</td>
<td>{{ $invoice->data_emissao->format('d/m/Y') }}</td>
<td class="text-right">{{ number_format($invoice->valor, 2, ',', '.') }}</td>
<td>{{ $invoice->invoice_type->label() }}</td>
<td>{{ $invoice->service_code ?: '-' }}</td>
</tr>
@endforeach
</tbody>
<tfoot>
<tr>
<td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
<td class="text-right" style="font-weight: bold;">R$ {{ number_format($invoices->sum('valor'), 2, ',', '.') }}</td>
<td colspan="2"></td>
</tr>
</tfoot>
</table>
@else
<p>Nenhuma nota fiscal emitida neste período.</p>
@endif
</div>

<div class="section">
<h2>3. DAS Pagos no Ano</h2>
@if($dasPayments->count() > 0)
<table>
<thead>
<tr>
<th>Mês de Referência</th>
<th>Data de Vencimento</th>
<th>Data de Pagamento</th>
<th>Valor (R$)</th>
</tr>
</thead>
<tbody>
@foreach($dasPayments as $das)
<tr>
<td>{{ $das->reference_month->format('m/Y') }}</td>
<td>{{ $das->due_date->format('d/m/Y') }}</td>
<td>{{ $das->payment_date ? $das->payment_date->format('d/m/Y') : '-' }}</td>
<td class="text-right">{{ number_format($das->amount, 2, ',', '.') }}</td>
</tr>
@endforeach
</tbody>
<tfoot>
<tr>
<td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
<td class="text-right" style="font-weight: bold;">R$ {{ number_format($dasPayments->sum('amount'), 2, ',', '.') }}</td>
</tr>
</tfoot>
</table>
@else
<p>Nenhum DAS pago neste período.</p>
@endif
</div>

<div class="section">
<h2>4. Observações</h2>
<p>Esta declaração consolida todas as informações financeiras do ano de {{ $declaration->reference_year }}, incluindo receitas provenientes de notas fiscais emitidas e despesas com DAS pagos.</p>
<p>Os valores apresentados são baseados nos registros cadastrados no sistema.</p>
</div>

<div class="section signature">
<p>Declaro que as informações acima são verdadeiras e correspondem aos registros do ano de {{ $declaration->reference_year }}.</p>
<br><br>
<p>_________________________________________<br>Assinatura do Microempreendedor</p>
<br><br>
<p><strong>Data:</strong> {{ now()->format('d/m/Y') }}</p>
</div>

</body>
</html>

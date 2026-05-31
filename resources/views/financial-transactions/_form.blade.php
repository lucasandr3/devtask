@php
    $typeValue = old('type', $transaction->type->value ?? $type ?? 'payable');
    $isEdit = isset($transaction);
    $isInstallmentEdit = $isEdit && $transaction->isInstallment();
    $paymentMode = old('payment_mode', 'single');
@endphp

<input type="hidden" name="type" value="{{ $typeValue }}">

<div>
    <x-input-label for="description" value="Descrição" />
    <x-text-input type="text" name="description" id="description" value="{{ old('description', $transaction->description ?? '') }}" required class="mt-1" placeholder="Ex: Mensalidade sistema, Venda parcelada cliente X" />
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
    @if(!$isEdit)
        <p class="text-xs text-muted-foreground mt-1">No parcelamento, cada parcela será exibida como "Descrição (1/12)".</p>
    @endif
</div>

@if(!$isEdit)
    <div
        x-data="{ mode: '{{ $paymentMode }}' }"
        class="rounded-xl border border-border bg-muted/30 p-4 space-y-4"
    >
        <p class="text-sm font-medium text-foreground">Forma de pagamento</p>
        <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="radio" name="payment_mode" value="single" x-model="mode" class="text-primary focus:ring-primary" @checked($paymentMode === 'single')>
                <span class="text-sm">À vista (único)</span>
            </label>
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="radio" name="payment_mode" value="installment" x-model="mode" class="text-primary focus:ring-primary" @checked($paymentMode === 'installment')>
                <span class="text-sm">Parcelado</span>
            </label>
        </div>

        <div x-show="mode === 'installment'" x-cloak class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-border">
            <div>
                <x-input-label for="installment_count" value="Quantidade de parcelas" />
                <x-text-input type="number" name="installment_count" id="installment_count" value="{{ old('installment_count', 2) }}" min="2" max="360" class="mt-1" x-bind:required="mode === 'installment'" />
                <x-input-error :messages="$errors->get('installment_count')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="installment_interval" value="Intervalo" />
                <select name="installment_interval" id="installment_interval" class="input mt-1" x-bind:required="mode === 'installment'">
                    @foreach(\App\Enums\InstallmentInterval::cases() as $interval)
                        <option value="{{ $interval->value }}" @selected(old('installment_interval', 'monthly') === $interval->value)>{{ $interval->label() }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('installment_interval')" class="mt-2" />
            </div>
            <div class="flex items-end">
                <p class="text-xs text-muted-foreground pb-2">O valor informado abaixo será o <strong>total</strong>, dividido entre as parcelas.</p>
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="amount" value="{{ $isInstallmentEdit ? 'Valor da parcela (R$)' : 'Valor (R$)' }}" />
        <x-text-input type="text" name="amount" id="amount" value="{{ old('amount', $transaction->amount ?? '') }}" required class="mt-1" data-money placeholder="R$ 0,00" />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="due_date" value="{{ $isInstallmentEdit ? 'Vencimento desta parcela' : ($isEdit ? 'Vencimento' : 'Primeiro vencimento') }}" />
        <x-text-input type="text" name="due_date" id="due_date" value="{{ old('due_date', isset($transaction) ? $transaction->due_date?->format('Y-m-d') : '') }}" required class="mt-1" data-datepicker />
        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="paid_at" value="Data de pagamento/recebimento (opcional)" />
        <x-text-input type="text" name="paid_at" id="paid_at" value="{{ old('paid_at', isset($transaction) ? $transaction->paid_at?->format('Y-m-d') : '') }}" class="mt-1" data-datepicker />
        @if(!$isEdit)
            <p class="text-xs text-muted-foreground mt-1">Disponível apenas para lançamento à vista.</p>
        @endif
    </div>
    <div>
        <x-input-label for="category" value="Categoria" />
        <x-text-input type="text" name="category" id="category" value="{{ old('category', $transaction->category ?? '') }}" class="mt-1" placeholder="Ex: Fornecedor, Aluguel, Serviços" />
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="client_id" value="Cliente (opcional)" />
        <select name="client_id" id="client_id" class="input mt-1">
            <option value="">—</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $transaction->client_id ?? '') == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <x-input-label for="project_id" value="Projeto (opcional)" />
        <select name="project_id" id="project_id" class="input mt-1">
            <option value="">—</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}" @selected(old('project_id', $transaction->project_id ?? '') == $project->id)>{{ $project->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <x-input-label for="notes" value="Observações" />
    <textarea name="notes" id="notes" rows="2" class="input mt-1">{{ old('notes', $transaction->notes ?? '') }}</textarea>
</div>

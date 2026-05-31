@props(['href' => null, 'fallback' => null, 'label' => 'Voltar'])

@php
    $backHref = back_url($fallback ?? $href ?? route('painel'));
@endphp

<a href="{{ $backHref }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-muted text-sm font-medium text-muted-foreground hover:bg-accent hover:text-accent-foreground transition-colors']) }}>
    <x-ui.icon name="arrow-back" class="size-5 shrink-0" />
    <span>{{ $label }}</span>
</a>

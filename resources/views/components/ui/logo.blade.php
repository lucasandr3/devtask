@props([
    'href' => null,
    'showText' => true,
    'size' => 'md',
    'textClass' => 'text-base font-bold tracking-tight whitespace-nowrap',
])

@php
    $boxes = [
        'sm' => 'h-8 w-8',
        'md' => 'h-9 w-9',
        'lg' => 'h-11 w-11',
    ];
    $boxClass = $boxes[$size] ?? $boxes['md'];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 min-w-0']) }}>
@else
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 min-w-0']) }}>
@endif
    <div @class(['inline-flex items-center justify-center shrink-0 rounded-md bg-primary overflow-hidden', $boxClass])>
        <img
            src="{{ asset('logo-white.png') }}"
            alt="{{ config('app.name', 'GestorPro') }}"
            class="max-w-[50px]"
        />
    </div>
    @if($showText)
        <span @class([$textClass, 'sidebar-brand-text'])>{{ config('app.name', 'GestorPro') }}</span>
    @endif
@if($href)
    </a>
@else
    </div>
@endif

@props([])

@php
$hasTop = (isset($leading) && !$leading->isEmpty()) || (isset($trailing) && !$trailing->isEmpty());
$hasBottom = isset($bottom) && !$bottom->isEmpty();
@endphp

<div {{ $attributes->merge(['class' => 'page-toolbar']) }}>
    @if($hasTop)
        <div class="page-toolbar-row">
            <div class="page-toolbar-leading">
                {{ $leading ?? '' }}
            </div>
            <div class="page-toolbar-trailing">
                {{ $trailing ?? '' }}
            </div>
        </div>
    @endif

    @if($hasBottom)
        <div @class(['page-toolbar-bottom', 'page-toolbar-divider' => $hasTop])>
            {{ $bottom }}
        </div>
    @elseif(!$hasTop && !$hasBottom)
        {{ $slot }}
    @endif
</div>

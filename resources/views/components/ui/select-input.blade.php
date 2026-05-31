@props([])

<div {{ $attributes->merge(['class' => 'select-input-wrapper']) }}>
    <select {{ $attributes->except('class')->merge(['class' => 'select-input']) }}>
        {{ $slot }}
    </select>
    <x-ui.icon name="chevron-down" class="select-input-icon" />
</div>

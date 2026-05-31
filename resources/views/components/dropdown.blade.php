@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'p-1'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    'top-left' => 'ltr:origin-bottom-left rtl:origin-bottom-right start-0 bottom-full mb-2',
    'top-right' => 'ltr:origin-bottom-right rtl:origin-top-left end-0 bottom-full mb-2',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$positionClasses = str_contains($align, 'top-') ? '' : 'mt-2';

$width = match ($width) {
    '48' => 'w-48',
    '56' => 'w-56',
    '80' => 'w-80',
    '96' => 'w-96',
    'full' => 'w-full',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 {{ $positionClasses }} {{ $width }} rounded-xl border border-border bg-popover text-popover-foreground shadow-lg overflow-hidden {{ $alignmentClasses }}"
            style="display: none;">
        <div class="{{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>

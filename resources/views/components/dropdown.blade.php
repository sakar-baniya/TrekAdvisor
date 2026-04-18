@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'dropdown-content'])

@php
$alignmentClasses = match ($align) {
    'left' => 'dropdown-align-left',
    'top' => 'dropdown-align-top',
    default => 'dropdown-align-right',
};

$width = match ($width) {
    '48' => 'dropdown-width',
    default => $width,
};
@endphp

<div class="dropdown" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div class="dropdown-trigger" @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="dropdown-menu {{ $width }} {{ $alignmentClasses }}"
            x-cloak
            @click="open = false">
        <div class="dropdown-panel {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>

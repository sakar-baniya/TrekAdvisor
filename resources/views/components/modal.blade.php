@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidth = [
    'sm' => 'modal-width-sm',
    'md' => 'modal-width-md',
    'lg' => 'modal-width-lg',
    'xl' => 'modal-width-xl',
    '2xl' => 'modal-width-2xl',
][$maxWidth];
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            // All focusable element types...
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)]
                // All non-disabled elements...
                .filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable().focus(), 100)' : '' }}
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    x-cloak
    class="modal-overlay"
>
    <div
        x-show="show"
        class="modal-backdrop"
        x-on:click="show = false"
        x-transition:enter="fade-transition"
        x-transition:enter-start="fade-enter-start"
        x-transition:enter-end="fade-enter-end"
        x-transition:leave="fade-transition"
        x-transition:leave-start="fade-leave-start"
        x-transition:leave-end="fade-leave-end"
    >
        <div class="modal-backdrop-layer"></div>
    </div>

    <div
        x-show="show"
        class="modal-panel {{ $maxWidth }}"
        x-transition:enter="modal-transition"
        x-transition:enter-start="modal-enter-start"
        x-transition:enter-end="modal-enter-end"
        x-transition:leave="modal-transition"
        x-transition:leave-start="modal-leave-start"
        x-transition:leave-end="modal-leave-end"
    >
        {{ $slot }}
    </div>
</div>

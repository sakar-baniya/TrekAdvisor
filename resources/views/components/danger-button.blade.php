<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-solid danger']) }}>
    {{ $slot }}
</button>

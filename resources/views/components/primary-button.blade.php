<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-solid primary']) }}>
    {{ $slot }}
</button>

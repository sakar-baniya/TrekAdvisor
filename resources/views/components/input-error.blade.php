@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-md p-3 list-disc list-inside']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif

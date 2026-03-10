@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[#d9d9d9] focus:border-black focus:ring-black rounded-md shadow-sm']) }}>

@props([
    'text' => 'Salvar',
    'icon' => 'save',
    'type' => 'submit',
    'disabled' => false,
])

<button
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => 'flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl shadow transition disabled:opacity-50']) }}
>
    <x-heroicon-o-{{ $icon }} class="w-5 h-5" />
    {{ $text }}
</button>

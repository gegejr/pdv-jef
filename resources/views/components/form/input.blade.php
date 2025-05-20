@props([
    'label' => '',
    'model' => '',
    'icon' => '',
    'type' => 'text',
    'step' => null,
])

<div>
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="mt-1 flex rounded shadow-sm border focus-within:ring-2 focus-within:ring-blue-500 bg-white overflow-hidden">
        @if ($icon)
            <span class="inline-flex items-center px-3 bg-gray-100 border-r text-gray-500">
                <x-heroicon-o-{{ $icon }} class="w-5 h-5" />
            </span>
        @endif
        <input
            type="{{ $type }}"
            @if ($step) step="{{ $step }}" @endif
            wire:model.defer="{{ $model }}"
            class="w-full border-0 focus:ring-0 p-2"
        />
    </div>
    @error($model) <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
</div>

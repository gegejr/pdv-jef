@props([
    'label' => '',
    'model' => '',
])

<div class="flex items-center space-x-2">
    <input
        type="checkbox"
        wire:model.defer="{{ $model }}"
        class="border-gray-300 rounded shadow-sm focus:ring-blue-500"
    >
    <label class="text-sm text-gray-700">{{ $label }}</label>
</div>
@error($model) <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

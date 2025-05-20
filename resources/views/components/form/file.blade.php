@props([
    'label' => '',
    'model' => '',
])

<div>
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <input
        type="file"
        wire:model="{{ $model }}"
        class="w-full border rounded p-1 mt-1 bg-white shadow-sm"
    />
    @error($model) <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
</div>

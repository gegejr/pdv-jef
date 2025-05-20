@props([
    'label' => '',
    'model' => '',
])

<div>
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <textarea
        wire:model.defer="{{ $model }}"
        rows="4"
        class="w-full mt-1 border rounded p-2 shadow-sm focus:ring-2 focus:ring-blue-500"
    ></textarea>
    @error($model) <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
</div>

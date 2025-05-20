@props([
    'label' => '',
    'model' => '',
    'options' => [],
    'value' => 'value',
    'text' => 'text',
])

<div>
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <select
        wire:model.defer="{{ $model }}"
        class="w-full mt-1 border rounded p-2 shadow-sm focus:ring-2 focus:ring-blue-500"
    >
        <option value="">Selecione</option>
        @foreach ($options as $option)
            <option value="{{ is_array($option) ? $option[$value] : $option->$value }}">
                {{ is_array($option) ? $option[$text] : $option->$text }}
            </option>
        @endforeach
    </select>
    @error($model) <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
</div>

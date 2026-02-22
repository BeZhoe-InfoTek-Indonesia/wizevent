@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'value' => null,
    'label' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'wireModel' => null,
    'wireModelLive' => null,
    'wireModelDebounce' => null,
    'autocomplete' => null,
    'class' => null,
])

@php
    $inputId = $id ?? $name ?? 'input-' . uniqid();
    $inputClass = 'w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all text-gray-900 placeholder-gray-400 font-medium ' . ($class ?? '');
    
    // Determine which wire:model to use
    $wireModelAttribute = null;
    if ($wireModel) {
        $wireModelAttribute = 'wire:model';
    } elseif ($wireModelLive) {
        $wireModelAttribute = 'wire:model.live';
    } elseif ($wireModelDebounce) {
        $wireModelAttribute = 'wire:model.debounce';
    }
    
    $wireModelValue = $wireModel ?? $wireModelLive ?? $wireModelDebounce;
@endphp

@if($label)
    <label for="{{ $inputId }}" class="block text-sm font-bold text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
@endif

<input
    type="{{ $type }}"
    id="{{ $inputId }}"
    @if($name) name="{{ $name }}" @endif
    @if($value !== null) value="{{ $value }}" @endif
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($required) required @endif
    @if($disabled) disabled @endif
    @if($readonly) readonly @endif
    @if($wireModelAttribute && $wireModelValue) {{ $wireModelAttribute }}="{{ $wireModelValue }}" @endif
    @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    class="{{ $inputClass }}"
/>

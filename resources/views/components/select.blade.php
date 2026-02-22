@props([
    'wireModel' => null,
    'wireModelLive' => null,
    'wireModelDebounce' => null,
    'options' => [],
    'placeholder' => null,
    'label' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'optionLabel' => null,
    'optionValue' => null,
    'clearable' => false,
    'searchable' => false,
    'class' => null,
    'name' => null,
])

@php
    $selectId = 'select-' . ($name ?? uniqid());
    $selectClass = 'w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all text-gray-900 font-medium appearance-none cursor-pointer ' . ($class ?? '');
    
    // Determine which wire:model to use
    $wireModelAttribute = null;
    $wireModelValue = null;
    if ($wireModel) {
        $wireModelAttribute = 'wire:model';
        $wireModelValue = $wireModel;
    } elseif ($wireModelLive) {
        $wireModelAttribute = 'wire:model.live';
        $wireModelValue = $wireModelLive;
    } elseif ($wireModelDebounce) {
        $wireModelAttribute = 'wire:model.debounce';
        $wireModelValue = $wireModelDebounce;
    }
@endphp

@if($label)
    <label for="{{ $selectId }}" class="block text-sm font-bold text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
@endif

<div class="relative">
    <select
        id="{{ $selectId }}"
        @if($name) name="{{ $name }}" @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($multiple) multiple @endif
        @if($wireModelAttribute && $wireModelValue) {{ $wireModelAttribute }}="{{ $wireModelValue }}" @endif
        @if($clearable) wire:click="$set('{{ $wireModelValue }}', null)" @endif
        class="{{ $selectClass }}"
    >
        @if($placeholder)
            <option value="" disabled>{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $option)
            @php
                $labelValue = $optionLabel ? data_get($option, $optionLabel) : $option;
                $valueValue = $optionValue ? data_get($option, $optionValue) : $option;
            @endphp
            <option value="{{ $valueValue }}">
                {{ $labelValue }}
            </option>
        @endforeach
    </select>
    
    {{-- Custom dropdown arrow --}}
    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7m0 0l-7 7-7 7m14 0l7-7-7 7" />
        </svg>
    </div>
</div>

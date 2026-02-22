@props([
    'wireModel' => null,
    'checked' => false,
    'disabled' => false,
    'color' => 'primary',
    'label' => null,
    'name' => null,
])

@php
    $toggleColors = [
        'primary' => 'bg-red-500',
        'secondary' => 'bg-gray-500',
        'success' => 'bg-green-500',
        'danger' => 'bg-red-600',
        'negative' => 'bg-red-600',
    ];
    
    $bgColor = $toggleColors[$color] ?? $toggleColors['primary'];
    
    // Generate unique ID for the toggle
    $toggleId = 'toggle-' . ($name ?? uniqid());
@endphp

<div class="relative inline-flex items-center">
    @if($label)
        <label for="{{ $toggleId }}" class="sr-only">{{ $label }}</label>
    @endif
    
    <input
        type="checkbox"
        id="{{ $toggleId }}"
        @if($name) name="{{ $name }}" @endif
        @if($wireModel) wire:model="{{ $wireModel }}" @endif
        @if(!$wireModel && $checked) checked @endif
        @if($disabled) disabled @endif
        class="peer sr-only"
    />
    
    <button
        type="button"
        @if($wireModel) wire:click="$toggle('{{ $wireModel }}')" @endif
        @if($disabled) disabled @endif
        role="switch"
        aria-checked="false"
        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 peer-checked:{{ $bgColor }} bg-gray-200 {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
    >
        <span
            aria-hidden="true"
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0 peer-checked:translate-x-5"
        ></span>
    </button>
</div>

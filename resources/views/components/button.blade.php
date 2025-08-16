@props([
    'type' => 'text',
    'label' => '',
    'error' => false,
    'required' => false,
    'placeholder' => '',
    'icon' => null
])

<div class="mb-4">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {{ $icon }}
                </svg>
            </div>
        @endif
        
        <input 
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => ($icon ? 'pl-10 ' : '') . 'w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white ' . 
                ($error ? 'border-red-500 dark:border-red-500' : 'border-gray-300')
            ]) }}
        >
    </div>
    
    @if($error)
        <p class="mt-1 text-sm text-red-500">{{ $error }}</p>
    @endif
</div>
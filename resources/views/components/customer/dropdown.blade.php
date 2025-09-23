@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
    $alignmentClasses = match ($align) {
        'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
        'top' => 'origin-top',
        default => 'ltr:origin-top-right rtl:origin-top-left end-0',
    };

    $width = match ($width) {
        '48' => 'w-48',
        default => $width,
    };
@endphp

<div @click.outside="open = false" @close.stop="open = false" class="relative" x-data="{ open: false }">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div @click="open = false" class="{{ $width }} {{ $alignmentClasses }} absolute z-50 mt-2 rounded-md shadow-lg"
        style="display: none;" x-show="open" x-transition:enter-end="opacity-100 scale-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter="transition ease-out duration-200"
        x-transition:leave-end="opacity-0 scale-95" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75">
        <div class="{{ $contentClasses }} rounded-md ring-1 ring-black ring-opacity-5">
            {{ $content }}
        </div>
    </div>
</div>

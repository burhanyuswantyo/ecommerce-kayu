@props(['active', 'icon' => null, 'badge' => null])

@php
  $classes =
      $active ?? false
          ? 'group flex items-center rounded-md bg-gray-200 px-2 py-3 text-sm font-medium text-gray-900'
          : 'group flex items-center rounded-md px-2 py-3 text-sm font-medium text-gray-800 hover:bg-gray-100 hover:bg-opacity-75';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
  @if ($icon)
    @svg($icon, 'mr-3 h-6 w-6 flex-shrink-0 text-gray-900')
  @endif
  <span class="flex-1">{{ $slot }}</span>
  @isset($badge)
    <span class="ml-3 inline-block rounded-full bg-gray-800 px-3 py-0.5 text-xs font-medium">{{ $badge }}</span>
  @endisset
</a>

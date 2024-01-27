{{-- prettier-ignore-start --}}
@props([
    'link',
    'icon' => '',
    'selected' => false
])
{{-- prettier-ignore-end --}}

<li class="relative">
  <a
    href="{{ $link }}"
    @class([
        'peer flex items-center justify-center h-10 transition duration-150',
        'dark:text-gray-50 hover:text-green-600 dark:hover:text-lividus-400' => !$selected,
        'text-green-600 dark:text-lividus-400' => $selected,
    ])
    wire:navigate
  >
    @if (empty($icon))
      <x-icon.home class="w-5" />
    @else
      <div class="w-5">{!! $icon !!}</div>
    @endif

    <span class="ml-2">{{ $slot }}</span>
  </a>

  <span @class([
      'absolute left-0 w-full h-1 transition-all duration-300 bg-green-500 dark:bg-lividus-400 rounded-full pointer-events-none',
      'opacity-0 -bottom-5 peer-hover:opacity-100 peer-hover:-bottom-1' => !$selected,
      'opacity-100 -bottom-1' => $selected,
  ])></span>
</li>

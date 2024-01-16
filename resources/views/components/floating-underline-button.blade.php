{{-- prettier-ignore-start --}}
@props([
    'link',
    'icon' => <<<'HTML'
    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
      <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
      <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
    </svg>
    HTML,
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
    <div class="size-5">{!! $icon !!}</div>

    <span class="ml-2">{{ $slot }}</span>
  </a>

  <span @class([
      'absolute left-0 w-full h-1 transition-all duration-300 bg-green-500 dark:bg-lividus-400 rounded-full pointer-events-none',
      'opacity-0 -bottom-5 peer-hover:opacity-100 peer-hover:-bottom-1' => !$selected,
      'opacity-100 -bottom-1' => $selected,
  ])></span>
</li>

@props(['href'])

<a
  class="m-1 inline-flex items-center rounded-full bg-emerald-200 px-2.5 py-0.5 text-xs text-emerald-900 transition-colors duration-150 ease-in hover:bg-emerald-300 dark:bg-lividus-700 dark:text-gray-50 dark:hover:bg-lividus-600"
  href="{{ $href }}"
  wire:navigate
>
  {{ $slot }}
</a>

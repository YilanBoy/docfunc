<li class="relative">
  <a
    href="{{ $link }}"
    @class([
        'peer flex items-center justify-center h-10 transition duration-150',
        'text-gray-400 hover:text-green-400 dark:hover:text-blue-400' => !$selected,
        'text-green-400 dark:text-blue-400' => $selected,
    ])
  >
    <i class="{{ $iconClass }}"></i><span class="ml-2">{{ $slot }}</span>
  </a>

  <span @class([
      'absolute left-0 w-full h-1 transition-all duration-300 bg-green-400 dark:bg-blue-400 rounded-full pointer-events-none',
      'opacity-0 -bottom-5 peer-hover:opacity-100 peer-hover:-bottom-1' => !$selected,
      'opacity-100 -bottom-1' => $selected,
  ])></span>
</li>

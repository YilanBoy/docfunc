@props(['id', 'placeholder'])

<div class="relative px-4 pb-4 pt-5">
  <input
    class="peer h-12 w-full border-b-2 border-gray-300 bg-transparent text-gray-900 placeholder-transparent transition duration-150 ease-in focus:border-emerald-500 focus:outline-none dark:border-gray-400 dark:text-gray-50 dark:focus:border-lividus-500"
    id="{{ $id }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->merge() }}
  >

  <label
    class="pointer-events-none absolute left-4 top-1 text-sm text-gray-600 transition-all peer-placeholder-shown:top-7 peer-placeholder-shown:text-lg peer-placeholder-shown:text-gray-400 peer-focus:top-1 peer-focus:text-sm peer-focus:text-gray-600 dark:text-gray-50 selection:dark:text-gray-50 dark:peer-placeholder-shown:text-gray-400 dark:peer-focus:text-gray-50"
    for="{{ $id }}"
  >
    {{ $placeholder }}
  </label>
</div>

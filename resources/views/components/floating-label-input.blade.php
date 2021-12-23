<div class="relative">
  <input
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ $value ?? '' }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes }}
    class="w-full h-10 text-gray-900 placeholder-transparent transition duration-150 ease-in bg-transparent border-b-2 border-gray-300 peer focus:outline-none focus:border-blue-500 dark:text-gray-50"
  >

  <label
    for="{{ $name }}"
    class="absolute left-0 -top-3.5 text-gray-600 text-sm pointer-events-none transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm selection:dark:text-gray-50 dark:peer-placeholder-shown:text-gray-50 dark:peer-focus:text-gray-50"
  >
    {{ $placeholder }}
  </label>
</div>

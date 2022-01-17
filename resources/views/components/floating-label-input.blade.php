<div class="relative px-2 pt-5 pb-2 rounded-lg bg-slate-100 dark:bg-slate-600">
  <input
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ $value ?? '' }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes }}
    class="w-full h-10 text-gray-900 placeholder-transparent transition duration-150 ease-in bg-transparent border-b-2 border-gray-300 peer focus:outline-none focus:border-blue-500 dark:text-gray-50 dark:border-gray-400 dark:focus:border-red-500"
  >

  <label
    for="{{ $name }}"
    class="absolute text-sm text-gray-600 transition-all pointer-events-none top-1 left-2 dark:text-gray-50 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-5 peer-focus:top-1 peer-focus:text-gray-600 peer-focus:text-sm selection:dark:text-gray-50 dark:peer-placeholder-shown:text-gray-400 dark:peer-focus:text-gray-50"
  >
    {{ $placeholder }}
  </label>
</div>

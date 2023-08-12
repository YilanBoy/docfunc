<button
  {{ $attributes->merge([
      'type' => 'submit',
      'class' =>
          'inline-flex items-center justify-center rounded-xl border border-transparent bg-green-600 px-4 py-2 font-semibold uppercase tracking-widest text-gray-50 ring-green-300 transition duration-150 ease-in-out hover:bg-green-700 focus:border-green-700 focus:outline-none focus:ring active:bg-green-600 disabled:opacity-25 dark:bg-lividus-600 dark:ring-lividus-400 dark:hover:bg-lividus-500 dark:focus:border-lividus-700 dark:active:bg-lividus-600',
  ]) }}
>
  {{ $slot }}
</button>

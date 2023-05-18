<button
  {{ $attributes->merge([
      'type' => 'submit',
      'class' =>
          'inline-flex items-center justify-center rounded-xl border border-transparent bg-green-600 px-4 py-2 font-semibold uppercase tracking-widest text-gray-50 ring-green-300 transition duration-150 ease-in-out hover:bg-green-700 focus:border-green-700 focus:outline-none focus:ring active:bg-green-600 disabled:opacity-25 dark:bg-[#1b87f5] dark:hover:bg-[#44adff] dark:ring-[#8edaff] dark:focus:border-[#146fe1] dark:active:bg-[#1b87f5]',
  ]) }}
>
  {{ $slot }}
</button>

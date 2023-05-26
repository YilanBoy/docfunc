<div
  {{ $attributes->merge(['class' => 'rounded-xl border border-dashed border-green-500 bg-gray-50 p-5 shadow-lg dark:border-indigo-500 dark:bg-gray-800 dark:shadow-none']) }}
>
  {{ $slot }}
</div>

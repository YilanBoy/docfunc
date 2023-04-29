<div
  {{ $attributes->merge(['class' => 'bg-gray-50 p-5 shadow-lg rounded-xl dark:bg-gray-700 border-dashed border border-green-500 dark:border-indigo-500']) }}
>
  {{ $slot }}
</div>

<div
  {{ $attributes->merge(['class' => 'rounded-xl border border-dashed border-emerald-500 bg-gray-50 p-5 dark:border-indigo-500 dark:bg-gray-800']) }}
>
  {{ $slot }}
</div>

@props(['status'])

@if ($status)
  <div
    {{ $attributes->merge(['class' => 'dark:text-emerald-400 text-emerald-700 rounded-md px-4 py-2 bg-emerald-300/20 border-l-4 border-emerald-400']) }}
  >
    {{ $status }}
  </div>
@endif

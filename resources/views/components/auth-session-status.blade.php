@props(['status'])

@if ($status)
  <div {{ $attributes->merge(['class' => 'text-gray-700 px-4 py-2 bg-emerald-200 border-l-4 border-emerald-400']) }}>
    {{ $status }}
  </div>
@endif

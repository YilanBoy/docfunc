@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-emerald-600']) }}>
        {{ $status }}
    </div>
@endif

@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-green-600']) }}>
        {{ $status }}
    </div>
@endif

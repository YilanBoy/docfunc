@props(['errors'])

@if ($errors->any())
  <div
    {{ $attributes->merge(['class' => 'text-red-700 dark:text-red-400 rounded-md px-4 py-2 bg-red-300/20 border-l-4 border-red-400']) }}
  >
    <ul class="list-inside list-disc">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

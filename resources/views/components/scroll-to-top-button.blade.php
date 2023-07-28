<button
  {{ $attributes->merge([
      'class' =>
          'fixed bottom-7 right-7 z-10 hidden h-16 w-16 rounded-full bg-green-600 text-gray-50 shadow-md transition duration-150 ease-in hover:scale-110 hover:shadow-xl dark:bg-lividus-600',
      'id' => 'scroll-to-top-btn',
      'type' => 'button',
      'title' => 'Go to top',
  ]) }}
>
  <span class="m-auto text-3xl font-bold">
    <i class="bi bi-arrow-up"></i>
  </span>
</button>

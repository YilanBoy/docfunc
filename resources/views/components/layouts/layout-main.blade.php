<div
  class="relative flex min-h-screen flex-col justify-between selection:bg-green-300 selection:text-green-900 dark:selection:bg-indigo-300 dark:selection:text-indigo-900"
>
  <livewire:shared.layouts.header />

  {{ $slot }}

  <livewire:shared.layouts.footer />
</div>

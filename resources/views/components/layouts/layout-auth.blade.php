<div
  class="relative min-h-screen selection:bg-green-300 selection:text-green-900 dark:selection:bg-indigo-300 dark:selection:text-indigo-900"
  {{ $attributes }}
>
  {{-- icon background left side --}}
  <div class="absolute left-10 top-32 -z-10 hidden md:block">
    <x-icon.controller class="size-64 rotate-12 text-gray-300/40 dark:text-lividus-900" />
  </div>

  <div class="absolute left-8 top-[26rem] -z-10 hidden md:block">
    <x-icon.rocket-takeoff-fill class="size-24 scale-x-[-1] text-gray-300/40 dark:text-lividus-900" />
  </div>

  <div class="absolute left-40 top-[32rem] -z-10 hidden md:block">
    <x-icon.chat-heart class="size-64 -rotate-12 scale-x-[-1] text-gray-300/40 dark:text-lividus-900" />
  </div>

  <div class="absolute left-20 top-[48rem] -z-10 hidden md:block">
    <x-icon.globe-americas class="size-24 -rotate-12 scale-x-[-1] text-gray-300/40 dark:text-lividus-900" />
  </div>

  {{-- icon background right side --}}
  <div class="absolute right-44 top-24 -z-10 hidden md:block">
    <x-icon.bug class="size-24 rotate-45 text-gray-300/40 dark:text-lividus-900" />
  </div>

  <div class="absolute right-20 top-72 -z-10 hidden md:block">
    <x-icon.filetype-php class="size-64 -rotate-12 text-gray-300/40 dark:text-lividus-900" />
  </div>

  <div class="absolute right-10 top-[38rem] -z-10 hidden md:block">
    <x-icon.watch class="size-24 rotate-45 text-gray-300/40 dark:text-lividus-900" />
  </div>

  <div class="absolute right-48 top-[44rem] -z-10 hidden md:block">
    <x-icon.laptop class="size-64 rotate-12 text-gray-300/40 dark:text-lividus-900" />
  </div>

  {{ $slot }}
</div>

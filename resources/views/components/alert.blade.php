<div
  x-cloak
  x-show="openAlertBox"
  class="fixed bottom-0 left-0"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-300"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
>
  <div class="p-10">
    <div
      class="flex items-center text-white text-lg font-bold px-4 py-3 rounded shadow-md"
      :class="alertBackgroundColor"
      role="alert"
    >
      <span x-html="alertMessage" class="flex items-center"></span>
      <button type="button" class="flex" @click="openAlertBox = false">
        <svg
          fill="none"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="1"
          viewBox="0 0 24 24"
          stroke="currentColor"
          class="w-4 h-4 ml-4"
        >
          <path d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
  </div>
</div>

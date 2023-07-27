<div
  class="fixed bottom-0 left-0"
  x-cloak
  x-data="alertComponent(@js(session()->get('alert')))"
  x-init="if (alert !== null) {
      showAlert(alert.status, alert.message)

      setTimeout(function() {
          openAlertBox = false
      }, 3000);
  }"
  @info-badge.window="
    showAlert(event.detail.status, event.detail.message)

    setTimeout(function () {
      openAlertBox = false
    }, 3000);
  "
  x-show="openAlertBox"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-300"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
>
  <div class="p-10">
    <div
      class="flex items-center rounded px-4 py-3 text-lg font-bold text-white shadow-md"
      role="alert"
      :class="alertBackgroundColor"
    >
      <span
        class="flex items-center"
        x-html="alertMessage"
      ></span>
      <button
        class="flex"
        type="button"
        @click="openAlertBox = false"
      >
        <svg
          class="ml-4 h-4 w-4"
          fill="none"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="1"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
  </div>
</div>

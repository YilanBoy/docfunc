<div
  x-data="{
      showDialog: false
  }"
  @reset.window="showDialog = ! showDialog"
>
  {{-- In work, do what you enjoy. --}}
  <div
    class="fixed inset-0 z-30 overflow-y-auto"
    role="dialog"
    aria-labelledby="modal-title"
    aria-modal="true"
    x-cloak
    x-show="showDialog"
  >
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

      {{-- Background backdrop, show/hide based on modal state. --}}
      <div
        class="fixed inset-0 bg-gray-500/75 transition-opacity"
        x-show="showDialog"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-description="Background backdrop, show/hide based on modal state."
      ></div>

      {{-- Modal panel, show/hide based on modal state. --}}
      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="inline-block overflow-hidden rounded-lg text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            x-show="showDialog"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-trap.noscroll="showDialog"
          >
            <div class="bg-white px-4 pb-4 pt-5 dark:bg-gray-700 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div
                  class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10"
                >
                  <!-- Heroicon name: outline/exclamation-triangle -->
                  <svg
                    class="h-6 w-6 text-blue-600"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 10.5v3.75m-9.303 3.376C1.83 19.126 2.914 21 4.645 21h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 4.88c-.866-1.501-3.032-1.501-3.898 0L2.697 17.626zM12 17.25h.007v.008H12v-.008z"
                    />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                  <h3
                    class="text-xl font-medium leading-6 text-gray-900 dark:text-gray-50"
                    id="modal-title"
                  >
                    重置資料
                  </h3>
                  <div class="mt-2">
                    <p class="text-gray-500 dark:text-gray-400">
                      請問是否重置資料？
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 dark:bg-[#343d4c] sm:flex sm:flex-row-reverse sm:px-6">
              <button
                class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto"
                type="button"
                x-on:click="showDialog = false"
              >
                繼續編輯
              </button>
              <button
                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-500 dark:text-gray-50 dark:hover:bg-gray-600 sm:ml-3 sm:mt-0 sm:w-auto"
                type="button"
                x-on:click="showDialog = false"
                wire:click="$emit('resetForm')"
              >
                重置資料
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

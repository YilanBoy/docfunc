<div
  x-data="{ loadFinish: false }"
  x-init="window.addEventListener('load', () => {
      loadFinish = true;
  });"
  x-cloak
  x-show="loadFinish"
  class="flex items-stretch justify-center space-x-4"
>
  <div class="hidden xl:block xl:w-1/6"></div>

  <div class="w-full p-2 md:w-[700px] lg:p-0">
    <div class="flex w-full flex-col items-center justify-center space-y-6">

      <div class="fill-current text-2xl text-gray-700 dark:text-gray-50">
        <i class="bi bi-pencil-square"></i><span class="ml-4">編輯文章</span>
      </div>

      {{-- editor --}}
      <x-card class="w-full">
        {{-- validate error message --}}
        <x-auth-validation-errors
          class="mb-4"
          :errors="$errors"
        />

        <form
          wire:submit.prevent="update"
          id="edit-post"
        >
          {{-- image --}}
          <div
            x-data="{ isUploading: false, progress: 0 }"
            x-on:livewire-upload-start="isUploading = true"
            x-on:livewire-upload-finish="isUploading = false"
            x-on:livewire-upload-error="isUploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress"
            class="text-base"
          >
            {{-- Upload Area --}}
            <div
              x-ref="uploadBlock"
              class="relative flex cursor-pointer flex-col items-center rounded-lg border-2 border-dashed border-green-500 bg-transparent px-4 py-6 tracking-wide text-green-500 transition-all duration-300 hover:border-green-600 hover:text-green-600 dark:border-indigo-400 dark:text-indigo-400 dark:hover:border-indigo-300 dark:hover:text-indigo-300"
            >
              <input
                wire:model="image"
                x-on:dragenter="
                  $refs.uploadBlock.classList.remove('text-green-500', 'dark:text-indigo-400', 'border-green-500', 'dark:border-indigo-400')
                  $refs.uploadBlock.classList.add('text-green-600', 'dark:text-indigo-300', 'border-green-600', 'dark:border-indigo-300')
                "
                x-on:dragleave="
                  $refs.uploadBlock.classList.add('text-green-500', 'dark:text-indigo-400', 'border-green-500', 'dark:border-indigo-400')
                  $refs.uploadBlock.classList.remove('text-green-600', 'dark:text-indigo-300', 'border-green-600', 'dark:border-indigo-300')
                "
                x-on:drop="
                  $refs.uploadBlock.classList.add('text-green-500', 'dark:text-indigo-400', 'border-green-500', 'dark:border-indigo-400')
                  $refs.uploadBlock.classList.remove('text-green-600', 'dark:text-indigo-300', 'border-green-600', 'dark:border-indigo-300')
                "
                type="file"
                class="absolute inset-0 z-50 m-0 h-full w-full cursor-pointer p-0 opacity-0 outline-none"
                title=""
              >

              <div class="flex flex-col items-center justify-center space-y-2 text-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="h-10 w-10"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"
                  />
                </svg>

                <p>預覽圖 (jpg, jpeg, png, bmp, gif, svg, or webp)</p>
              </div>
            </div>

            {{-- Progress Bar --}}
            <div
              x-show="isUploading"
              class="relative mt-4 pt-1"
            >
              <div class="mb-4 flex h-4 overflow-hidden rounded bg-green-200 text-xs dark:bg-indigo-200">
                <div
                  x-bind:style="`width:${progress}%`"
                  class="flex flex-col justify-center whitespace-nowrap bg-green-500 text-center text-white shadow-none dark:bg-indigo-500"
                ></div>
              </div>
            </div>

            @if ($previewUrl && empty($image))
              <div class="relative mt-4 w-full md:w-1/2">
                <img
                  src="{{ $previewUrl }}"
                  alt="preview image"
                  class="rounded-lg"
                >

                <button
                  type="button"
                  onclick="confirm('你確定要刪除預覽圖嗎？') || event.stopImmediatePropagation()"
                  wire:click="$set('previewUrl', null)"
                  class="group absolute inset-0 flex flex-1 items-center justify-center rounded-lg transition-all duration-150 hover:bg-gray-600/50 hover:backdrop-blur-sm"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-24 w-24 opacity-0 transition-all duration-150 group-hover:text-gray-50 group-hover:opacity-100"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </button>
              </div>
            @endif

            @if (!$errors->has('image') && $image)
              <div class="relative mt-4 w-full md:w-1/2">
                <img
                  src="{{ $image->temporaryUrl() }}"
                  alt="preview image"
                  class="rounded-lg"
                >

                <button
                  type="button"
                  wire:click="$set('image', null)"
                  class="group absolute inset-0 flex flex-1 items-center justify-center rounded-lg transition-all duration-150 hover:bg-gray-600/50 hover:backdrop-blur-sm"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-24 w-24 opacity-0 transition-all duration-150 group-hover:text-gray-50 group-hover:opacity-100"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </button>
              </div>
            @endif
          </div>

          {{-- title --}}
          <div class="mt-4">
            <label
              for="title"
              class="hidden"
            >文章標題</label>

            <input
              wire:model="title"
              type="text"
              id="title"
              name="title"
              placeholder="文章標題"
              value=""
              required
              autofocus
              class="form-input h-12 w-full rounded-md border border-gray-300 text-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-50 dark:placeholder-white"
            >
          </div>

          {{-- classfication --}}
          <div class="mt-5">
            <label
              for="category_id"
              class="hidden"
            >分類</label>

            <select
              wire:model="categoryId"
              id="category_id"
              name="category_id"
              required
              class="form-select h-12 w-full rounded-md border border-gray-300 text-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-50"
            >
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- tags --}}
          <div
            wire:ignore
            class="mt-5"
          >
            <label
              for="tags"
              class="hidden"
            >標籤（最多 5 個）</label>

            <input
              id="tags"
              type="text"
              name="tags"
              value="{{ $this->tags }}"
              placeholder="標籤（最多 5 個）"
              class="h-12 w-full rounded-md bg-white dark:bg-gray-700"
            >
          </div>

          {{-- body --}}
          <div
            wire:ignore
            class="mt-5 max-w-none"
          >
            <label
              for="editor"
              class="hidden"
            >內文</label>

            <div id="editor">{!! $this->body !!}</div>
          </div>

          {{-- mobile device --}}
          <div class="mt-4 flex items-center justify-between xl:hidden">
            {{-- show characters count --}}
            <div
              wire:ignore
              class="dark:text-gray-50"
            >
              <span class="character-counter"></span>
            </div>

            {{-- save button --}}
            <x-button wire:loading.attr="disabled">
              <span wire:loading.remove>
                <i class="bi bi-save2-fill"></i>
              </span>

              <span wire:loading>
                <svg
                  class="h-5 w-5 animate-spin"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  ></circle>
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
              </span>

              <span class="ml-2">儲存</span>
            </x-button>
          </div>
        </form>
      </x-card>

    </div>
  </div>

  {{-- desktop sidebar --}}
  <div class="hidden xl:block xl:w-1/6">
    <div class="sticky top-1/2 flex w-full -translate-y-1/2 flex-col">
      {{-- charactor count --}}
      <div
        wire:ignore
        class="flex w-full items-center justify-start rounded-xl bg-gradient-to-r from-white to-white/0 p-4 dark:from-gray-700 dark:to-gray-700/0 dark:text-gray-50"
      >
        <span class="character-counter"></span>
      </div>

      {{-- save button --}}
      <button
        wire:loading.attr="disabled"
        type="submit"
        form="edit-post"
        class="group mt-4 inline-flex h-14 w-14 items-center justify-center rounded-xl border border-transparent bg-blue-600 text-gray-50 ring-blue-300 transition duration-150 ease-in-out focus:border-blue-700 focus:outline-none focus:ring active:bg-blue-700"
      >
        <span
          wire:loading.remove
          class="text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125"
        >
          <i class="bi bi-save2-fill"></i>
        </span>

        <span wire:loading>
          <svg
            class="h-10 w-10 animate-spin"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
          </svg>
        </span>
      </button>
    </div>
  </div>
</div>

@push('script')
  {{-- binding livewire attribute 'tags' --}}
  <script>
    let tags = document.querySelector('#tags');

    tags.addEventListener('change', function(event) {
      @this.set('tags', event.target.value);
    });
  </script>

  <script>
    let debounceTimer;

    // https://webdesign.tutsplus.com/tutorials/javascript-debounce-and-throttle--cms-36783
    const debounce = (callback, time) => {
      window.clearTimeout(debounceTimer);
      debounceTimer = window.setTimeout(callback, time);
    };

    // wait page loaded fully
    window.addEventListener('load', () => {
      // get ckeditor textarea value
      const domEditableElement = document.querySelector('.ck-editor__editable');

      const editorInstance = domEditableElement.ckeditorInstance;

      editorInstance.model.document.on('change:data', () => {
        debounce(() => {
          @this.set('body', editorInstance.getData());
        }, 500);
      });
    });
  </script>

  <script>
    let leaveStatus = false

    window.addEventListener('leaveThePage', function(event) {
      leaveStatus = event.detail.permit;
    });

    window.addEventListener('beforeunload', function(event) {
      if (!leaveStatus) {
        // standard practice for canceling events, but Chrome does not support
        event.preventDefault();

        // Firefox not support
        // to cancel the event, Chrome requires that the returnValue must be given a value
        // In the past, this value could be displayed in the alert window, but now it is no longer supported, so just give it a null value.
        event.returnValue = '';
      }
    });
  </script>
@endpush

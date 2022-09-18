<div
  x-data="{ loadFinish: false }"
  x-init="
    window.addEventListener('load', () => {
      loadFinish = true;
    });
  "
  x-cloak
  x-show="loadFinish"
  class="flex items-stretch justify-center space-x-4"
>
  <div class="hidden xl:block xl:w-1/6"></div>

  <div class="w-full px-4 lg:w-2/3 xl:w-7/12 lg:px-0 z-0">
    <div class="flex flex-col items-center justify-center w-full space-y-6">
      {{-- title --}}
      <div class="text-2xl text-gray-800 fill-current dark:text-gray-50">
        <i class="bi bi-pencil-fill"></i><span class="ml-4">新增文章</span>
      </div>

      {{-- editor --}}
      <x-card class="w-full">
        {{-- validation error message --}}
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form wire:submit.prevent="store" id="create-post">

          {{-- title --}}
          <div class="mt-2">
            <label for="title" class="hidden">文章標題</label>

            <input
              wire:model.lazy="title"
              type="text"
              id="title"
              name="title"
              placeholder="文章標題"
              value=""
              required
              autofocus
              class="w-full h-12 text-lg border border-gray-300 rounded-md shadow-sm form-input focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white"
            >
          </div>

          {{-- classfication --}}
          <div class="mt-5">
            <label for="category_id" class="hidden">分類</label>

            <select
              wire:model="categoryId"
              id="category_id"
              name="category_id"
              required
              class="w-full h-12 text-lg border border-gray-300 rounded-md shadow-sm form-select focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50"
            >
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- tags --}}
          <div wire:ignore class="mt-5">
            <label for="tags" class="hidden">標籤 (最多 5 個)</label>

            <input
              id="tags"
              type="text"
              name="tags"
              value="{{ $tags }}"
              placeholder="標籤 (最多 5 個)"
              class="w-full h-12 bg-white rounded-md dark:bg-gray-600"
            >
          </div>

          {{-- photo --}}
          <div
            x-data="{ isUploading: false, progress: 0 }"
            x-on:livewire-upload-start="isUploading = true"
            x-on:livewire-upload-finish="isUploading = false"
            x-on:livewire-upload-error="isUploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress"
            class="mt-5 text-base"
          >
            <div
              x-ref="uploadBlock"
              class="relative flex flex-col items-center px-4 py-6 mt-4 tracking-wide text-green-500 transition-all duration-300 bg-transparent border-2 border-green-500 border-dashed rounded-lg cursor-pointer dark:text-blue-400 dark:border-blue-400 hover:text-green-600 dark:hover:text-blue-300 hover:border-green-600 dark:hover:border-blue-300"
            >
              <input
                wire:model="photo"
                x-on:dragenter="
                  $refs.uploadBlock.classList.remove('text-green-500', 'dark:text-blue-400', 'border-green-500', 'dark:border-blue-400')
                  $refs.uploadBlock.classList.add('text-green-600', 'dark:text-blue-300', 'border-green-600', 'dark:border-blue-300')
                "
                x-on:dragleave="
                  $refs.uploadBlock.classList.add('text-green-500', 'dark:text-blue-400', 'border-green-500', 'dark:border-blue-400')
                  $refs.uploadBlock.classList.remove('text-green-600', 'dark:text-blue-300', 'border-green-600', 'dark:border-blue-300')
                "
                x-on:drop="
                  $refs.uploadBlock.classList.add('text-green-500', 'dark:text-blue-400', 'border-green-500', 'dark:border-blue-400')
                  $refs.uploadBlock.classList.remove('text-green-600', 'dark:text-blue-300', 'border-green-600', 'dark:border-blue-300')
                "
                type="file"
                class="absolute inset-0 z-50 w-full h-full p-0 m-0 outline-none opacity-0 cursor-pointer"
                title=""
              >

              <div class="flex flex-col items-center justify-center space-y-2 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-10 h-10">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/>
                </svg>

                <p>預覽圖 (jpg, jpeg, png, bmp, gif, svg, or webp)</p>
              </div>
            </div>

            {{-- Progress Bar --}}
            <div x-show="isUploading" class="mt-4">
              <progress max="100" x-bind:value="progress"></progress>
            </div>

            @if ($photo && !$errors->any())
              <img src="{{ $photo->temporaryUrl() }}" alt="preview image" class="h-48 mt-4 rounded-lg">
            @endif
          </div>

          {{-- body --}}
          <div wire:ignore class="mt-5 max-w-none">
            <label for="editor" class="hidden">內文</label>

            <div id="editor">{!! $this->body !!}</div>
          </div>

          {{-- mobile device --}}
          <div class="flex items-center justify-between mt-4 xl:hidden">
            {{-- show characters count --}}
            <div wire:ignore class="dark:text-gray-50">
              <span class="character-counter"></span>
            </div>

            {{-- save button --}}
            <x-button wire:loading.attr="disabled">
              <span wire:loading.remove>
                <i class="bi bi-save2-fill"></i>
              </span>

              <span wire:loading>
                <svg class="w-5 h-5 text-gray-700 animate-spin dark:text-gray-50"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                >
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
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
    <div class="sticky flex flex-col w-full -translate-y-1/2 top-1/2">
      {{-- character count --}}
      <div
        wire:ignore
        class="flex items-center justify-start w-full p-4 bg-gradient-to-r from-white to-white/0 rounded-xl dark:text-gray-50 dark:from-gray-700 dark:to-gray-700/0"
      >
        <span class="character-counter"></span>
      </div>

      {{-- save button --}}
      <button
        wire:loading.attr="disabled"
        type="submit"
        form="create-post"
        class="inline-flex items-center justify-center mt-4 transition duration-150 ease-in-out bg-blue-500 border border-transparent w-14 h-14 group rounded-xl text-gray-50 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300"
      >
        <span wire:loading.remove
              class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
          <i class="bi bi-save2-fill"></i>
        </span>

        <span wire:loading>
          <svg class="w-10 h-10 text-gray-700 animate-spin dark:text-gray-50"
               xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
          >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
          </svg>
        </span>
      </button>
    </div>
  </div>

  <livewire:posts.partials.keep-auto-save-dialog
    :show-dialog="$showDialog"></livewire:posts.partials.keep-auto-save-dialog>
</div>

@push('script')
  {{-- binding livewire attribute 'tags' --}}
  <script>
    let tags = document.querySelector('#tags');

    tags.addEventListener('change', function (event) {
      @this.
      set('tags', event.target.value);
    });

    window.addEventListener('removeAllTags', () => {
      tagify.removeAllTags();
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
          @this.
          set('body', editorInstance.getData());
        }, 500);
      });

      window.addEventListener('resetCkeditorContent', () => {
        editorInstance.setData('');
      });
    });
  </script>

  <script>
    let leaveStatus = false

    window.addEventListener('leaveThePage', function (event) {
      leaveStatus = event.detail.permit;
    });

    window.addEventListener('beforeunload', function (event) {
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

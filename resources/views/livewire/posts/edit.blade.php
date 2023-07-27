@section('title', '編輯文章')

@push('css')
  @vite(['resources/css/editor.css', 'node_modules/@yaireo/tagify/dist/tagify.css', 'resources/css/missing-content-style.css'])
@endpush

@push('scriptInHead')
  {{-- Ckeditor --}}
  <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endpush

@push('scriptInBody')
  {{-- Tagify --}}
  @vite('resources/ts/tagify.ts')
@endpush

{{-- edit post --}}
<div class="container mx-auto">
  <div
    class="flex items-stretch justify-center space-x-4"
    x-data="{
        editorElement: document.querySelector('#editor'),
        characterCounter: document.querySelectorAll('.character-counter'),
        maxCharacters: 20000,
        editorDebounceTimer: null,
        debounce(callback, time) {
            // https://webdesign.tutsplus.com/tutorials/javascript-debounce-and-throttle--cms-36783
            window.clearTimeout(this.editorDebounceTimer);
            this.editorDebounceTimer = window.setTimeout(callback, time);
        },
        csrf_token: @js(csrf_token())
    }"
    x-init="// init the create post page
    ClassicEditor.create(editorElement, {
            // Editor configuration.
            wordCount: {
                onUpdate: (stats) => {
                    // The character count has exceeded the maximum limit
                    let isLimitExceeded = stats.characters > maxCharacters;
                    // The character count is approaching the maximum limit
                    let isCloseToLimit = !isLimitExceeded && stats.characters > maxCharacters * 0.8;

                    // update character count in HTML element
                    characterCounter.forEach((element) => {
                        element.textContent = `${stats.characters} / ${maxCharacters}`;
                        // If the character count is approaching the limit
                        // add the class 'text-yellow-500' to the 'wordsBox' element to turn the text yellow
                        element.classList.toggle('text-yellow-500', isCloseToLimit);
                        // If the character count exceeds the limit
                        // add the class 'text-red-400' to the 'wordsBox' element to turn the text red
                        element.classList.toggle('text-red-400', isLimitExceeded);
                    });
                }
            },
            simpleUpload: {
                // The URL that the images are uploaded to.
                uploadUrl: '/api/images/upload',

                // laravel sanctum need csrf token to authenticate
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
            }
        })
        .then((editor) => {
            // create a listener to update the ckeditor content
            window.addEventListener('updateCkeditorContent', (event) => {
                editor.setData(event.detail.content);
            });

            // binding the value of the ckeditor to the livewire attribute 'body'
            editor.model.document.on('change:data', () => {
                debounce(() => {
                    $wire.set('post.body', editor.getData());
                }, 500);
            });
        })
        .catch((err) => {
            console.error(err.stack);
        });

    // binding the value of the tag input to the livewire attribute 'tags'
    $refs.tags.addEventListener('change', (event) => {
        $wire.set('post.tags', event.target.value);
    });

    window.addEventListener('updateTags', (event) => {
        tagify.removeAllTags();
        tagify.addTags(JSON.parse(event.detail.tags));
    });"
  >
    <div class="hidden xl:block xl:w-1/6"></div>

    <div class="w-full p-2 md:w-[700px] lg:p-0">
      <div class="flex w-full flex-col items-center justify-center space-y-6">
        {{-- title --}}
        <div class="fill-current text-2xl text-gray-700 dark:text-gray-50">
          <i class="bi bi-pencil-square"></i><span class="ml-4">編輯文章</span>
        </div>

        {{-- editor --}}
        <x-card class="w-full">
          {{-- validation error message --}}
          <x-auth-validation-errors
            class="mb-4"
            :errors="$errors"
          />

          <form
            id="edit-post"
            wire:submit.prevent="update"
          >
            <div class="grid grid-cols-2 gap-5">
              {{-- preview image --}}
              <div
                class="col-span-2 text-base"
                x-data="{ isUploading: false, progress: 0 }"
                x-on:livewire-upload-start="isUploading = true"
                x-on:livewire-upload-finish="isUploading = false"
                x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
              >
                {{-- Upload Area --}}
                <div
                  class="relative flex cursor-pointer flex-col items-center rounded-lg border-2 border-dashed border-green-500 bg-transparent px-4 py-6 tracking-wide text-green-500 transition-all duration-300 hover:border-green-600 hover:text-green-600 dark:border-indigo-400 dark:text-indigo-400 dark:hover:border-indigo-300 dark:hover:text-indigo-300"
                  x-ref="uploadBlock"
                >
                  <input
                    class="absolute inset-0 z-50 m-0 h-full w-full cursor-pointer p-0 opacity-0 outline-none"
                    type="file"
                    title=""
                    wire:model="post.image"
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
                  >

                  <div class="flex flex-col items-center justify-center space-y-2 text-center">
                    <svg
                      class="h-10 w-10"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
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
                  class="relative mt-4 pt-1"
                  x-show="isUploading"
                >
                  <div class="mb-4 flex h-4 overflow-hidden rounded bg-green-200 text-xs dark:bg-indigo-200">
                    <div
                      class="flex flex-col justify-center whitespace-nowrap bg-green-500 text-center text-white shadow-none dark:bg-indigo-500"
                      x-bind:style="`width:${progress}%`"
                    >
                    </div>
                  </div>
                </div>

                @if ($post['preview_url'] && empty($post['image']))
                  <div class="relative mt-4 w-full md:w-1/2">
                    <img
                      class="rounded-lg"
                      src="{{ $post['preview_url'] }}"
                      alt="preview image"
                    >

                    <button
                      class="group absolute inset-0 flex flex-1 items-center justify-center rounded-lg transition-all duration-150 hover:bg-gray-600/50 hover:backdrop-blur-sm"
                      type="button"
                      onclick="confirm('你確定要刪除預覽圖嗎？') || event.stopImmediatePropagation()"
                      wire:click="$set('previewUrl', null)"
                    >
                      <svg
                        class="h-24 w-24 opacity-0 transition-all duration-150 group-hover:text-gray-50 group-hover:opacity-100"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
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

                @if (!$errors->has('image') && $post['image'])
                  <div class="relative mt-4 w-full md:w-1/2">
                    <img
                      class="rounded-lg"
                      src="{{ $post['image']->temporaryUrl() }}"
                      alt="preview image"
                    >

                    <button
                      class="group absolute inset-0 flex flex-1 items-center justify-center rounded-lg transition-all duration-150 hover:bg-gray-600/50 hover:backdrop-blur-sm"
                      type="button"
                      wire:click="$set('post.image', null)"
                    >
                      <svg
                        class="h-24 w-24 opacity-0 transition-all duration-150 group-hover:text-gray-50 group-hover:opacity-100"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
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

              {{-- classfication --}}
              <div class="col-span-2 md:col-span-1">
                <label
                  class="hidden"
                  for="category_id"
                >分類</label>

                <select
                  class="form-select h-12 w-full rounded-md border border-gray-300 text-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-50"
                  id="category_id"
                  name="category_id"
                  wire:model="post.category_id"
                  required
                >
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                      {{ $category->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              {{-- is private --}}
              <div class="col-span-2 flex items-center md:col-span-1">
                <label
                  class="inline-flex items-center"
                  for="is-private"
                >
                  <input
                    class="form-checkbox h-6 w-6 rounded border-gray-300 text-indigo-400 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    id="is-private"
                    name="is-private"
                    type="checkbox"
                    wire:model="post.is_private"
                  >
                  <span class="ml-2 text-lg text-gray-600 dark:text-gray-50">文章不公開</span>
                </label>
              </div>

              {{-- title --}}
              <div class="col-span-2">
                <label
                  class="hidden"
                  for="title"
                >文章標題</label>

                <input
                  class="form-input h-12 w-full rounded-md border border-gray-300 text-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-50 dark:placeholder-white"
                  id="title"
                  name="title"
                  type="text"
                  value=""
                  wire:model="post.title"
                  placeholder="文章標題"
                  required
                  autofocus
                >
              </div>

              {{-- tags --}}
              <div
                class="col-span-2"
                wire:ignore
              >
                <label
                  class="hidden"
                  for="tags"
                >標籤 (最多 5 個)</label>

                <input
                  class="h-12 w-full rounded-md bg-white dark:bg-gray-700"
                  id="tags"
                  name="tags"
                  type="text"
                  value="{{ $post['tags'] }}"
                  x-ref="tags"
                  placeholder="標籤 (最多 5 個)"
                >
              </div>

              {{-- body --}}
              <div
                class="col-span-2 max-w-none"
                wire:ignore
              >
                <label
                  class="hidden"
                  for="editor"
                >內文</label>

                <div id="editor">{!! $post['body'] !!}</div>
              </div>
            </div>

            {{-- mobile device --}}
            <div class="mt-4 flex items-center justify-between xl:hidden">
              {{-- show characters count --}}
              <div
                class="dark:text-gray-50"
                wire:ignore
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
                    >
                    </path>
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
        {{-- character count --}}
        <div
          class="flex w-full items-center justify-start rounded-xl bg-gradient-to-r from-white to-white/0 p-4 dark:from-gray-700 dark:to-gray-700/0 dark:text-gray-50"
          wire:ignore
        >
          <span class="character-counter"></span>
        </div>

        {{-- save button --}}
        <button
          class="group mt-4 inline-flex h-14 w-14 items-center justify-center rounded-xl border border-transparent bg-blue-600 text-gray-50 ring-blue-300 transition duration-150 ease-in-out focus:border-blue-700 focus:outline-none focus:ring active:bg-blue-700"
          form="edit-post"
          type="submit"
          wire:loading.attr="disabled"
        >
          <span
            class="text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125"
            wire:loading.remove
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
              >
              </path>
            </svg>
          </span>
        </button>

        {{-- show dialog --}}
        <button
          class="group mt-4 inline-flex h-14 w-14 items-center justify-center rounded-xl border border-transparent bg-orange-600 text-gray-50 ring-orange-300 transition duration-150 ease-in-out focus:border-orange-700 focus:outline-none focus:ring active:bg-orange-700"
          type="button"
          x-on:click="$dispatch('reset')"
        >
          <span class="text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125">
            <i class="bi bi-arrow-counterclockwise"></i>
          </span>
        </button>
      </div>
    </div>

    <livewire:posts.partials.reset-dialog />
  </div>
</div>

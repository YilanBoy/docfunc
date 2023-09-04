{{-- create new post --}}
<x-layouts.layout>
  <div class="container mx-auto">
    <div class="flex items-stretch justify-center space-x-4">
      <div class="hidden xl:block xl:w-1/6"></div>

      <div class="w-full p-2 md:w-[700px] lg:p-0">
        <div class="flex w-full flex-col items-center justify-center space-y-6">
          {{-- title --}}
          <div class="fill-current text-2xl text-gray-700 dark:text-gray-50">
            <i class="bi bi-pencil-fill"></i><span class="ml-4">新增文章</span>
          </div>

          {{-- editor --}}
          <x-card class="w-full">
            {{-- validation error message --}}
            <x-auth-validation-errors
              class="mb-4"
              :errors="$errors"
            />

            <form
              id="create-post"
              wire:submit="store"
            >
              <div class="grid grid-cols-2 gap-5">
                {{-- preview image --}}
                <x-post-form.upload-image-block
                  :image="$image"
                  :preview-url="$form->preview_url"
                  :showPreview="!$errors->has('image') && !is_null($image)"
                />

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
                    wire:model.live="form.category_id"
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
                      wire:model.live="form.is_private"
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
                    wire:model.live="form.title"
                    placeholder="文章標題"
                    required
                    autofocus
                  >
                </div>

                {{-- tags --}}
                <x-post-form.tagify />

                {{-- body --}}
                <x-post-form.ckedtior />
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
            form="create-post"
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
        </div>
      </div>
    </div>
  </div>
</x-layouts.layout>

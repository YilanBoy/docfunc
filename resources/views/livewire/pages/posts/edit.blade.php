{{-- edit post --}}
<x-layouts.layout-main>
  <div class="container mx-auto">
    <div class="flex items-stretch justify-center space-x-4">
      <div class="hidden xl:block xl:w-1/6"></div>

      <div class="w-full p-2 md:w-[750px] lg:p-0">
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
              wire:submit="update"
            >
              <div class="grid grid-cols-2 gap-5">
                {{-- post preview image --}}
                <x-post-form.upload-image-block
                  :image-model="'form.image'"
                  :preview-url-model="'form.preview_url'"
                  :image="$form->image"
                  :preview-url="$form->preview_url"
                  :showPreview="!$errors->has('form.image') && !is_null($form->image)"
                />

                {{-- post classfication --}}
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

                {{-- post private setting --}}
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

                {{-- post title --}}
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
                    wire:model.live="form.title"
                    placeholder="文章標題"
                    required
                    autofocus
                  >
                </div>

                {{-- post tags --}}
                <x-post-form.tagify :model="'form.tags'" />

                {{-- post body --}}
                <x-post-form.ckedtior :model="'form.body'" />
              </div>

              {{-- show in mobile device --}}
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

                  <span
                    class="h-5 w-5"
                    wire:loading
                  >
                    <x-icon.animate-spin />
                  </span>

                  <span class="ml-2">儲存</span>
                </x-button>
              </div>
            </form>
          </x-card>

        </div>
      </div>

      <x-post-form.desktop-side-menu :form-id="'edit-post'" />
    </div>
  </div>
</x-layouts.layout-main>

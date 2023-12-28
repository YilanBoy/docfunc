@props([
    'imageModel' => 'image',
    'image' => null,
    'showUploadedImage' => false,
    'previewUrlModel' => 'previewUrl',
    'previewUrl' => null,
])

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
      wire:model.live="{{ $imageModel }}"
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
      <x-icon.upload class="size-10" />

      <p>預覽圖 (jpg, jpeg or png)</p>
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

  {{-- preview image preview --}}
  @if ($previewUrl && is_null($image))
    <div class="relative mt-4 w-full md:w-1/2">
      <img
        class="rounded-lg"
        id="preview-image"
        src="{{ $previewUrl }}"
        alt="preview image"
      >

      <button
        class="group absolute right-0 top-0 flex h-full w-full items-center justify-center rounded-lg transition-all duration-150 hover:bg-gray-600/50 hover:backdrop-blur-sm"
        type="button"
        wire:confirm="你確定要刪除預覽圖嗎？"
        wire:click="$set('{{ $previewUrlModel }}', '')"
      >
        <x-icon.x-circle
          class="size-24 opacity-0 transition-all duration-150 group-hover:text-gray-50 group-hover:opacity-100"
        />
      </button>
    </div>
  @endif

  {{-- uploaded image preview --}}
  @if ($showUploadedImage)
    <div class="relative mt-4 w-full md:w-1/2">
      <img
        class="rounded-lg"
        id="upload-image"
        src="{{ $image->temporaryUrl('local') }}"
        alt="preview image"
      >

      <button
        class="group absolute right-0 top-0 flex h-full w-full items-center justify-center rounded-lg transition-all duration-150 hover:bg-gray-600/50 hover:backdrop-blur-sm"
        type="button"
        wire:click="$set('{{ $imageModel }}', null)"
      >
        <x-icon.x-circle
          class="size-24 opacity-0 transition-all duration-150 group-hover:text-gray-50 group-hover:opacity-100"
        />
      </button>
    </div>
  @endif
</div>

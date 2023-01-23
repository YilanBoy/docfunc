<div
  x-cloak
  x-data="{ isOpen: false }"
  x-init="
    Livewire.on('closeCreateCommentModal', () => {
      isOpen = false
    })

    // when enable the preview, reload the scripts
    Livewire.hook('message.processed', (el, component) => {
      document.querySelectorAll('#creating-comment-preview pre code').forEach((element) => {
        window.hljs.highlightElement(element)
      })
    })
  "
  x-show="isOpen"
  @keydown.escape.window="isOpen = false"
  @open-create-comment-modal.window="
    isOpen = true
    $nextTick(() => $refs.createComment.focus())
  "
  class="fixed z-30 inset-0"
  aria-labelledby="modal-title"
  role="dialog"
  aria-modal="true"
>
  <div class="flex items-end justify-center min-h-screen">
    {{-- gray background --}}
    <div
      x-show="isOpen"
      x-transition.opacity
      class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
      aria-hidden="true">
    </div>

    {{--  modal  --}}
    <div
      x-show="isOpen"
      x-transition.origin.bottom.duration.300ms
      class="bg-gray-50 dark:bg-gray-700 rounded-tl-xl rounded-tr-xl overflow-auto transform transition-all p-5 sm:max-w-2xl sm:w-full max-h-[36rem]"
    >
      {{-- close modal button --}}
      <div class="absolute top-5 right-5">
        <button
          @click="isOpen = false"
          class="text-gray-400 hover:text-gray-500"
        >
          <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div>
        <div class="text-center text-2xl font-bold text-gray-900 dark:text-gray-50 space-x-2 mb-5">
          <i class="bi bi-chat-dots-fill"></i>
          <span>新增留言</span>
        </div>

        <form wire:submit.prevent="store" class="space-y-4">
          @if (! $convertToHtml)
            <div>
              <label for="body"></label>

              <textarea
                x-ref="createComment"
                wire:model.lazy="body"
                id="body"
                name="body"
                rows="12"
                placeholder="寫下你的留言吧！**支援 Markdown**"
                required
                class="w-full border border-gray-300 rounded-md shadow-sm form-textarea text-lg focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white resize-none"
              ></textarea>

              @error('body')
              <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
          @else
            <div id="creating-comment-preview" class="space-y-2">
              <div class="space-x-4">
                <span class="font-semibold dark:text-gray-50">{{ auth()->user()->name }}</span>
                <span class="text-gray-400">{{ now()->format('Y 年 m 月 d 日') }}</span>
              </div>
              <div class="comment-body h-80 overflow-auto">
                {!! $this->convertedBody !!}
              </div>
            </div>
          @endif

          <div class="flex items-center justify-between space-x-3">
            <label
              for="create-comment-markdown-converter"
              class="flex cursor-pointer select-none items-center space-x-4"
            >
              <x-toggle-switch
                wire:model="convertToHtml"
                id="create-comment-markdown-converter"
                title="顯示轉換後的內容"
                placeholder="show converted results"
              />

              <span class="dark:text-gray-50">預覽</span>
            </label>

            <x-button>
              <i class="bi bi-save2-fill"></i>
              <span class="ml-2">儲存</span>
            </x-button>

          </div>
        </form>

      </div>

    </div> <!-- end modal -->
  </div>
</div>

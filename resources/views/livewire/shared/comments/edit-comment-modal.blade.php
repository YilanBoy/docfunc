@script
  <script>
    Alpine.data('editCommentModal', () => ({
      observers: [],
      modalIsOpen: false,
      commentId: null,
      body: @entangle('body'),
      openModal(event) {
        this.commentId = event.detail.commentId;
        this.body = event.detail.body;

        this.modalIsOpen = true;
        this.$nextTick(() => this.$refs.editCommentTextarea?.focus());
      },
      closeModal() {
        this.modalIsOpen = false;
      },
      submitForm() {
        $wire.update(this.commentId);
      },
      tabToFourSpaces() {
        this.$el.setRangeText('    ', this.$el.selectionStart, this.$el.selectionStart, 'end');
      },
      bodyIsEmpty() {
        return this.body === '';
      },
      init() {
        let previewObserver = new MutationObserver(() => {
          this.$refs.editCommentModal
            .querySelectorAll('pre code:not(.hljs)')
            .forEach((element) => {
              hljs.highlightElement(element);
            });
        });

        previewObserver.observe(this.$refs.editCommentModal, {
          childList: true,
          subtree: true,
          attributes: true,
          characterData: false
        });

        this.observers.push(previewObserver);
      },
      destroy() {
        this.observers.forEach((observer) => {
          observer.disconnect();
        })
      }
    }));
  </script>
@endscript

<div
  class="fixed inset-0 z-30"
  role="dialog"
  aria-labelledby="modal-title"
  aria-modal="true"
  x-cloak
  x-data="editCommentModal"
  x-ref="editCommentModal"
  x-show="modalIsOpen"
  x-on:open-edit-comment-modal.window="openModal"
  x-on:close-edit-comment-modal.window="closeModal"
  x-on:keydown.escape.window="closeModal"
>
  <div class="flex min-h-screen items-end justify-center">
    {{-- gray background --}}
    <div
      class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
      aria-hidden="true"
      x-show="modalIsOpen"
      x-transition.opacity
    ></div>

    {{--  modal  --}}
    <div
      class="mx-2 max-h-[36rem] w-full transform overflow-auto rounded-tl-xl rounded-tr-xl bg-gray-50 p-5 transition-all dark:bg-gray-800 md:max-w-2xl"
      x-show="modalIsOpen"
      x-transition.origin.bottom.duration.300ms
    >
      {{-- close modal button --}}
      <div class="absolute right-5 top-5">
        <button
          class="text-gray-400 hover:text-gray-500"
          type="button"
          x-on:click="closeModal"
        >
          <x-icon.x class="size-8" />
        </button>
      </div>

      <div>
        <div class="mb-5 flex items-center justify-center space-x-2 text-2xl text-gray-900 dark:text-gray-50">
          <x-icon.chat-dots class="w-8" />
          <span>編輯留言</span>
        </div>

        <form
          class="space-y-4"
          x-on:submit.prevent="submitForm"
        >
          @if ($previewIsEnabled)
            <div class="space-y-2">
              <div class="space-x-4">
                <span class="font-semibold dark:text-gray-50">{{ auth()->user()->name }}</span>
                <span class="text-gray-400">{{ now()->format('Y 年 m 月 d 日') }}</span>
              </div>
              <div class="comment-body h-80 overflow-auto">
                {!! $this->convertedBody !!}
              </div>
            </div>
          @else
            <div>
              <label for="edit-comment-textarea"></label>

              <textarea
                class="form-textarea w-full resize-none rounded-md border border-gray-300 font-jetbrains-mono text-lg focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-50 dark:placeholder-white"
                name="body"
                x-ref="editCommentTextarea"
                {{-- change tab into 4 spaces --}}
                x-on:keydown.tab.prevent="tabToFourSpaces"
                x-model="body"
                rows="12"
                placeholder="寫下你的留言吧！**支援 Markdown**"
                required
              ></textarea>

              @error('body')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
              @enderror
            </div>
          @endif

          <div class="flex items-center justify-between space-x-3">
            <x-toggle-switch
              wire:model.live="previewIsEnabled"
              :id="'edit-comment-modal-preview'"
              x-bind:disabled="bodyIsEmpty"
            >
              預覽
            </x-toggle-switch>

            <x-button>
              <x-icon.save class="w-5" />
              <span class="ml-2">更新留言</span>
            </x-button>
          </div>
        </form>
      </div>
    </div> <!-- end modal -->
  </div>
</div>

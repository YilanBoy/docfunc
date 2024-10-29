@script
  <script>
    Alpine.data('createCommentModal', () => ({
      observers: [],
      modalIsOpen: false,
      submitIsEnabled: false,
      body: @entangle('body'),
      captchaSiteKey: @js(config('services.captcha.site_key')),
      parentId: null,
      openModal(event) {
        this.parentId = event.detail.parentId;

        this.modalIsOpen = true;
        this.$nextTick(() => this.$refs.createCommentTextarea?.focus());
      },
      closeModal() {
        this.modalIsOpen = false;
      },
      submitForm() {
        $wire.store(this.parentId)
      },
      tabToFourSpaces() {
        this.$el.setRangeText('    ', this.$el.selectionStart, this.$el.selectionStart, 'end');
      },
      bodyIsEmpty() {
        return this.body === '';
      },
      submitIsDisabled() {
        return this.submitIsEnabled === false;
      },
      informationOnSubmitButton() {
        return this.submitIsEnabled ? '儲存' : '驗證中'
      },
      init() {
        turnstile.ready(() => {
          turnstile.render(this.$refs.turnstileBlock, {
            sitekey: this.captchaSiteKey,
            callback: (token) => {
              this.$wire.set('captchaToken', token);
              this.submitIsEnabled = true;
            }
          });
        });

        let previewObserver = new MutationObserver(() => {
          this.$refs.createCommentModal
            .querySelectorAll('pre code:not(.hljs)')
            .forEach((element) => {
              hljs.highlightElement(element);
            });
        });

        previewObserver.observe(this.$refs.createCommentModal, {
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
  x-data="createCommentModal"
  x-ref="createCommentModal"
  x-show="modalIsOpen"
  x-on:open-create-comment-modal.window="openModal"
  x-on:close-create-comment-modal.window="closeModal"
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
          <span>新增留言</span>
        </div>

        <form
          class="space-y-4"
          x-on:submit.prevent="submitForm"
        >
          @if ($previewIsEnabled)
            <div class="space-y-2">
              <div class="space-x-4">
                <span class="font-semibold dark:text-gray-50">
                  @if (auth()->check())
                    {{ auth()->user()->name }}
                  @else
                    訪客
                  @endif
                </span>
                <span class="text-gray-400">{{ now()->format('Y 年 m 月 d 日') }}</span>
              </div>
              <div class="comment-body h-80 overflow-auto">
                {!! $this->convertedBody !!}
              </div>
            </div>
          @else
            <div>
              <label for="create-comment-textarea"></label>

              <textarea
                class="form-textarea w-full resize-none rounded-md border border-gray-300 font-jetbrains-mono text-lg focus:border-indigo-300 focus:ring focus:ring-indigo-200/50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-50 dark:placeholder-gray-50"
                name="body"
                x-ref="createCommentTextarea"
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

              @error('captchaToken')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
              @enderror
            </div>
          @endif

          <div
            class="hidden"
            x-ref="turnstileBlock"
            wire:ignore
          ></div>

          <div class="flex items-center justify-between space-x-3">
            <x-toggle-switch
              :id="'create-comment-modal-preview'"
              wire:model.live="previewIsEnabled"
              x-bind:disabled="bodyIsEmpty"
            >
              預覽
            </x-toggle-switch>

            <x-button x-bind:disabled="submitIsDisabled">
              <x-icon.save
                class="mr-2 w-5"
                x-cloak
                x-show="submitIsEnabled"
              />
              <x-icon.animate-spin
                class="mr-2 h-5 w-5 text-gray-50"
                x-cloak
                x-show="submitIsDisabled"
              />
              <span x-text="informationOnSubmitButton"></span>
            </x-button>
          </div>
        </form>
      </div>
    </div>
    {{-- end modal --}}
  </div>
</div>

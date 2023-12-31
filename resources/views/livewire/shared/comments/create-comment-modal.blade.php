<div
  class="fixed inset-0 z-30"
  role="dialog"
  aria-labelledby="modal-title"
  aria-modal="true"
  x-cloak
  x-data="{ isOpen: false }"
  x-show="isOpen"
  x-on:open-create-comment-modal.window="
    isOpen = true;
    $nextTick(() => $refs.createCommentTextarea.focus());
  "
  x-on:close-create-comment-modal.window="isOpen = false"
  x-on:keydown.escape.window="isOpen = false"
>
  <div class="flex min-h-screen items-end justify-center">
    {{-- gray background --}}
    <div
      class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
      aria-hidden="true"
      x-show="isOpen"
      x-transition.opacity
    >
    </div>

    {{--  modal  --}}
    <div
      class="max-h-[36rem] transform overflow-auto rounded-tl-xl rounded-tr-xl bg-gray-50 p-5 transition-all sm:w-full sm:max-w-2xl dark:bg-gray-800"
      x-show="isOpen"
      x-transition.origin.bottom.duration.300ms
    >
      {{-- close modal button --}}
      <div class="absolute right-5 top-5">
        <button
          class="text-gray-400 hover:text-gray-500"
          type="button"
          x-on:click="isOpen = false"
        >
          <x-icon.x class="size-8" />
        </button>
      </div>

      <div>
        <div class="mb-5 space-x-2 text-center text-2xl text-gray-900 dark:text-gray-50">
          <i class="bi bi-chat-dots-fill"></i>
          <span>新增留言</span>
        </div>

        <form
          class="space-y-4"
          wire:submit="store"
          x-data="{ enableSubmit: false, body: @entangle('body') }"
        >
          @if (!$convertToHtml)
            <div>
              <label for="body"></label>

              <textarea
                class="form-textarea w-full resize-none rounded-md border border-gray-300 font-jetbrains-mono text-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-50 dark:placeholder-white"
                id="body"
                name="body"
                x-ref="createCommentTextarea"
                {{-- change tab into 4 spaces --}}
                x-on:keydown.tab.prevent="$el.setRangeText( '    ', $el.selectionStart, $el.selectionStart, 'end')"
                wire:model.blur="body"
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
          @else
            <div
              class="space-y-2"
              id="create-comment-preview"
            >
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
          @endif

          <div
            class="hidden"
            wire:ignore
            x-data="{
                captchaSiteKey: @js(config('services.captcha.site_key'))
            }"
            x-init="// Execute the captcha check
            turnstile.ready(function() {
                turnstile.render($el, {
                    sitekey: captchaSiteKey,
                    callback: function(token) {
                        $wire.set('captchaToken', token);
                        enableSubmit = true;
                    }
                });
            });"
          ></div>

          <div class="flex items-center justify-between space-x-3">
            <x-toggle-switch
              :id="'create-comment-modal-preview'"
              wire:model.live="convertToHtml"
              x-bind:disabled="body === ''"
            >
              預覽
            </x-toggle-switch>

            <x-button x-bind:disabled="!enableSubmit">
              <i
                class="bi bi-save2-fill mr-2"
                x-cloak
                x-show="enableSubmit"
              ></i>
              <x-icon.animate-spin
                class="mr-2 h-5 w-5 text-gray-50"
                x-cloak
                x-show="!enableSubmit"
              />
              <span x-text="enableSubmit ? '儲存' : '驗證中'"></span>
            </x-button>
          </div>
        </form>
      </div>
    </div>
    {{-- end modal --}}
  </div>
</div>

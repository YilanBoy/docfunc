@script
  <script>
    Alpine.data('register', () => ({
      submitIsEnabled: false,
      captchaSiteKey: @js(config('services.captcha.site_key')),
      submitIsDisabled() {
        return this.submitIsEnabled === false;
      },
      informationOnSubmitButton() {
        return this.submitIsEnabled ? '註冊' : '驗證中'
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
      }
    }));
  </script>
@endscript

<x-layouts.layout-auth x-data="register">
  <div class="fixed left-5 top-5">
    <a
      class="flex items-center text-2xl text-gray-400 transition duration-150 ease-in hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-50"
      href="{{ route('login') }}"
      wire:navigate
    >
      <x-icon.arrow-left-circle class="w-6" />
      <span class="ml-2">返回登入</span>
    </a>
  </div>

  <div class="container mx-auto">
    <div class="flex min-h-screen flex-col items-center justify-center px-4">
      {{-- 頁面標題 --}}
      <div class="flex items-center fill-current text-2xl text-gray-700 dark:text-gray-50">
        <x-icon.person-plus class="w-6" />
        <span class="ml-4">註冊</span>
      </div>

      <x-card class="mt-4 w-full space-y-6 overflow-hidden sm:max-w-md">

        {{-- 驗證錯誤訊息 --}}
        <x-auth-validation-errors :errors="$errors" />

        <form
          id="register"
          wire:submit="store"
        >
          {{-- 會員名稱 --}}
          <div>
            <x-floating-label-input
              name="name"
              type="text"
              value="{{ old('name') }}"
              :id="'name'"
              :placeholder="'會員名稱 (只能使用英文、數字、_ 或是 -)'"
              required
              autofocus
              wire:model="name"
            />
          </div>

          {{-- 信箱 --}}
          <div class="mt-6">
            <x-floating-label-input
              name="email"
              type="text"
              value="{{ old('email') }}"
              :id="'email'"
              :placeholder="'電子信箱'"
              required
              wire:model="email"
            />
          </div>

          {{-- 密碼 --}}
          <div class="mt-6">
            <x-floating-label-input
              name="password"
              type="password"
              :id="'password'"
              :placeholder="'密碼'"
              required
              wire:model="password"
            />
          </div>

          {{-- 確認密碼 --}}
          <div class="mt-6">
            <x-floating-label-input
              name="password_confirmation"
              type="password"
              :id="'password_confirmation'"
              :placeholder="'確認密碼'"
              required
              wire:model="password_confirmation"
            />
          </div>

          <div
            class="hidden"
            wire:ignore
            x-ref="turnstileBlock"
          ></div>

          <div class="mt-6 flex items-center justify-end">
            <a
              class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
              href="{{ route('login') }}"
              wire:navigate
            >
              {{ __('Already registered?') }}
            </a>

            <x-button
              class="ml-4"
              x-bind:disabled="submitIsDisabled"
            >
              <x-icon.animate-spin
                class="mr-2 h-5 w-5 text-gray-50"
                x-cloak
                x-show="submitIsDisabled"
              />
              <span x-text="informationOnSubmitButton"></span>
            </x-button>
          </div>
        </form>
      </x-card>
    </div>
  </div>
</x-layouts.layout-auth>

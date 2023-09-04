<div class="container mx-auto">
  <div class="fixed left-5 top-5">
    <a
      class="block text-2xl font-semibold text-gray-400 transition duration-150 ease-in hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-50"
      href="{{ route('root') }}"
      wire:navigate
    >
      <i class="bi bi-arrow-left-circle-fill"></i>
      <span class="ml-2">返回文章列表</span>
    </a>
  </div>
  {{-- google recaptcha --}}
  <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

  <div class="flex items-center justify-center px-4 xl:px-0">

    <div class="flex min-h-screen w-full flex-col items-center justify-center">
      {{-- 頁面標題 --}}
      <div class="fill-current text-2xl text-gray-700 dark:text-gray-50">
        <i class="bi bi-box-arrow-in-right"></i><span class="ml-4">登入</span>
      </div>

      {{-- 登入表單 --}}
      <x-card class="mt-4 w-full space-y-6 overflow-hidden sm:max-w-md">

        {{-- Session 狀態訊息 --}}
        <x-auth-session-status :status="session('status')" />

        {{-- 驗證錯誤訊息 --}}
        <x-auth-validation-errors :errors="$errors" />

        <form
          id="login"
          x-data="{
              recaptchaSiteKey: @js(config('services.recaptcha.site_key'))
          }"
          x-on:submit.prevent="
              grecaptcha.ready(function() {
                  grecaptcha.execute(recaptchaSiteKey, { action: 'submit' })
                      .then(function(response) {
                          // set livewire property 'recaptcha' value
                          $wire.set('recaptcha', response);

                          // submit the form and call the livewire method 'store'
                          $wire.store();
                      });
              });
          "
        >
          {{-- 信箱 --}}
          <div>
            <x-floating-label-input
              type="text"
              :id="'email'"
              :placeholder="'電子信箱'"
              wire:model="email"
              required
              autofocus
            />
          </div>

          {{-- 密碼 --}}
          <div class="mt-6">
            <x-floating-label-input
              type="password"
              :id="'password'"
              :placeholder="'密碼'"
              wire:model="password"
              required
            />
          </div>

          <div class="mt-6 flex justify-between">
            {{-- 記住我 --}}
            <label
              class="inline-flex items-center"
              for="remember-me"
            >
              <input
                class="form-checkbox rounded border-gray-300 text-indigo-400 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                id="remember-me"
                name="remember"
                type="checkbox"
                wire:model="remember"
              >
              <span class="ml-2 text-sm text-gray-600 dark:text-gray-50">{{ __('Remember me') }}</span>
            </label>

            <div>
              @if (Route::has('password.request'))
                <a
                  class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
                  href="{{ route('password.request') }}"
                  wire:navigate
                >
                  {{ __('Forgot your password?') }}
                </a>
              @endif

              <x-button class="ml-3">
                {{ __('Log in') }}
              </x-button>
            </div>
          </div>
        </form>
      </x-card>
    </div>

  </div>
</div>

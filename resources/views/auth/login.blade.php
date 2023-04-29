@section('title', '登入')

{{-- Google reCAPTCHA --}}
@push('script')
  <script>
    document.getElementById("login").addEventListener("submit", function(event) {
      event.preventDefault();
      grecaptcha.ready(function() {
        grecaptcha.execute("{{ config('services.recaptcha.site_key') }}", {
            action: "submit"
          })
          .then(function(response) {
            document.getElementById("g-recaptcha-response").value = response;
            document.getElementById("login").submit();
          });
      });
    });
  </script>
@endpush

<x-app-layout>
  <div class="container mx-auto max-w-7xl">
    <div class="flex items-center justify-center px-4 xl:px-0">

      <div class="flex w-full flex-col items-center justify-center">
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
            method="POST"
            action="{{ route('login') }}"
          >
            @csrf

            {{-- reCAPTCHA --}}
            <input
              type="hidden"
              class="g-recaptcha"
              name="g-recaptcha-response"
              id="g-recaptcha-response"
            >

            {{-- 信箱 --}}
            <div>
              <x-floating-label-input
                :type="'text'"
                :name="'email'"
                :placeholder="'電子信箱'"
                required
                autofocus
              >
              </x-floating-label-input>
            </div>

            {{-- 密碼 --}}
            <div class="mt-6">
              <x-floating-label-input
                :type="'password'"
                :name="'password'"
                :placeholder="'密碼'"
                required
              >
              </x-floating-label-input>
            </div>

            <div class="mt-6 flex justify-between">
              {{-- 記住我 --}}
              <label
                for="remember_me"
                class="inline-flex items-center"
              >
                <input
                  id="remember_me"
                  type="checkbox"
                  name="remember"
                  class="form-checkbox rounded border-gray-300 text-indigo-400 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-50">{{ __('Remember me') }}</span>
              </label>

              <div>
                @if (Route::has('password.request'))
                  <a
                    class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
                    href="{{ route('password.request') }}"
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
</x-app-layout>

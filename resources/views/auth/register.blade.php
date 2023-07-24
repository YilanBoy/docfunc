@section('title', '註冊')

{{-- Google reCAPTCHA --}}
@push('script')
  <script>
    document.getElementById("register").addEventListener("submit", function(event) {
      event.preventDefault();
      grecaptcha.ready(function() {
        grecaptcha.execute("{{ config('services.recaptcha.site_key') }}", {
            action: "submit"
          })
          .then(function(response) {
            document.getElementById("g-recaptcha-response").value = response;
            document.getElementById("register").submit();
          });
      });
    });
  </script>
@endpush

<x-app-layout>
  <div class="container mx-auto">
    <div class="flex items-center justify-center px-4 xl:px-0">

      <div class="flex w-full flex-col items-center justify-center">
        {{-- 頁面標題 --}}
        <div class="fill-current text-2xl text-gray-700 dark:text-gray-50">
          <i class="bi bi-person-plus-fill"></i><span class="ml-4">註冊</span>
        </div>

        <x-card class="mt-4 w-full space-y-6 overflow-hidden sm:max-w-md">

          {{-- 驗證錯誤訊息 --}}
          <x-auth-validation-errors :errors="$errors" />

          <form
            id="register"
            method="POST"
            action="{{ route('register') }}"
          >
            @csrf

            {{-- reCAPTCHA --}}
            <input
              class="g-recaptcha"
              id="g-recaptcha-response"
              name="g-recaptcha-response"
              type="hidden"
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
              />
            </div>

            <div class="mt-6 flex items-center justify-end">
              <a
                class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
                href="{{ route('login') }}"
              >
                {{ __('Already registered?') }}
              </a>

              <x-button class="ml-4">
                {{ __('Register') }}
              </x-button>
            </div>
          </form>
        </x-card>
      </div>

    </div>
  </div>
</x-app-layout>

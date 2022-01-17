@section('title', '註冊')

@section('scriptsInHead')
  {{-- Google reCAPTCHA --}}
  @if (app()->isProduction())
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  @endif
@endsection

<x-app-layout>
  <div class="container mx-auto max-w-7xl">
    <div class="flex items-center justify-center min-h-screen px-4 xl:px-0">

      <div class="flex flex-col items-center justify-center w-full">
        {{-- 頁面標題 --}}
        <div class="text-2xl text-gray-700 fill-current dark:text-gray-50">
          <i class="bi bi-person-plus-fill"></i><span class="ml-4">註冊</span>
        </div>

        <x-card class="w-full mt-4 overflow-hidden sm:max-w-md">

          {{-- 驗證錯誤訊息 --}}
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- 會員名稱 --}}
            <div class="mt-5">
              <x-floating-label-input
                :type="'text'"
                :name="'name'"
                :placeholder="'會員名稱'"
                :value="old('name')"
                required
                autofocus
              ></x-floating-label-input>
            </div>

            {{-- 信箱 --}}
            <div class="mt-5">
              <x-floating-label-input
                :type="'text'"
                :name="'email'"
                :placeholder="'電子信箱'"
                :value="old('email')"
                required
              ></x-floating-label-input>
            </div>

            {{-- 密碼 --}}
            <div class="mt-5">
              <x-floating-label-input
                :type="'password'"
                :name="'password'"
                :placeholder="'密碼'"
                required
              >
              </x-floating-label-input>
            </div>

            {{-- 確認密碼 --}}
            <div class="mt-5">
              <x-floating-label-input
                :type="'password'"
                :name="'password_confirmation'"
                :placeholder="'確認密碼'"
                required
              >
              </x-floating-label-input>
            </div>

            {{-- reCAPTCHA --}}
            @if (app()->isProduction())
              <x-recaptcha />
            @endif

            <div class="flex items-center justify-end mt-5">
              <a
                href="{{ route('login') }}"
                class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
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

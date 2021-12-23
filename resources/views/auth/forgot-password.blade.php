@section('title', '忘記密碼')

<x-app-layout>
  <div class="container mx-auto max-w-7xl">
    <div class="flex items-center justify-center min-h-screen px-4 xl:px-0">

      <div class="flex flex-col items-center justify-center w-full">
        {{-- 頁面標題 --}}
        <div class="text-2xl text-gray-700 fill-current dark:text-gray-50">
          <i class="bi bi-question-circle"></i><span class="ml-4">忘記密碼</span>
        </div>

        <x-card class="w-full mt-4 overflow-hidden sm:max-w-md">
          <div class="mb-4 text-gray-600 dark:text-gray-50">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
          </div>

          {{-- Session 狀態訊息 --}}
          <x-auth-session-status class="mb-4" :status="session('status')" />

          {{-- 驗證錯誤訊息 --}}
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- 信箱 --}}
            <div class="mt-10">
              <x-floating-label-input :type="'text'" :name="'email'" :placeholder="'電子信箱'" required autofocus>
              </x-floating-label-input>
            </div>

            <div class="flex items-center justify-end mt-4">
              <x-button>
                {{ __('Email Password Reset Link') }}
              </x-button>
            </div>
          </form>
        </x-card>
      </div>

    </div>
  </div>
</x-app-layout>

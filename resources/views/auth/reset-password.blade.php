@section('title', '重設密碼')

<x-app-layout>
  <div class="container mx-auto">
    <div class="flex items-center justify-center px-4 xl:px-0">

      <div class="flex w-full flex-col items-center justify-center">
        {{-- 頁面標題 --}}
        <div class="fill-current text-2xl text-gray-700 dark:text-gray-50">
          <i class="bi bi-question-circle"></i><span class="ml-4">重設密碼</span>
        </div>

        <x-card class="mt-4 w-full space-y-6 overflow-hidden sm:max-w-md">
          {{-- 驗證錯誤訊息 --}}
          <x-auth-validation-errors :errors="$errors" />

          <form
            method="POST"
            action="{{ route('password.update') }}"
          >
            @csrf

            {{-- 更改密碼 Token --}}
            <input
              name="token"
              type="hidden"
              value="{{ $request->route('token') }}"
            >

            {{-- 信箱 --}}
            <div>
              <x-floating-label-input
                name="email"
                type="text"
                value="{{ $request->email ?? old('email') }}"
                :id="'email'"
                :placeholder="'電子信箱'"
                required
                readonly
              />
            </div>

            {{-- 密碼 --}}
            <div class="mt-6">
              <x-floating-label-input
                name="password"
                type="password"
                :id="'password'"
                :placeholder="'新密碼'"
                required
                autofocus
              />
            </div>

            {{-- 確認密碼 --}}
            <div class="mt-6">
              <x-floating-label-input
                name="password_confirmation"
                type="password"
                :id="'password_confirmation'"
                :placeholder="'確認新密碼'"
                required
              />
            </div>

            <div class="mt-6 flex items-center justify-end">
              <x-button>
                {{ __('Reset Password') }}
              </x-button>
            </div>
          </form>
        </x-card>
      </div>

    </div>
  </div>
</x-app-layout>

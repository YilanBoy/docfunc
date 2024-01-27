<x-layouts.layout-auth>
  <div class="container mx-auto">
    <div class="flex min-h-screen flex-col items-center justify-center px-4">
      {{-- 頁面標題 --}}
      <div class="flex items-center fill-current text-2xl text-gray-700 dark:text-gray-50">
        <x-icon.question-circle class="w-6" />
        <span class="ml-4">重設密碼</span>
      </div>

      <x-card class="mt-4 w-full space-y-6 overflow-hidden sm:max-w-md">
        {{-- 驗證錯誤訊息 --}}
        <x-auth-validation-errors :errors="$errors" />

        <form wire:submit="store">
          {{-- 信箱 --}}
          <div>
            <x-floating-label-input
              name="email"
              type="text"
              :id="'email'"
              :placeholder="'電子信箱'"
              required
              readonly
              wire:model="email"
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
              wire:model="password"
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
              wire:model="password_confirmation"
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
</x-layouts.layout-auth>

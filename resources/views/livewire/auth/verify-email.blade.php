<div class="container mx-auto">
  <div class="flex items-center justify-center px-4 xl:px-0">

    <div class="flex w-full flex-col items-center justify-center">
      {{-- 頁面標題 --}}
      <div class="fill-current text-2xl text-gray-700 dark:text-gray-50">
        <i class="bi bi-person-check-fill"></i><span class="ml-4">驗證 Email</span>
      </div>

      <x-card class="mt-4 w-full overflow-hidden sm:max-w-md">
        <div class="mb-4 text-gray-600 dark:text-gray-50">
          {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
          <div class="mb-4 font-medium text-emerald-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
          </div>
        @endif

        <div class="mt-4 flex items-center justify-between">

          <x-button
            type="button"
            wire:click="resendVerificationEmail"
          >
            {{ __('Resend Verification Email') }}
          </x-button>

          <button
            class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
            type="button"
            wire:click="$dispatch('logout')"
          >
            {{ __('Log Out') }}
          </button>
        </div>
      </x-card>
    </div>

  </div>
</div>

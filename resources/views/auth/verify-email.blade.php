@section('title', '驗證信箱')

<x-app-layout>
  <div class="container mx-auto max-w-7xl">
    <div class="flex items-center justify-center min-h-screen px-4 xl:px-0">

      <div class="flex flex-col items-center justify-center w-full">
        {{-- 頁面標題 --}}
        <div class="text-2xl text-gray-700 fill-current dark:text-gray-50">
          <i class="bi bi-person-check-fill"></i><span class="ml-4">驗證 Email</span>
        </div>

        <x-card class="w-full mt-4 overflow-hidden sm:max-w-md">
          <div class="mb-4 text-gray-600 dark:text-gray-50">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
          </div>

          @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-emerald-600">
              {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
          @endif

          <div class="flex items-center justify-between mt-4">
            <form method="POST" action="{{ route('verification.send') }}">
              @csrf

              <div>
                <x-button>
                  {{ __('Resend Verification Email') }}
                </x-button>
              </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
              @csrf

              <button type="submit" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50">
                {{ __('Log Out') }}
              </button>
            </form>
          </div>
        </x-card>
      </div>

    </div>
  </div>
</x-app-layout>

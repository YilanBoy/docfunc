@section('title', '重設密碼')

<x-app-layout>
    <div class="container mx-auto max-w-7xl">
        <div class="min-h-screen flex justify-center items-center px-4 xl:px-0">

            <div class="w-full flex flex-col justify-center items-center">

                {{-- 頁面標題 --}}
                <div class="fill-current text-gray-700 text-2xl dark:text-gray-50">
                    <i class="bi bi-question-circle"></i><span class="ml-4">重設密碼</span>
                </div>

                <x-card class="w-full sm:max-w-md mt-4 overflow-hidden">
                    {{-- 驗證錯誤訊息 --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        {{-- 更改密碼 Token --}}
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        {{-- 信箱 --}}
                        <div class="mt-5">
                            <x-floating-label-input
                                :type="'text'"
                                :name="'email'"
                                :placeholder="'電子信箱'"
                                :value="$request->email ?? old('email')"
                                required
                                readonly
                            ></x-floating-label-input>
                        </div>

                        {{-- 密碼 --}}
                        <div class="mt-10">
                            <x-floating-label-input
                                :type="'password'"
                                :name="'password'"
                                :placeholder="'新密碼'"
                                required
                                autofocus
                            ></x-floating-label-input>
                        </div>

                        {{-- 確認密碼 --}}
                        <div class="mt-10">
                            <x-floating-label-input
                                :type="'password'"
                                :name="'password_confirmation'"
                                :placeholder="'確認新密碼'"
                                required
                            ></x-floating-label-input>
                        </div>


                        <div class="flex items-center justify-end mt-4">
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

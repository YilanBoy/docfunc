@extends('users.index')

@section('title', '會員中心-編輯個人資料')

@section('users.content')

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Validation Errors --}}
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <form method="POST" action="{{ route('users.updatePassword', ['user' => $user->id]) }}" class="w-full">
        @method('PUT')
        @csrf

        {{-- Current Password --}}
        <div class="mt-5">
            <x-floating-label-input
                :type="'password'"
                :name="'current_password'"
                :placeholder="'舊密碼'"
                required
                autofocus
            ></x-floating-label-input>
        </div>

        {{-- New Password --}}
        <div class="mt-10">
            <x-floating-label-input
                :type="'password'"
                :name="'new_password'"
                :placeholder="'新密碼'"
                required
            ></x-floating-label-input>
        </div>

        {{-- Confirm New Password --}}
        <div class="mt-10">
            <x-floating-label-input
                :type="'password'"
                :name="'new_password_confirmation'"
                :placeholder="'確認新密碼'"
                required
            ></x-floating-label-input>
        </div>

        <div class="flex items-center justify-end mt-4">
            {{-- Save Button --}}
            <x-button>
                <i class="bi bi-save2-fill"></i><span class="ml-2">修改密碼</span>
            </x-button>
        </div>
    </form>
@endsection

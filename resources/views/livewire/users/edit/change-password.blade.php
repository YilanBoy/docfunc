@section('title', '會員中心-修改密碼')

<x-card class="flex flex-col justify-center w-full mt-6 md:w-1/2 md:mt-0 space-y-6">
  {{-- 驗證錯誤訊息 --}}
  <x-auth-validation-errors :errors="$errors"/>

  <form wire:submit.prevent="update" class="w-full">
    {{-- 舊密碼 --}}
    <div>
      <x-floating-label-input
        wire:model.lazy="current_password"
        :type="'password'"
        :name="'current_password'"
        :placeholder="'舊密碼'"
        required
        autofocus
      ></x-floating-label-input>
    </div>

    {{-- 新密碼 --}}
    <div class="mt-6">
      <x-floating-label-input
        wire:model.lazy="new_password"
        :type="'password'"
        :name="'new_password'"
        :placeholder="'新密碼'"
        required
      ></x-floating-label-input>
    </div>

    {{-- 確認新密碼 --}}
    <div class="mt-6">
      <x-floating-label-input
        wire:model.lazy="new_password_confirmation"
        :type="'password'"
        :name="'new_password_confirmation'"
        :placeholder="'確認新密碼'"
        required
      ></x-floating-label-input>
    </div>

    <div class="flex items-center justify-end mt-6">
      {{-- 儲存按鈕 --}}
      <x-button>
        <i class="bi bi-save2-fill"></i><span class="ml-2">修改密碼</span>
      </x-button>
    </div>
  </form>
</x-card>

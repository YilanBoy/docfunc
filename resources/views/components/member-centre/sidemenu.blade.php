{{-- user edit side men --}}
<x-card class="flex w-full flex-col items-center justify-center dark:text-gray-50 md:mr-6 md:w-60 xl:w-80">
  <h3
    class="mb-3 flex w-full items-center justify-center border-b-2 border-black pb-3 text-center text-lg font-semibold dark:border-white"
  >
    <x-icon.person-circle class="w-6" />
    <span class="ml-2">會員中心</span>
  </h3>

  <div class="flex w-full flex-col space-y-1">
    @php
      $editUserUrl = urldecode(route('users.edit', ['user' => auth()->id()]));
      $inEditUserPage = urldecode(request()->url()) === $editUserUrl;
    @endphp
    <a
      href="{{ $editUserUrl }}"
      @class([
          'flex items-center rounded-md p-2 dark:text-gray-50',
          'bg-gray-200 dark:bg-gray-700' => $inEditUserPage,
          'hover:bg-gray-200 dark:hover:bg-gray-700' => !$inEditUserPage,
      ])
      wire:navigate
    >
      <x-icon.person-lines class="w-5" />
      <span class="ml-2">編輯個人資料</span>
    </a>

    @php
      $changePasswordUrl = urldecode(route('users.updatePassword', ['user' => auth()->id()]));
      $inChangePasswordPage = urldecode(request()->url()) === $changePasswordUrl;
    @endphp
    <a
      href="{{ $changePasswordUrl }}"
      @class([
          'flex items-center rounded-md p-2 dark:text-gray-50',
          'bg-gray-200 dark:bg-gray-700' => $inChangePasswordPage,
          'hover:bg-gray-200 dark:hover:bg-gray-700' => !$inChangePasswordPage,
      ])
      wire:navigate
    >
      <x-icon.file-earmark-lock class="w-5" />
      <span class="ml-2">修改密碼</span>
    </a>

    @php
      $destroyUserUrl = urldecode(route('users.destroy', ['user' => auth()->id()]));
      $inDestroyUserPage = urldecode(request()->url()) === $destroyUserUrl;
    @endphp
    <a
      href="{{ $destroyUserUrl }}"
      @class([
          'flex items-center rounded-md p-2 dark:text-gray-50',
          'bg-gray-200 dark:bg-gray-700' => $inDestroyUserPage,
          'hover:bg-gray-200 dark:hover:bg-gray-700' => !$inDestroyUserPage,
      ])
      wire:navigate
    >
      <x-icon.person-x class="w-5" />
      <span class="ml-2">刪除帳號</span>
    </a>
  </div>
</x-card>

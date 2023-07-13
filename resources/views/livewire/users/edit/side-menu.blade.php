{{-- user edit side men --}}
<x-card class="flex w-full flex-col items-center justify-center dark:text-gray-50 md:mr-6 md:w-60 xl:w-80">
  <h3 class="mb-3 w-full border-b-2 border-black pb-3 text-center text-lg font-semibold dark:border-white">
    <i class="bi bi-person-circle"></i><span class="ml-2">會員中心</span>
  </h3>

  <div class="flex w-full flex-col space-y-1">
    @php
      $editUserUrl = route('users.edit', ['user' => auth()->id()]);
      $inEditUserPage = request()->url() === $editUserUrl;
    @endphp
    <a
      href="{{ $editUserUrl }}"
      @class([
          'block rounded-md p-2 dark:text-gray-50',
          'bg-gray-200 dark:bg-gray-700' => $inEditUserPage,
          'hover:bg-gray-200 dark:hover:bg-gray-700' => !$inEditUserPage,
      ])
    >
      <i class="bi bi-person-lines-fill"></i><span class="ml-2">編輯個人資料</span>
    </a>

    @php
      $changePasswordUrl = route('users.changePassword', ['user' => auth()->id()]);
      $inChangePasswordPage = request()->url() === $changePasswordUrl;
    @endphp
    <a
      href="{{ $changePasswordUrl }}"
      @class([
          'block rounded-md p-2 dark:text-gray-50',
          'bg-gray-200 dark:bg-gray-700' => $inChangePasswordPage,
          'hover:bg-gray-200 dark:hover:bg-gray-700' => !$inChangePasswordPage,
      ])
    >
      <i class="bi bi-file-earmark-lock-fill"></i><span class="ml-2">修改密碼</span>
    </a>

    @php
      $deleteUserUrl = route('users.delete', ['user' => auth()->id()]);
      $inDeleteUserPage = request()->url() === $deleteUserUrl;
    @endphp
    <a
      href="{{ $deleteUserUrl }}"
      @class([
          'block rounded-md p-2 dark:text-gray-50',
          'bg-gray-200 dark:bg-gray-700' => $inDeleteUserPage,
          'hover:bg-gray-200 dark:hover:bg-gray-700' => !$inDeleteUserPage,
      ])
    >
      <i class="bi bi-person-x-fill"></i><span class="ml-2">刪除帳號</span>
    </a>
  </div>
</x-card>

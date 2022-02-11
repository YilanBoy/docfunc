{{-- 會員中心 --}}
<x-app-layout>
  <div class="container flex-1 mx-auto max-w-7xl">
    <div class="flex flex-col items-start justify-center px-4 mt-6 md:flex-row xl:px-0">

      {{-- 選項 --}}
      <x-card class="flex flex-col items-center justify-center w-full md:w-60 xl:w-80 md:mr-6 dark:text-gray-50">
        <h3 class="w-full pb-3 mb-3 text-lg font-semibold text-center border-b-2 border-black dark:border-white">
          <i class="bi bi-person-circle"></i><span class="ml-2">會員中心</span>
        </h3>

        <div class="flex flex-col w-full space-y-1">
          <a
            href="{{ route('users.edit', ['user' => auth()->id()]) }}"
            @class([
              'block rounded-md p-2 dark:text-gray-50',
              'bg-gray-200 dark:bg-gray-600' => request()->url() === route('users.edit', ['user' => auth()->id()]),
              'hover:bg-gray-200 dark:hover:bg-gray-600' => request()->url() !== route('users.edit', ['user' => auth()->id()]),
            ])
          >
            <i class="bi bi-person-lines-fill"></i><span class="ml-2">編輯個人資料</span>
          </a>

          <a
            href="{{ route('users.changePassword', ['user' => auth()->id()]) }}"
            @class([
              'block rounded-md p-2 dark:text-gray-50',
              'bg-gray-200 dark:bg-gray-600' => request()->url() === route('users.changePassword', ['user' => auth()->id()]),
              'hover:bg-gray-200 dark:hover:bg-gray-600' => request()->url() !== route('users.changePassword', ['user' => auth()->id()]),
            ])
          >
            <i class="bi bi-file-earmark-lock-fill"></i><span class="ml-2">修改密碼</span>
          </a>

          <a
            href="{{ route('users.delete', ['user' => auth()->id()]) }}"
            @class([
              'block rounded-md p-2 dark:text-gray-50',
              'bg-gray-200 dark:bg-gray-600' => request()->url() === route('users.delete', ['user' => auth()->id()]),
              'hover:bg-gray-200 dark:hover:bg-gray-600' => request()->url() !== route('users.delete', ['user' => auth()->id()]),
            ])
          >
            <i class="bi bi-person-x-fill"></i><span class="ml-2">刪除帳號</span>
          </a>
        </div>
      </x-card>

      {{-- 選項表單 --}}
      <x-card class="flex flex-col justify-center w-full mt-6 md:w-1/2 md:mt-0">
        @yield('users.content')
      </x-card>

    </div>
  </div>
</x-app-layout>

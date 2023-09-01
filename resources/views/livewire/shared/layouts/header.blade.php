{{-- Header --}}
<nav
  class="z-20 mb-6"
  id="header"
>
  <livewire:shared.layouts.mobile-header-menu
    :categories="$categories"
    :show-register-button="$showRegisterButton"
  />

  <livewire:shared.layouts.desktop-header-menu
    :categories="$categories"
    :show-register-button="$showRegisterButton"
  />
</nav>

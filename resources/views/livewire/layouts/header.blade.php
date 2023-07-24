{{-- Header --}}
<nav
  class="z-20 mb-6"
  id="header"
>
  <livewire:layouts.mobile-menu
    :categories="$categories"
    :show-register-button="$showRegisterButton"
  />

  <livewire:layouts.desktop-menu
    :categories="$categories"
    :show-register-button="$showRegisterButton"
  />
</nav>

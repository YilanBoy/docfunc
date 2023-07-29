{{-- Header --}}
<nav
  class="z-20 mb-6"
  id="header"
>
  <x-mobile-menu
    :categories="$categories"
    :show-register-button="$showRegisterButton"
  />

  <x-desktop-menu
    :categories="$categories"
    :show-register-button="$showRegisterButton"
  />
</nav>

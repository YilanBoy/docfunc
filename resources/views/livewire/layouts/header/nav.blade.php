{{-- Header --}}
<nav
  id="header"
  class="z-20 mb-6"
>
  <livewire:layouts.header.mobile-menu :categories="$categories" :show-register-button="$showRegisterButton"/>

  <livewire:layouts.header.desktop-menu :categories="$categories" :show-register-button="$showRegisterButton"/>
</nav>

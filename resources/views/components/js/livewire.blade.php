<script>
  document.addEventListener('livewire:init', () => {
    // Runs after Livewire is loaded but before it's initialized
    // on the page...

    Livewire.directive('confirm', ({
      el,
      directive,
      component,
      cleanup
    }) => {
      let content = directive.expression

      // The "directive" object gives you access to the parsed directive.
      // For example, here are its values for: wire:click.prevent="deletePost(1)"
      //
      // directive.raw = wire:click.prevent
      // directive.value = "click"
      // directive.modifiers = ['prevent']
      // directive.expression = "deletePost(1)"

      let onClick = e => {
        if (!confirm(content)) {
          e.preventDefault()
          e.stopPropagation()
        }
      }

      el.addEventListener('click', onClick, {
        capture: true
      })

      // Register any cleanup code inside `cleanup()` in the case
      // where a Livewire component is removed from the DOM while
      // the page is still active.
      cleanup(() => {
        el.removeEventListener('click', onClick)
      })
    })
  });

  document.addEventListener('livewire:initialized', () => {
    // Runs immediately after Livewire has finished initializing
    // on the page...
  });

  document.addEventListener('livewire:navigated', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    })
  });
</script>

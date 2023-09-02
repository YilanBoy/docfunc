<script>
  document.addEventListener("livewire:init", () => {
    // Runs after Livewire is loaded but before it's initialized
    // on the page...

    Livewire.directive("confirm", ({
      el,
      directive,
      component,
      cleanup
    }) => {
      let content = directive.expression;

      // The "directive" object gives you access to the parsed directive.
      // For example, here are its values for: wire:click.prevent="deletePost(1)"
      //
      // directive.raw = wire:click.prevent
      // directive.value = "click"
      // directive.modifiers = ['prevent']
      // directive.expression = "deletePost(1)"

      let onClick = e => {
        if (!confirm(content)) {
          e.preventDefault();
          e.stopPropagation();
        }
      };

      el.addEventListener("click", onClick, {
        capture: true
      });

      // Register any cleanup code inside `cleanup()` in the case
      // where a Livewire component is removed from the DOM while
      // the page is still active.
      cleanup(() => {
        el.removeEventListener("click", onClick);
      });
    });

    Livewire.hook("commit", ({
      component,
      commit,
      respond,
      succeed,
      fail
    }) => {
      // Runs immediately before a commit's payload is sent to the server...
      let currentScrollY = window.scrollY;

      succeed(() => {
        // Equivalent of 'message.processed'
        queueMicrotask(() => {
          // when show more comments, stick the current scrollY
          if (component.name === "shared.comments.comments") {
            window.scrollTo({
              top: currentScrollY,
              behavior: "instant"
            });
          }

          // when create comment in show-post-page
          if (component.name === "shared.comments.create-comment-modal") {
            document.querySelectorAll("#create-comment-preview pre code:not(.hljs)").forEach((
              element) => {
              hljs.highlightElement(element);
            });
          }

          // when edit comment in show-post-page
          if (component.name === "shared.comments.edit-comment-modal") {
            document.querySelectorAll("#edit-comment-preview pre code:not(.hljs)").forEach((
              element) => {
              hljs.highlightElement(element);
            });
          }

          // when creat and change comment in show-post-page
          if ([
              "shared.comments.comment-group",
              "shared.comments.comment-card"
            ].includes(component.name)) {
            document.querySelectorAll(".comment-body pre code:not(.hljs)").forEach((element) => {
              if (element instanceof Element) {
                hljs.highlightElement(element);
              }
            });

            codeBlockCopyButton(document.querySelector("#comments"));
          }

          // when change the comments page in users-information-page
          if (component.name === "shared.users.comments") {
            document.querySelectorAll(".comment-body pre code:not(.hljs)").forEach((
              element) => {
              hljs.highlightElement(element);
            });
          }
        });
      });
    });
  });

  document.addEventListener("livewire:navigated", () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  });
</script>

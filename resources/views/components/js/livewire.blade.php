<script>
  document.addEventListener("livewire:init", () => {
    // Runs after Livewire is loaded but before it's initialized
    // on the page...
    let currentScrollY = 0;

    Livewire.hook('commit.prepare', ({
      component,
      commit
    }) => {
      // Runs before commit payloads are collected and sent to the server...
      currentScrollY = window.scrollY;
    })

    Livewire.hook("morph.updated", ({
      el,
      component
    }) => {
      // when show more comments, stick the current scrollY
      if (component.name === "shared.comments.comments") {
        window.scrollTo({
          top: currentScrollY,
          behavior: "instant"
        });
      }

      // when create comment in show-post-page
      if (component.name === "shared.comments.create-comment-modal") {
        document.querySelectorAll("#create-comment-preview pre code:not(.hljs)")
          .forEach((element) => {
            hljs.highlightElement(element);
          });
      }

      // when edit comment in show-post-page
      if (component.name === "shared.comments.edit-comment-modal") {
        document.querySelectorAll("#edit-comment-preview pre code:not(.hljs)")
          .forEach((element) => {
            hljs.highlightElement(element);
          });
      }

      // when creat and change comment in show-post-page
      if ([
          "shared.comments.comment-group",
          "shared.comments.comment-card"
        ].includes(component.name)) {
        document.querySelectorAll(".comment-body pre code:not(.hljs)")
          .forEach((element) => {
            if (element instanceof Element) {
              hljs.highlightElement(element);
            }
          });

        codeBlockCopyButton(document.querySelector("#comments"));
      }

      // when change the comments page in users-information-page
      if (component.name === "shared.users.comments") {
        document.querySelectorAll(".comment-body pre code:not(.hljs)")
          .forEach((element) => {
            hljs.highlightElement(element);
          });
      }
    });
  });
</script>

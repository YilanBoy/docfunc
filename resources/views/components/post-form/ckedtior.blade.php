<div
  class="col-span-2 max-w-none"
  x-data="{
      csrf_token: @js(csrf_token()),
      maxCharacters: 20000,
      editorDebounceTimer: null,
      debounce(callback, time) {
          // https://webdesign.tutsplus.com/tutorials/javascript-debounce-and-throttle--cms-36783
          window.clearTimeout(this.editorDebounceTimer);
          this.editorDebounceTimer = window.setTimeout(callback, time);
      },
      imageUploadUrl: @js(route('images.store')),
      body: @entangle($model).live
  }"
  x-init="// init the create post page
  ClassicEditor.create($refs.editor, {
          placeholder: '分享使自己成長～',
          // Editor configuration.
          wordCount: {
              onUpdate: (stats) => {
                  let characterCounter = document.querySelectorAll('.character-counter');
                  // The character count has exceeded the maximum limit
                  let isLimitExceeded = stats.characters > maxCharacters;
                  // The character count is approaching the maximum limit
                  let isCloseToLimit = !isLimitExceeded && stats.characters > maxCharacters * 0.8;

                  // update character count in HTML element
                  characterCounter.forEach((element) => {
                      element.textContent = `${stats.characters} / ${maxCharacters}`;
                      // If the character count is approaching the limit
                      // add the class 'text-yellow-500' to the 'wordsBox' element to turn the text yellow
                      element.classList.toggle('text-yellow-500', isCloseToLimit);
                      // If the character count exceeds the limit
                      // add the class 'text-red-400' to the 'wordsBox' element to turn the text red
                      element.classList.toggle('text-red-400', isLimitExceeded);
                  });
              }
          },
          simpleUpload: {
              // The URL that the images are uploaded to.
              uploadUrl: imageUploadUrl,

              // laravel sanctum need csrf token to authenticate
              headers: {
                  'X-CSRF-TOKEN': csrf_token
              }
          }
      })
      .then((editor) => {
          // set the default value of the editor
          editor.setData(body);
          // binding the value of the ckeditor to the livewire attribute 'body'
          editor.model.document.on('change:data', () => {
              debounce(() => {
                  body = editor.getData();
              }, 500);
          });

          document.addEventListener('livewire:navigating', () => {
              if (editor !== null) {
                  console.log('destroy ckeditor before navigating away');
                  editor.destroy().catch((error) => {
                      console.log(error);
                  });
                  editor = null;
              }
          });
      })
      .catch((err) => {
          console.error(err.stack);
      });"
  wire:ignore
>
  <div x-ref="editor"></div>
</div>

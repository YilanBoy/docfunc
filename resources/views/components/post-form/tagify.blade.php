<div
  class="col-span-2"
  wire:ignore
  x-data="{
      tagsListUrl: '/api/tags',
      tags: @entangle('form.tags').live
  }"
  x-init="// because the editor will be cached by livewire in navigation
  fetch(tagsListUrl)
      .then((response) => response.json())
      .then(function(tagsJson) {
          return new Tagify($refs.tags, {
              whitelist: tagsJson.data,
              enforceWhitelist: true,
              maxTags: 5,
              dropdown: {
                  // show the dropdown immediately on focus
                  enabled: 0,
                  maxItems: 5,
                  // place the dropdown near the typed text
                  position: 'text',
                  // keep the dropdown open after selecting a suggestion
                  closeOnSelect: false,
                  highlightFirst: true
              },
              callbacks: {
                  // binding the value of the tag input to the livewire attribute 'tags'
                  'change': (event) => tags = event.detail.value
              }
          });
      })
      .then(function(tagify) {
          try {
              tagify.addTags(JSON.parse(tags));
          } catch (e) {
              // forget about it :)
          }

          document.addEventListener('livewire:navigating', () => {
              if (tagify !== null) {
                  console.log('destroy tagify before navigating away');
                  tagify.destroy();
                  tagify = null;
              }
          });
      });"
>
  <label
    class="hidden"
    for="tags"
  >標籤 (最多 5 個)</label>

  <input
    class="w-full rounded-md bg-white dark:bg-gray-700"
    id="tags"
    type="text"
    placeholder="標籤 (最多 5 個)"
    x-ref="tags"
  >
</div>

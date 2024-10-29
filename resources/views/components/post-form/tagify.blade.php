@props(['model'])

@assets
  {{-- custom tagify style --}}
  <style>
    .tagify-custom-look {
      --tag-border-radius: 6px;
      align-items: center;
      --tag-inset-shadow-size: 3rem;
    }

    .dark .tagify-custom-look {
      --tag-bg: #52525b;
      --tag-hover: #71717a;
      --tag-text-color: #f9fafb;
      --tag-remove-btn-color: #f9fafb;
      --tag-text-color--edit: #f9fafb;
    }

    :root.dark {
      --tagify-dd-bg-color: #52525b;
      --tagify-dd-color-primary: #71717a;
      --tagify-dd-text-color: #f9fafb;
    }
  </style>
@endassets

@script
  <script>
    Alpine.data('tagify', () => ({
      tagsListUrl: @js(route('api.tags')),
      tags: @entangle($model).live,
      async init() {
        let response = await fetch(this.tagsListUrl);
        let tagsJson = await response.json();

        let tagify = new Tagify(this.$refs.tags, {
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
            'change': (event) => {
              this.tags = event.detail.value
            }
          }
        });

        try {
          tagify.addTags(JSON.parse(this.tags));
        } catch (error) {
          console.log('There is an error to init the tags:', error)
        }

        const removeTagify = () => {
          tagify.destroy();
          document.removeEventListener('livewire:navigating', removeTagify);
        };

        document.addEventListener('livewire:navigating', removeTagify);
      }
    }));
  </script>
@endscript

<div
  class="col-span-2"
  wire:ignore
  x-data="tagify"
>
  <label
    class="hidden"
    for="tags"
  >標籤 (最多 5 個)</label>

  <input
    class="tagify-custom-look w-full rounded-md border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-700"
    id="tags"
    type="text"
    placeholder="標籤 (最多 5 個)"
    x-ref="tags"
  >
</div>

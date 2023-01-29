@section('title', '新增文章')

@push('css')
  @vite([
    'resources/css/editor.css',
    'node_modules/@yaireo/tagify/dist/tagify.css',
    'resources/css/missing-content-style.css',
  ])
@endpush

@push('script')
  {{-- Ckeditor --}}
  <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
  @vite('resources/js/editor.js')
  {{-- Tagify --}}
  @vite('resources/ts/tagify.ts')
@endpush

{{-- create new post --}}
<div class="container mx-auto max-w-7xl">
  <livewire:posts.create-form></livewire:posts.create-form>
</div>

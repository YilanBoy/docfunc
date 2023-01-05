@section('title', '編輯文章')

@section('css')
  @vite([
    'resources/css/editor.css',
    'node_modules/@yaireo/tagify/dist/tagify.css',
    'resources/css/missing-content-style.css',
  ])
@endsection

@push('script')
  {{-- Ckeditor --}}
  <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
  @vite('resources/js/editor.js')
  {{-- Tagify --}}
  @vite('resources/ts/tagify.ts')
@endpush

{{-- edit post --}}
<div class="container mx-auto max-w-7xl">
  <livewire:posts.edit-form :postId="$id"></livewire:posts.edit-form>
</div>

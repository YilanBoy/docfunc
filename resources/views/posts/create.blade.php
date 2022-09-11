@section('title', '新增文章')

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

{{-- create new post --}}
<x-app-layout>
  <div class="container mx-auto max-w-7xl">
    <livewire:posts.create-form></livewire:posts.create-form>
  </div>
</x-app-layout>

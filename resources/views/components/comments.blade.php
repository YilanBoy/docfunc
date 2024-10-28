@props(['postId', 'postAuthorId', 'commentCounts', 'maxLayer'])

<div
  class="w-full"
  id="comments"
>
  {{-- comment box --}}
  <livewire:shared.comments.reply
    :$postId
    :$commentCounts
  />

  {{-- new comment will show here --}}
  <livewire:shared.comments.comment-group
    :$maxLayer
    :$postId
    :$postAuthorId
    {{-- if it's root comment, the comment group id is 'root-group' --}}
    :comment-group-name="'root-new-group'"
  />

  {{-- comments list --}}
  <livewire:shared.comments.comment-list
    :$maxLayer
    :$postId
    :$postAuthorId
  />

  {{-- create comment modal --}}
  <livewire:shared.comments.create-comment-modal :$postId />

  {{-- edit comment modal --}}
  <livewire:shared.comments.edit-comment-modal />
</div>

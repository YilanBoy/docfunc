<div class="mt-6 space-y-6">
  @forelse ($comments as $comment)
    {{-- 第一階層留言 --}}
    <x-card
      x-data="{
        commentId: {{ $comment->id }},
      }"
      class="relative flex group"
    >
      <div class="flex flex-col flex-1 md:flex-row">
        {{-- 大頭貼 --}}
        <div class="flex-none">
          <a href="{{ route('users.index', ['user' => $comment->user_id]) }}">
            <img
              src="{{ $comment->gravatar_url }}"
              alt="{{ $comment->user_name }}"
              class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400"
            >
          </a>
        </div>

        {{-- 留言 --}}
        <div class="w-full md:mx-4">
          <p class="mt-3 text-gray-600 whitespace-pre-wrap sm:mt-0 dark:text-gray-50">{{ $comment->content }}</p>

          <div class="flex items-center justify-between mt-3">
            <div class="flex items-center space-x-2 text-sm text-gray-400">
              <div>{{ $comment->user_name }}</div>
              <div>&bull;</div>
              <div>{{ $comment->created_at->diffForHumans() }}</div>
            </div>
          </div>
        </div>

        {{-- 新增第二階層留言與刪除留言 --}}
        @auth
          <div
            class="flex items-center justify-start mt-2 space-x-2 transition duration-150 opacity-0 md:mt-0 group-hover:opacity-100">
            <button
              x-on:click="
                $dispatch('set-comment-box-open', { open: true })
                $dispatch('set-comment-id', {{ $comment->id }})
                $dispatch('set-comment-to', '回覆 ➡ {{ $comment->user_name }}')
                $dispatch('comment-box-focus')
              "
              class="inline-flex items-center justify-center w-10 h-10 font-semibold transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md text-gray-50 hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300"
            >
              <i class="bi bi-chat-left-text-fill"></i>
            </button>

            @if (in_array(auth()->id(), [$comment->user_id, $comment->post_user_id]))
              <button
                x-on:click="
                  if (confirm('您確定要刪除此留言嗎？')) {
                    $wire.destroy(commentId)
                  }
                "
                class="inline-flex items-center justify-center w-10 h-10 font-semibold transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md text-gray-50 hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300"
              >
                <i class="bi bi-trash-fill"></i>
              </button>
            @endif
          </div>
        @endauth
      </div>
    </x-card>

    {{-- 第二階層留言 --}}
    <div class="comment-with-responses">
      <div class="space-y-6 responses">
        @forelse ($comment->subComments as $subComment)
          <div class="relative">
            <x-card
              x-data="{
                subCommentId: {{ $subComment->id }},
              }"
              class="flex group"
            >
              <div class="flex flex-col flex-1 md:flex-row">
                {{-- 大頭貼 --}}
                <div class="flex-none">
                  <a href="{{ route('users.index', ['user' => $subComment->user_id]) }}">
                    <img
                      src="{{ $subComment->gravatar_url }}"
                      alt="{{ $subComment->user_name }}"
                      class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400"
                    >
                  </a>
                </div>

                {{-- 留言 --}}
                <div class="w-full md:mx-4">
                  <p
                    class="mt-3 text-gray-600 whitespace-pre-wrap sm:mt-0 dark:text-gray-50">{{ $subComment->content }}</p>

                  <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center space-x-2 text-sm text-gray-400">
                      <div>{{ $subComment->user_name }}</div>
                      <div>&bull;</div>
                      <div>{{ $subComment->created_at->diffForHumans() }}</div>
                    </div>
                  </div>
                </div>

                {{-- 刪除留言 --}}
                @if (in_array(auth()->id(), [$subComment->user_id, $comment->post_user_id]))
                  <div
                    class="flex items-center justify-start mt-2 transition duration-150 opacity-0 md:mt-0 group-hover:opacity-100"
                  >
                    <button
                      x-on:click="
                        if (confirm('您確定要刪除此留言嗎？')) {
                          $wire.destroy(subCommentId)
                        }
                      "
                      class="inline-flex items-center justify-center w-10 h-10 font-semibold transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md text-gray-50 hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300"
                    >
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </div>
                @endif
              </div>
            </x-card>
          </div>
        @empty
        @endforelse
      </div>
    </div>
  @empty
  @endforelse
</div>

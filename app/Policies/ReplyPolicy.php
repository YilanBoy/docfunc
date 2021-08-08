<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function destroy(User $user, Post $post, Reply $reply)
    {
        return $user->isAuthorOf($reply) || $user->isAuthorOf($post);
    }
}

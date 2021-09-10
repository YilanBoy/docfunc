<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

class CommentPolicy extends Policy
{
    public function destroy(User $user, Comment $comment)
    {
        return $user->isAuthorOf($comment) || $user->isAuthorOf($comment->post);
    }
}

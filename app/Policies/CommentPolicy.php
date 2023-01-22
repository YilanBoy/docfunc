<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy extends Policy
{
    public function update(User $user, Comment $comment)
    {
        return $user->isAuthorOf($comment);
    }

    public function destroy(User $user, Comment $comment)
    {
        return $user->isAuthorOf($comment) || $user->isAuthorOf($comment->post);
    }
}

<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy extends Policy
{
    public function update(User $user, Post $post)
    {
        return $user->isAuthorOf($post);
    }

    public function destroy(User $user, Post $post)
    {
        return $user->isAuthorOf($post);
    }
}

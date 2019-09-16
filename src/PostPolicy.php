<?php

namespace Den1n\NovaBlog;

use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->can('blogManager'))
            return true;
    }

    public function viewAny($user): bool
    {
        return $user->can('blogViewPosts');
    }

    public function view($user, Post $post): bool
    {
        return $user->can('blogViewPosts');
    }

    public function create($user): bool
    {
        return $user->can('blogCreatePosts');
    }

    public function update($user, Post $post): bool
    {
        return $user->can('blogUpdatePosts') and $user->id == $post->author_id;
    }

    public function delete($user, Post $post): bool
    {
        return $user->can('blogDeletePosts') and $user->id == $post->author_id;
    }

    public function restore($user, Post $post): bool
    {
        return $user->can('blogDeletePosts') and $user->id == $post->author_id;
    }

    public function forceDelete($user, Post $post): bool
    {
        return $user->can('blogDeletePosts') and $user->id == $post->author_id;
    }
}

<?php

namespace Den1n\NovaBlog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use \Den1n\NovaBlog\Models\Post as Model;

class Post
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

    public function view($user, Model $post): bool
    {
        return $user->can('blogViewPosts');
    }

    public function create($user): bool
    {
        return $user->can('blogCreatePosts');
    }

    public function update($user, Model $post): bool
    {
        return $user->can('blogUpdatePosts') and $user->id == $post->author_id;
    }

    public function delete($user, Model $post): bool
    {
        return $user->can('blogDeletePosts') and $user->id == $post->author_id;
    }

    public function restore($user, Model $post): bool
    {
        return $user->can('blogDeletePosts') and $user->id == $post->author_id;
    }

    public function forceDelete($user, Model $post): bool
    {
        return $user->can('blogDeletePosts') and $user->id == $post->author_id;
    }
}
